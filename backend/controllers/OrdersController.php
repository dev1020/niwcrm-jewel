<?php

namespace backend\controllers;

use Yii;
use backend\models\Orders;
use backend\models\OrdersPayments;
use backend\models\Customers;
use backend\models\CompanyCustomers;
use backend\models\CompanySettings;
use backend\models\CompanyBranches;
use backend\models\CustomersBonuses;
use backend\models\OrdersSearch;
use backend\models\OrdersUploadExcelForm;
use backend\models\PaymentReceiptForm;
use backend\models\OrdersTransferLog;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use yii\web\UploadedFile;
use yii\helpers\BaseFileHelper;

use common\components\CustomerBonusesComp;
use common\components\CustomerBonusesGenerateComp;
use common\components\Sms;

/**
 * OrdersController implements the CRUD actions for Orders model.
 */
class OrdersController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'bulk-delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Orders models.
     * @return mixed
     */
    public function actionIndex()
    {    
		$session = Yii::$app->session;
        $searchModel = new OrdersSearch();
		if(yii::$app->user->can('executive')){
			$searchModel->created_by = yii::$app->user->identity->id ;
		}
		$searchModel->cancelled = 'no';
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		
		$company_id = $session['company.company_id'];
		$branch_id = $session['company.branch_id'];
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'company_id'=>$company_id,
        ]);
    }
	
	public function actionOpenedToday()
    {    
		$session = Yii::$app->session;
		
        $searchModel = new OrdersSearch();
		$searchModel->created_date = date('Y-m-d');
		if(yii::$app->user->can('executive')){
			$searchModel->created_by = yii::$app->user->identity->id ;
		}
		$searchModel->cancelled = 'no';
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		
		$company_id = $session['company.company_id'];
		$branch_id = $session['company.branch_id'];
        return $this->render('opened-today', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'company_id'=>$company_id,
        ]);
    }
	
	public function actionApprovalPending()
    {    
		$session = Yii::$app->session;
		
        $searchModel = new OrdersSearch();
		$searchModel->order_approved = 'no';
		$searchModel->cancelled = 'no';
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		
		$company_id = $session['company.company_id'];
		$branch_id = $session['company.branch_id'];
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'company_id'=>$company_id,
        ]);
    }
	
	
    /**
     * Displays a single Orders model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {   
        $request = Yii::$app->request;
		$model = $this->findModel($id);
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                    'title'=> "Orders #".$id,
                    'content'=>$this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
							Html::a('&nbsp;&nbsp;&nbsp;Pay&nbsp;&nbsp;&nbsp;',['pay','id'=>$model->id],['class'=>'btn btn-success','role'=>'modal-remote'])
                            
                ];    
        }else{
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }
    }
	
    /**
     * Creates a new Orders model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($existmodel=NULL)
    {
        $request = Yii::$app->request;
		$session = Yii::$app->session;
		
        if(isset($existmodel)){
			$model = $existmodel;
		}else{
			$model = new Orders();
		}   
		$model->scenario = 'newsale';		
		$model->company_id = $session['company.company_id'];
				
		$model->branch_id = $session['company.branch_id'];
		$model->order_date = date('Y-m-d');
		$model->weight = 0;
		if(yii::$app->user->can('executive')){
			$model->order_approved = 'no';
			$model->branch_id = $session['company.branch_id'];
		}
		if(CompanySettings::find()->where(['company_id'=>$session['company.company_id']])->exists()){
			$companySettings = CompanySettings::find()->where(['company_id'=>$session['company.company_id']])->one();
			//$model->settings['bonus_redemption'] = $companySettings->bonus_redemption;
			$model->settings = $companySettings;
			if($model->settings->multi_store == 'yes'){
				$model->scenario = 'newbranchsale';	
			}	
			if($model->settings->package == 'b'){
				$model->order_approved = 'no';
				return $this->basiccreate($model);
			}
			if($model->settings->package == 'bp'){
				return $this->basicpaycreate($model);    
			}
			if($model->settings->package == 'dp'){
				return $this->detailpaycreate($model); 
			}
			
		}else{
			return $this->basicpaycreate($model);    
		}        
    }
	
	
	public function actionPay($id)
    {   
        $request = Yii::$app->request;
		$orders = $this->findModel($id);
		$company_id = $orders->company_id;
		$branch_id = $orders->branch_id;
		//echo $orders->cust->companyCust->introducer->name;
		//exit;
		$transaction = Yii::$app->db->beginTransaction();
		
		if(CompanySettings::find()->where(['company_id'=>$company_id])->exists()){
			$companySettings = CompanySettings::find()->where(['company_id'=>$company_id])->one();
			$company_name = $companySettings->brand_name;
			$mutiple_payments_type = $companySettings->enable_multiple_payment_type;
			$orders->settings['bonus_redemption'] = $companySettings->bonus_redemption;
			
		}
		
		if(CompanyBranches::find()->where(['id'=>$branch_id])->exists()){
			$branch_name = CompanyBranches::find()->where(['id'=>$branch_id])->one()->branch_name;
		}else{
			$branch_name = '';
		}
		
		
		$ordersPayments = OrdersPayments::find()->where(['orders_id'=>$id])->all();
		$totalpaymenttillnow = OrdersPayments::find()->where(['orders_id'=>$id])->sum('amount');
		$paymentReceiptForm = new PaymentReceiptForm();
		$sms = new Sms();
		
		//if($orders->cust_id != NULL || $orders->cust_id !=''){
			$custid = $orders->cust_id;
			$customerBonusesComp = new CustomerBonusesComp();
			$bonuses = $customerBonusesComp->checkBonuses($custid,$orders->company_id);
			$bonus_available = $bonuses['available'];
			$max_redeem_points = ($orders->due_amount > $bonus_available)? $bonus_available : $orders->due_amount;
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
           if($request->isGet){
                return [
                    'title'=>'',
					'content'=>$this->renderAjax('order-payment',[
						'orders'=>$orders,
						'paymentReceiptForm'=>$paymentReceiptForm,
						'bonuses'=>$bonuses,
						'max_redeem_points'=>(int)$max_redeem_points,
						'bonus_available'=>$bonus_available,
						'ordersPayments'=>$ordersPayments,
						'totalpaymenttillnow'=>$totalpaymenttillnow,
						'mutiple_payments_type'=>$mutiple_payments_type,
					]),
					'size'=>'large',
                    'footer'=> ($orders->due_amount >0)? Html::button('Pay Now',['class'=>'btn btn-danger','type'=>"submit"]) : Html::button('Done',['class'=>'btn btn-danger','data-dismiss'=>"modal"])
                ];         
            }else if($paymentReceiptForm->load($request->post())&& $paymentReceiptForm->validate()){
				$totalpayment = 0;
				foreach($paymentReceiptForm->pay as $key=>$pay){
					
					if($pay !=null && $pay != 0){
						
						$ordersPayment = new OrdersPayments();
						$ordersPayment->orders_id = $paymentReceiptForm->orderid;
						$ordersPayment->payment_type = $key;
						$ordersPayment->amount = $pay;
						$ordersPayment->payment_date = date('y-m-d');
						
						if(!$ordersPayment->save()){
							 $transaction->rollBack();
							 return [
								//'forceReload'=>'#crud-datatable-pjax',
								'title'=> "Error in Payment Method #",
								'content'=>print_r($ordersPayment->errors),
								'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"])										
							]; 
						}
						if($key =='points'){
							$redeemBonuses = new CustomerBonusesGenerateComp();
							$pointsredeem = $redeemBonuses->redeemBonuses($orders->id,$pay);	
						}
						$totalpayment = $totalpayment+$pay;
					}
				}
				$orders = Orders::findOne($paymentReceiptForm->orderid);
				$orders->due_amount = $orders->due_amount - $totalpayment;
				
				if($orders->due_amount <= 0){
					$orders->status = 'completed';
				}
				if(!$orders->save()){
					$transaction->rollBack();
					return [
								//'forceReload'=>'#crud-datatable-pjax',
								'title'=> "Error in Order saving #",
								'content'=>print_r($orders->errors),
								'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"])										
							];
				}
				$ordersPayments = OrdersPayments::find()->where(['orders_id'=>$paymentReceiptForm->orderid])->all();
				$totalpaymenttillnow = OrdersPayments::find()->where(['orders_id'=>$paymentReceiptForm->orderid])->sum('amount');
				
				if($orders->status == 'completed' && $orders->order_approved=='yes'){
					// Loyalty Bonus Generation
					$custNewBonuses = new CustomerBonusesGenerateComp();
					$bonusloyal = $custNewBonuses->generateLoyaltyBonuses($orders->id);	
					$loyaltybonus = $bonusloyal['bonus'];
					if(isset($orders->cust->companyCust->introducer_id)){
						$bonusrefer = $custNewBonuses->generateReferralBonuses($orders->id);
						$referralbonus = $bonusrefer['bonus'];
					}
					$customerBonuses = new CustomerBonusesComp();
					$totalcustbonuses = $customerBonuses->checkBonuses($orders->cust_id,$company_id);
					$availablecustbonuses = $totalcustbonuses['available'];
					
					$sms_text_after_payment = CompanySettings::find()->where(['company_id'=>$company_id])->one()->sms_text_after_payment; 
					if($sms_text_after_payment!='' || $sms_text_after_payment!=null ){
						$searchitems  = array("BILLNO", "BILLVALUE", "TPOINTS","LPOINTS","RPOINTS","COMPANY","ORDERDATE","BRANCH");
						$replaceitems = array($orders->session_nos,$orders->total_amount,$availablecustbonuses,$loyaltybonus,"",$company_name,date('d-m-Y',strtotime($orders->order_date)),$branch_name);
						$text =str_replace($searchitems,$replaceitems,$sms_text_after_payment);
						$text =rawurlencode($text);
					}else{
						$text =str_replace('_CRMSPA_','%0D%0A',rawurlencode('Dear '.ucwords($orders->cust->name).',_CRMSPA_Thanks for visiting_CRMSPA_'.$company_name.'._CRMSPA_Your '.date('d-m-Y',strtotime($orders->order_date)).' Bill Details:_CRMSPA_ID:C'.$orders->cust->id.'_CRMSPA_Invoice:'.$orders->session_nos.'_CRMSPA_Amt:'.$orders->total_amount.'/-_CRMSPA_Points got:'.$loyaltybonus.'_CRMSPA_Points Available:'.$availablecustbonuses.'_CRMSPA_'));
					}
					
						//$sms->sendSms($orders->cust->companyCust->customer_number,$text,'PEARL');
						$sms->sendSms($orders->cust->companyCust->customer_number,$text);
						
						
						if(isset($orders->cust->companyCust->introducer_id)){
							$totalintroducerbonuses = $customerBonuses->checkBonuses($orders->cust->companyCust->introducer_id,$company_id);
							$introduceravailablebonuses = $totalintroducerbonuses['available'];
							
							$textreferral =str_replace('_CRMSPA_','%0D%0A',rawurlencode('Dear '.ucwords($orders->cust->companyCust->introducer->name).',_CRMSPA_Your referral '.ucwords($orders->cust->name).'has visited_CRMSPA_'.$company_name.' on '.$orders->order_date.'_CRMSPA_Your Referral Points:'.$referralbonus.'_CRMSPA_Total Points Available:'.$introduceravailablebonuses.'_CRMSPA_'));
							//$sms->sendSms($orders->cust->companyCust->introducer->contact,$textreferral,'PEARL');
							$sms->sendSms($orders->cust->companyCust->introducer->contact,$textreferral);
						}
					
				}
				//$model->save();
				$transaction->commit();
                return [
                    //'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "Categories #",
                    'content'=>$this->renderAjax('payment-success',[
                        'ordersPayments'=>$ordersPayments,
                        'totalpaymenttillnow'=>$totalpaymenttillnow,
                        'orders'=>$orders,
						'loyaltybonus'=>isset($loyaltybonus)? $loyaltybonus : 0,
						'referralbonus'=>isset($referralbonus)? $referralbonus : 0,
                    ]),
                    'footer'=> Html::button('Done',['class'=>'btn btn-danger pull-right','data-dismiss'=>"modal"])
                ];    
            }else{
				return [
                    'title'=>'',
					'content'=>$this->renderAjax('order-payment',[
						'orders'=>$orders,
						'paymentReceiptForm'=>$paymentReceiptForm,
						'bonuses'=>$bonuses,
						'max_redeem_points'=>(int)$max_redeem_points,
						'bonus_available'=>$bonus_available,
						'ordersPayments'=>$ordersPayments,
						'totalpaymenttillnow'=>$totalpaymenttillnow,
						'mutiple_payments_type'=>$mutiple_payments_type,
					]),
					'size'=>'large',
                    'footer'=> ($orders->due_amount >0)? Html::button('Pay Now',['class'=>'btn btn-danger','type'=>"submit"]) : Html::button('Done',['class'=>'btn btn-danger','data-dismiss'=>"modal"])
                                
                ]; 
			}    
        }else{
            
			if($paymentReceiptForm->load($request->post())&& $paymentReceiptForm->validate()){
				
				//print_r($request->post());exit;
				$totalpayment = 0;
				foreach($paymentReceiptForm->pay as $key=>$pay){
					
					if($pay !=null && $pay != 0){
						//echo $pay;
						$ordersPayment = new OrdersPayments();
						$ordersPayment->orders_id = $paymentReceiptForm->orderid;
						$ordersPayment->payment_type = $key;
						$ordersPayment->amount = $pay;
						$ordersPayment->payment_date = date('y-m-d');
						
						if(!$ordersPayment->save()){
							$transaction->rollBack();
							 return [
								//'forceReload'=>'#crud-datatable-pjax',
								'title'=> "Error in Payment Method #",
								'content'=>print_r($ordersPayment->errors),
								'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"])										
							]; 
						}
						
						if($key =='points'){
							$redeemBonuses = new CustomerBonusesGenerateComp();
							$pointsredeem = $redeemBonuses->redeemBonuses($orders->id,$pay);	
						}
						$totalpayment = $totalpayment+$pay;
					}
				}
				$orders = Orders::findOne($paymentReceiptForm->orderid);
				$orders->due_amount = $orders->due_amount - $totalpayment;
				
				if($orders->due_amount <= 0){
					$orders->status = 'completed';
				}
				if(!$orders->save()){
					$transaction->rollBack();
					print_r($orders->errors);
				}
				$ordersPayments = OrdersPayments::find()->where(['orders_id'=>$paymentReceiptForm->orderid])->all();
				$totalpaymenttillnow = OrdersPayments::find()->where(['orders_id'=>$paymentReceiptForm->orderid])->sum('amount');
				
				if($orders->status == 'completed' && $orders->order_approved =='yes'){
					// Loyalty Bonus Generation
					$custNewBonuses = new CustomerBonusesGenerateComp();
					$bonusloyal = $custNewBonuses->generateLoyaltyBonuses($orders->id);	
					$loyaltybonus = $bonusloyal['bonus'];
					if(isset($model->cust->introducer_customer_id)){
						$bonusrefer = $custNewBonuses->generateReferralBonuses($orders->id);
						$referralbonus = $bonusrefer['bonus'];
					}
					$customerBonuses = new CustomerBonusesComp();
					$totalcustbonuses = $customerBonuses->checkBonuses($orders->cust_id,$company_id);
					$availablecustbonuses = $totalcustbonuses['available'];
					
					
					$sms_text_after_payment = CompanySettings::find()->where(['company_id'=>$company_id])->one()->sms_text_after_payment; 
					if($sms_text_after_payment!='' || $sms_text_after_payment!=null ){
						$searchitems  = array("BILLNO", "BILLVALUE", "TPOINTS","LPOINTS","RPOINTS","COMPANY","ORDERDATE","BRANCH");
						$replaceitems = array($orders->session_nos,$orders->total_amount,$availablecustbonuses,$loyaltybonus,"",$company_name,date('d-m-Y',strtotime($orders->order_date)),$branch_name);
						$text =str_replace($searchitems,$replaceitems,$sms_text_after_payment);
						$text =rawurlencode($text);
					}else{
						$text =str_replace('_CRMSPA_','%0D%0A',rawurlencode('Dear '.ucwords($orders->cust->name).',_CRMSPA_Thanks for visiting_CRMSPA_'.$company_name.'._CRMSPA_Your '.date('d-m-Y',strtotime($orders->order_date)).' Bill Details:_CRMSPA_ID:C'.$orders->cust->id.'_CRMSPA_Invoice:'.$orders->session_nos.'_CRMSPA_Amt:'.$orders->total_amount.'/-_CRMSPA_Points got:'.$loyaltybonus.'_CRMSPA_Points Available:'.$availablecustbonuses.'_CRMSPA_'));
					}
						
						if(isset($orders->cust->introducerCustomer->id)){
							$totalintroducerbonuses = $customerBonuses->checkBonuses($orders->cust->introducerCustomer->id,$company_id);
							$introduceravailablebonuses = $totalintroducerbonuses['available'];
							
							$textreferral =str_replace('_CRMSPA_','%0D%0A',rawurlencode('Dear '.ucwords($orders->cust->introducerCustomer->name).',_CRMSPA_Your referral '.ucwords($orders->cust->name).'has visited_CRMSPA_'.$company_name.' on '.$orders->order_date.'_CRMSPA_Your Referral Points:'.$referralbonus.'_CRMSPA_Total Points Available:'.$introduceravailablebonuses.'_CRMSPA_'));
							$sms->sendSms($orders->cust->introducerCustomer->contact,$textreferral);
						}
				}
				//$model->save();
				$transaction->commit();
                return $this->render('payment-success',[
                        'ordersPayments'=>$ordersPayments,
                        'totalpaymenttillnow'=>$totalpaymenttillnow,
                        'orders'=>$orders,
						'loyaltybonus'=>isset($loyaltybonus)? $loyaltybonus : 0,
						'referralbonus'=>isset($referralbonus)? $referralbonus : 0,
                    ]);    
            }else{
				return $this->render('order-payment', [
					'orders'=>$orders,
					'paymentReceiptForm'=>$paymentReceiptForm,
					'bonuses'=>$bonuses,
					'max_redeem_points'=>(int)$max_redeem_points,
					'bonus_available'=>$bonus_available,
					'ordersPayments'=>$ordersPayments,
					'totalpaymenttillnow'=>$totalpaymenttillnow,
					'mutiple_payments_type'=>$mutiple_payments_type,
				]);
			}
        }
    }
	
	protected function orderCompletedAfterPayment(){
		
	}
	/**
     * new Orders Approval.
     * Throw ForbiddenHttpException if dont have access
     * check all type of orders on settings and on order complete generate bonus points and send sms if checked .
     * @return mixed
     */
	
    public function actionOrderApproval($id)
    {    
		$request = Yii::$app->request;
		$session = Yii::$app->session;
		$company_id = $session['company.company_id'];
		$branch_id = $session['company.branch_id'];
		$transaction = Yii::$app->db->beginTransaction();
		$model = $this->findModel($id);
		if((!yii::$app->user->can('Admin')&&$model->company_id!=$company_id)||(!yii::$app->user->can('manager')&&$model->company_id!=$company_id)||yii::$app->user->can('executive')){
			throw new \yii\web\ForbiddenHttpException;						
		}
		
		
		$companySettings = CompanySettings::find()->where(['company_id'=>$company_id])->one();
		$company_name = $companySettings->brand_name; 
		$package = $companySettings->package;
		$loyaltypercentage = $companySettings->loyalty_bonus_percentage;
		
		
		$points_to_generate = (int)round($model->total_amount*$loyaltypercentage/100);
		
		
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
			if($model->order_approved=='yes'){
			return [
				'forceReload'=>'#crud-datatable-pjax',
				'title'=> "Warning Order Approved",
				'content'=>'<h4 class="text-danger">This Order is Already approved.</h4>',
				'footer'=> Html::button('DONE',['class'=>'btn btn-danger pull-right','data-dismiss'=>"modal"])	
			];
			}
			if($request->isGet){
				return [
                    'title'=> "Orders #".$id,
                    'content'=>$this->renderAjax('approve', [
                        'model' => $model,
						'package'=>$package,
						'points_to_generate'=>$points_to_generate,
                    ]),
                    'footer'=> Html::button('CLOSE',['class'=>'btn btn-danger pull-left','data-dismiss'=>"modal"]).
							Html::button('APPROVE',['class'=>'btn btn-primary','type'=>"submit"])
                            
                ];
			}else if($model->load($request->post())){
			    $model->order_approved = 'yes';
				$model->order_approved_by = yii::$app->user->identity->id;
				if(!$model->save()){
					$transaction->rollBack();
					return [
					'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "Orders #".$id,
                    'content'=>'<h4 class="text-danger">Error In Processing. Please Try Again</h4><p>'.print_r($model->errors).'</p>',
                    'footer'=> Html::button('Done',['class'=>'btn btn-danger pull-right','data-dismiss'=>"modal"]),                        
					];
				}else{
					$custNewBonuses = new CustomerBonusesGenerateComp();
					$customerBonuses = new CustomerBonusesComp();
					if($package=='b'){ // for package b remaining payment
						if($model->due_amount>0){
							$orderPayments = new OrdersPayments();
							$orderPayments->orders_id= $model->id;
							$orderPayments->payment_type= 'consolidated';
							$orderPayments->amount = $model->due_amount;
							$orderPayments->payment_date = date('Y-m-d');
							if(!$orderPayments->save()){
								$transaction->rollBack();
								return [
								'title'=> "Error",
								'content'=>print_r($orderPayments->errors)
								];
							}
							$model->due_amount = 0;
						}
						$model->status = 'completed';					
						if(!$model->save()){
							$transaction->rollBack();
						}					
					}
					if($model->order_approved=='yes' && $model->status == 'completed'){ 					
						
							$bonus = new CustomersBonuses();
							$bonus->cust_id = $model->cust_id;
							$bonus->type = 'loyalty';
							$bonus->order_id = $model->id;
							$bonus->created_date = date('y-m-d');
							$bonus->bonus_amount = $request->post('givenpoints')?$request->post('givenpoints'):0;
							if(!$bonus->save()){
								$transaction->rollBack();
							}
							$loyaltybonus = $bonus->bonus_amount;
							$totalcustbonuses = $customerBonuses->checkBonuses($model->cust_id,$company_id);
							$availablecustbonuses = $totalcustbonuses['available'];					
						
						
						//introducer referral generation
						if(isset($model->cust->companyCust->introducer_id)){
							$bonusrefer = $custNewBonuses->generateReferralBonuses($model->id);
							$referralbonus = $bonusrefer['bonus'];
							$totalintroducerbonuses = $customerBonuses->checkBonuses($model->cust->introducer_customer_id,$company_id);
							$introduceravailablebonuses = $totalintroducerbonuses['available'];
							
						}
						// Send SMS To customer And Introducer(IF Exists)
						if($request->post('sendsms')==1){
							$this->sendSmstoCustomerAfterPayment($model,$companySettings,$availablecustbonuses,$loyaltybonus);
							if(isset($model->cust->companyCust->introducer_id)){
							$this->sendSmstoIntroducerAfterPayment($model,$companySettings,$introduceravailablebonuses,$referralbonus);
							}
						}
					}
					$transaction->commit();
					return [
						'forceReload'=>'#crud-datatable-pjax',
						'title'=> "Orders #".$id,
						'content'=>'Order is approved',
						'footer'=> Html::button('CLOSE',['class'=>'btn btn-danger pull-left','data-dismiss'=>"modal"]),                            
					];
				}				
			}else{
				return [
                    'title'=> "Orders #".$id,
                    'content'=>$this->renderAjax('approve', [
                        'model' => $model,
						'package'=>$package,
						'points_to_generate'=>$points_to_generate,
                    ]),
                    'footer'=> Html::button('CLOSE',['class'=>'btn btn-danger pull-left','data-dismiss'=>"modal"]).
							Html::button('APPROVE',['class'=>'btn btn-primary','type'=>"submit"])
                            
                ];
			}                
        }else{
            throw new \yii\web\NotFoundHttpException;
        }
    }
	/**
     * Search for exixting custromers and orders .
     * match via customer number , name and order invoice 
     * show number name points of customer and invoice number , order date and pay for orders 
     * @return mixed
     */
    public function actionSearchToOrder($custid){
		$request = Yii::$app->request;
		$session = Yii::$app->session;
		
        $model = new Orders();  
		$model->scenario = 'newsale';
		$model->company_id = $session['company.company_id'];
		$model->branch_id = $session['company.branch_id'];
		$model->order_date = date('Y-m-d');
		
		if(CompanyCustomers::find()->where(['cust_id'=>$custid])->exists()){
			$customer = CompanyCustomers::find()->where(['cust_id'=>$custid])->one();
			$model->customer_contact = 	$customer->customer_number;
			$model->customer_name = $customer->cust->name;
			
			$customerBonusesComp = new CustomerBonusesComp();
			$bonuses = $customerBonusesComp->checkBonuses($custid,$model->company_id);
			$bonus_available = $bonuses['available'];
			$model->points = $bonus_available;
		}
		return $this->actionCreate($model);
		
	}
    /**
     * Updates an existing Orders model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);       

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Update Orders #".$id,
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
                ];         
            }else if($model->load($request->post()) && $model->save()){
                return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "Orders #".$id,
                    'content'=>$this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Edit',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                ];    
            }else{
                 return [
                    'title'=> "Update Orders #".$id,
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
                ];        
            }
        }else{
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }
	public function actionTest()
	{
		$customerBonusesComp = new CustomerBonusesComp();
		$bonuses = $customerBonusesComp->checkBonuses(252,11);
		echo $bonus_available = $bonuses['available'];
	}
    
    public function actionGetCustomername()
    {
        $request = Yii::$app->request;
		$session = Yii::$app->session;
		$company_id = $session['company.company_id'];
        $number = $request->post('number');
		
        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if(Customers::find()->where(['contact'=>$number])->exists()){
				$customers = Customers::find()->where(['contact'=>$number])->one();
				$customername = $customers->name;
				$customerBonusesComp = new CustomerBonusesComp();
				$bonuses = $customerBonusesComp->checkBonuses($customers->id,$company_id);
				$bonus_available = $bonuses['available'];
				return ['name'=>$customername,'bonus' =>$bonus_available,'id'=>$customers->id];
			}else{
				return ['name'=>'','bonus'=>''];
			}
        }else{
			throw new yii\web\NotFoundHttpException;
		}
    }
    /**
     * Delete an existing Orders model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
		$model->cancelled= 'yes';
		$model->cancelled_by = Yii::$app->user->identity->id;
		$model->cancelled_at = date('Y-m-d h:i:s');
		$model->save();
		if(CustomersBonuses::find()->where(['order_id'=>$id])->exists()){
			$allbonuses = CustomersBonuses::find()->where(['order_id'=>$id])->all();
			foreach($allbonuses as $bonuses){
				$bonuses->cancelled = 'yes';
				$bonuses->save();
			}
		}

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose'=>true,'forceReload'=>'#crud-datatable-pjax'];
        }else{
            /*
            *   Process for non-ajax request
            */
            return $this->redirect(['index']);
        }


    }
	/**
	for all Orders 
	*/
	public function actionBulkSms()
    {        
		//$smstext = rawurlencode($request->post( 'smstext' ));
		//$sms = new Sms();
        
		$request = Yii::$app->request;
		$session = Yii::$app->session;
		$company_id = $session['company.company_id'];
		$companySettings = CompanySettings::find()->where(['company_id'=>$company_id])->one();
        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
			$ids = explode(',',$request->post('ids'));
			$custbonuses = new CustomerBonusesComp();
			$sms = new Sms();
			foreach ( $ids as $id ){
				if(Orders::find()->where(['id'=>$id])->exists()){
					$orders = Orders::find()->where(['id'=>$id])->one();
					//if($orders->sms_delivered=='no'){
						$loyaltybonus = CustomersBonuses::find()->where(['order_id'=>$id,'type'=>'loyalty'])->one()->bonus_amount;
						$totalcustbonuses = $custbonuses->checkBonuses($orders->cust_id,$orders->company_id);
						$availablecustbonuses = $totalcustbonuses['available'];
						
						
						$this->sendSmstoCustomerAfterPayment($orders,$companySettings,$availablecustbonuses,$loyaltybonus);
						if(isset($orders->cust->introducer_customer_id)){
							$referralbonus = CustomersBonuses::find()->where(['order_id'=>$id,'type'=>'referral'])->one()->bonus_amount;
							$totalintroducerbonuses = $customerBonuses->checkBonuses($orders->cust->introducer_customer_id,$company_id);
							$introduceravailablebonuses = $totalintroducerbonuses['available'];
							$this->sendSmstoIntroducerAfterPayment($orders,$companySettings,$introduceravailablebonuses,$referralbonus);
						}						
						$orders->sms_delivered='yes';
						if(!$orders->save()){
							return $orders->errors;
						}
					//}
				}
			}
            return ['status'=>true,'msg'=>'Send Successfully'];
        }       
    }
	
	public function actionTransferOrders()
    {        
		//$smstext = rawurlencode($request->post( 'smstext' ));
		//$sms = new Sms();
        
		$request = Yii::$app->request;
		$session = Yii::$app->session;
		$company_id = $session['company.company_id'];
		if(yii::$app->user->can('Admin') || yii::$app->user->can('manager')){
			if($request->isAjax){
				/*
				*   Process for ajax request
				*/
				Yii::$app->response->format = Response::FORMAT_JSON;
				$ids = explode(',',$request->post('ids'));
				$branch_id = $request->post('branchid');
				foreach ( $ids as $id ){
					if(Orders::find()->where(['id'=>$id])->exists()){
						$orders = Orders::find()->where(['id'=>$id])->one();
						$fromBranchid = isset($orders->branch->id)?$orders->branch->id:'NULL';
						$fromBranchName = isset($orders->branch->branch_name)?$orders->branch->branch_name:'N.A';
						$toBranchName = CompanyBranches::findOne($branch_id)->branch_name;
						$orders->branch_id = $branch_id;
							if(!$orders->save()){
								return $orders->errors;
							}
						$ordersTransferLog = new OrdersTransferLog();
						$ordersTransferLog->order_id=$orders->id;
						$ordersTransferLog->branch_from_id = $fromBranchid;
						$ordersTransferLog->branch_from_name = $fromBranchName;
						$ordersTransferLog->branch_to_id = $branch_id;
						$ordersTransferLog->branch_to_name = $toBranchName;
						$ordersTransferLog->transferred_by = yii::$app->user->identity->id;
						$ordersTransferLog->transferred_at = date('Y-m-d h-i-s');
						//$ordersTransferLog->save();
						if(!$ordersTransferLog->save()){
								return $ordersTransferLog->errors;
							}
					}
				}
				return ['status'=>true,'msg'=>'Transfer Successfully'];
			} 
		}else{
			throw new \yii\web\ForbiddenHttpException;						
		}
    }
	
	public function actionImportExcel()
    {        
		$inputFile = 'uploads/test.xlsx' ;
		$request = Yii::$app->request;
		$session = Yii::$app->session;
		
		$model = new OrdersUploadExcelForm();
		
		if($request->isAjax){
			Yii::$app->response->format = Response::FORMAT_JSON;
			
			if($request->isGet){
				return [
                    'title'=> "Upload Excel File",
                    'content'=>$this->renderAjax('importExcel', [
						'model'=>$model
                    ]),
                    'footer'=> Html::button('Upload & Save Data',['class'=>'btn btn-success','type'=>"submit"]),
                ];
			}else if($model->load($request->post()) ){
				$model->excel_file = UploadedFile::getInstance($model,'excel_file');					
				if($model->validate()){
					$path = Yii::getAlias('@backend').'/web/uploads/ImportExcel/';
					BaseFileHelper::createDirectory($path,0777,false);
					
					$model->excel_file->saveAs(Yii::getAlias($path.'Order'.date('y-m-d h-i-s').'.'.$model->excel_file->extension));
					$inputFile = $path.'Order'.date('y-m-d h-i-s').'.'.$model->excel_file->extension;
					
					try{
						$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFile);
						$sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
						
					}catch (InvalidArgumentException $e) {
						$helper->log('Error loading file "' . pathinfo($inputFile, PATHINFO_BASENAME) . '": ' . $e->getMessage());
					}
					for($i=2;$i<count($sheetData);$i++){
						$datatoinsert = array_values($sheetData[$i]);
						if($datatoinsert[0]!=''){
							
							if(Customers::find()->where(['contact'=>$datatoinsert[0]])->exists()){
								$customer = Customers::find()->where(['contact'=>$datatoinsert[0]])->one();
								
							}else{
								$customer = new Customers();
								$customer->contact = $datatoinsert[0];
								if(!$customer->save()){
								return [
									'title'=> "Error",
									'content'=>print_r($customer->errors),
									'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]),
								];
							}
							}
							$orders = new Orders();
							$orders->cust_id = $customer->id;
							$orders->session_nos  = $datatoinsert[1];
							$orders->order_date = date("y-m-d", strtotime($datatoinsert[2]));;
							$orders->total_amount = $datatoinsert[3];
							$orders->created_by = yii::$app->user->identity->id;
							$orders->status = 'completed';
							$orders->due_amount = 0;
							$orders->company_id = $session['company.company_id'];
							if(!$orders->save()){
								return [
									'title'=> "Error",
									'content'=>print_r($orders->errors),
									'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]),
								];
							}
							
							
							$custNewBonuses = new CustomerBonusesGenerateComp();
							$bonusloyal = $custNewBonuses->generateLoyaltyBonuses($orders->id);					
							if(isset($model->cust->introducer_customer_id)){
								$bonusrefer = $custNewBonuses->generateReferralBonuses($orders->id);
							}					
						}				
						
					}
					return [
					'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "Import Successfully",
                    'content'=>"Import Successfully",
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]),
					];
					
					
				}else{
					return [
						'title'=> "Upload Excel File",
						'content'=>$this->renderAjax('importExcel', [
							'model'=>$model
						]),
						'footer'=> Html::button('Upload & Save Data',['class'=>'btn btn-success','type'=>"submit"]),
					];
				}
			}
		}
    }
	
	public function actionDownloadSampleExcel(){
		$path = Yii::getAlias('@backend').'/web/uploads';
		$file = $path . '/sample.xlsx';
		if (file_exists($file)) {
		   Yii::$app->response->sendFile($file)->send();
		}else{
			echo "NoT exists";
		}
	}
     /**
     * Delete multiple existing Orders model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionBulkDelete()
    {        
        $request = Yii::$app->request;
        $pks = explode(',', $request->post( 'pks' )); // Array or selected records primary keys
        foreach ( $pks as $pk ) {
			$model = $this->findModel($pk);
			$model->cancelled= 'yes';
			$model->cancelled_by = Yii::$app->user->identity->id;
			$model->cancelled_at = date('Y-m-d h:i:s');
			$model->save();
        }

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose'=>true,'forceReload'=>'#crud-datatable-pjax'];
        }else{
            /*
            *   Process for non-ajax request
            */
            return $this->redirect(['index']);
        }
       
    }
	protected function basiccreate($model)
	{
		$request = Yii::$app->request;
		$session = Yii::$app->session;
		if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Create new Orders",
                    'content'=>$this->renderAjax('basiccreate', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            }else if($model->load($request->post()) ){
				$model = $this->savemodelwithcustomer($model);
				//print_r($model->attributes);
				//exit;
				$customerBonuses = new CustomerBonusesComp();
				$totalcustbonuses = $customerBonuses->checkBonuses($model->cust_id,$model->company_id);
				$availablecustbonuses = $totalcustbonuses['available'];
				
				if($model->points > $availablecustbonuses){
					$model->addError('points','Points Exceeded maximum point of '.$availablecustbonuses);
					return [
					'title'=> "Create new Orders",
					'content'=>$this->renderAjax('basiccreate', [
						'model' => $model,
					]),
					'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
								Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
		
					];
				}
				if($model->save()){
					if($model->points !=null && $model->points != 0){
						
						$ordersPayment = new OrdersPayments();
						$ordersPayment->orders_id = $model->id;
						$ordersPayment->payment_type = 'points';
						$ordersPayment->amount = $model->points;
						$ordersPayment->payment_date = date('y-m-d');						
						$ordersPayment->save();
						
						$redeemBonuses = new CustomerBonusesGenerateComp();
						$pointsredeem = $redeemBonuses->redeemBonuses($model->id,$model->points);	
						
						$model->due_amount = $model->total_amount-$model->points;
						$model->save();
					}
					
					return [
                    //'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "View",
                    'content'=>$this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Create More',['create'],['class'=>'btn btn-primary','role'=>'modal-remote'])
        
					];
					
				}else{
					return [
                    'title'=> "Create new Orders",
                    /*'content'=>$this->renderAjax('basiccreate', [
                        'model' => $model,
                    ]),*/
					'content'=>print_r($model->errors),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
        
					]; 
				}         
            }
        }else{
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post())){
				$model = $this->savemodelwithcustomer($model);
				print_r($model);
				exit;
				$customerBonuses = new CustomerBonusesComp();
				$totalcustbonuses = $customerBonuses->checkBonuses($model->cust_id,$model->company_id);
				$availablecustbonuses = $totalcustbonuses['available'];
				if($model->points > $availablecustbonuses){
					$model->addError('points','Points Exceeded maximum point of '.$availablecustbonuses);
					return $this->render('basiccreate', [
                    'model' => $model,
					]);
				}
				if($model->save()){
					if($model->points !=null && $model->points != 0){
						
						$ordersPayment = new OrdersPayments();
						$ordersPayment->orders_id = $model->id;
						$ordersPayment->payment_type = 'points';
						$ordersPayment->amount = $model->points;
						$ordersPayment->payment_date = date('y-m-d');						
						$ordersPayment->save();
						
						$redeemBonuses = new CustomerBonusesGenerateComp();
						$pointsredeem = $redeemBonuses->redeemBonuses($model->id,$model->points);	
						
						$model->due_amount = $model->total_amount-$model->points;
						$model->save();
					}
					return $this->redirect(['view', 'id' => $model->id]);
					
				}else{
					return $this->render('basiccreate', [
                    'model' => $model,
					]);
				} 
            }else{
				 return $this->render('basiccreate', [
                    'model' => $model,
					]);
			}
        }
	}
	protected function basicpaycreate($model)
	{
		$request = Yii::$app->request;
		$session = Yii::$app->session;
		if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Create new Orders",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            }else if($model->load($request->post())){	
				$model = $this->savemodelwithcustomer($model);
				if($model->save()){
					return [
                    //'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "View",
                    'content'=>$this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Pay',['pay','id'=>$model->id],['class'=>'btn btn-success','role'=>'modal-remote']).
                            Html::a('Create More',['create'],['class'=>'btn btn-primary','role'=>'modal-remote'])
        
					];
					
				}else{
					return [
                    'title'=> "Create new Orders",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
        
					]; 
				}                         
            }
        }else{
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post())) {
				$model = $this->savemodelwithcustomer($model);
				if($model->save()){
					return $this->redirect(['view', 'id' => $model->id]);
				}else{
					 return $this->render('create', [
                    'model' => $model,
					]); 
				}
            }else{
				 return $this->render('create', [
                    'model' => $model,
					]);
			}
        }
	}
	protected function savemodelwithcustomer($model)
	{
		// SAVE NEW CUSTOMERS IN CUSTOMERS TABLE
		if(Customers::find()->where(['contact'=>$model->customer_contact])->exists()){
			$customer = Customers::find()->where(['contact'=>$model->customer_contact,])->one();
			if($customer->name =='' || $customer->name==NULL){
				$customer->name = $model->customer_name;
				$customer->save();
			}			
		}else{
			$customer = new Customers();
			$customer->contact = $model->customer_contact;
			$customer->name = $model->customer_name;
			$customer->save();
		}
		// SAVE NEW CUSTOMER ID WITH COMPANY IN COMPANYCUSTOMERS TABLE
		if(!CompanyCustomers::find()->where(['company_id'=>$model->company_id,'cust_id'=>$customer->id])->exists()){
			$company_customer = new CompanyCustomers();
			$company_customer->company_id = $model->company_id;
			$company_customer->cust_id = $customer->id;
			$company_customer->customer_number = $customer->contact;
			$company_customer->save();
		}
		
		$model->cust_id = $customer->id;
		//echo $model->cust_id = $customer->id;
		//exit;
		$model->created_by = yii::$app->user->identity->id;
		$model->created_date = date('y-m-d');
		$model->status = 'isdue';
		$model->due_amount = $model->total_amount;
		return $model;
	}
	
	
	protected function sendSmstoCustomerAfterPayment($model,$companySettings,$availablecustbonuses,$loyaltybonus){
		$sms = new Sms();
		$company_name = $companySettings->brand_name;  
		$sms_text_after_payment = $companySettings->sms_text_after_payment; 
		if($sms_text_after_payment!='' || $sms_text_after_payment!=null ){
			$searchitems  = array("BILLNO", "BILLVALUE", "TPOINTS","LPOINTS","RPOINTS","COMPANY","ORDERDATE");
			$replaceitems = array($model->session_nos,$model->total_amount,$availablecustbonuses,$loyaltybonus,"",$company_name,date('d-m-Y',strtotime($model->order_date)));
			$text =str_replace($searchitems,$replaceitems,$sms_text_after_payment);
			$text =rawurlencode($text);
		}else{
			$text =str_replace('_CRMSPA_','%0D%0A',rawurlencode('Dear '.ucwords($model->cust->name).',_CRMSPA_Thanks for visiting_CRMSPA_'.$company_name.'._CRMSPA_Your '.date('d-m-Y',strtotime($model->order_date)).' Bill Details:_CRMSPA_ID:C'.$model->cust->id.'_CRMSPA_Invoice:'.$model->session_nos.'_CRMSPA_Amt:'.$model->total_amount.'/-_CRMSPA_Points got:'.$loyaltybonus.'_CRMSPA_Points Available:'.$availablecustbonuses.'_CRMSPA_'));
		}
		return $sms->sendSms($model->cust->contact,$text);
	 }
	 
	 protected function sendSmstoIntroducerAfterPayment($model,$companySettings,$introduceravailablebonuses,$referralbonus)
	 {
		$sms = new Sms();
		$company_name = $companySettings->brand_name;  
		
		$textreferral =str_replace('_CRMSPA_','%0D%0A',rawurlencode('Dear '.ucwords($model->cust->introducerCustomer->name).',_CRMSPA_Your referral '.ucwords($model->cust->name).'has visited_CRMSPA_'.$company_name.' on '.$model->order_date.'_CRMSPA_ Bill Details:_CRMSPA_Amt:'.$model->total_amount.'/-_CRMSPA_Your Referral Points:'.$referralbonus.'_CRMSPA_Total Points Available:'.$introduceravailablebonuses.'_CRMSPA_'));
		$sms->sendSms($model->cust->introducerCustomer->contact,$textreferral);
		 
	 }

	
	
    /**
     * Finds the Orders model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Orders the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Orders::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
