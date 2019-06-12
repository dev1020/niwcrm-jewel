<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\Helpers\Html;
use yii\Helpers\Url;
use yii\Helpers\Json;
use yii\helpers\BaseFileHelper;
use yii\helpers\ArrayHelper;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use \yii\web\Response;

use yii\base\InvalidParamException;
use yii\web\ForbiddenHttpException;
use yii\web\BadRequestHttpException;

use common\models\User;


use backend\models\Categories;
use backend\models\Customers;
use backend\models\CustomersBonuses;
use backend\models\CustomersLog;
use backend\models\CustomersServices;

use backend\models\Orders;
use backend\models\OrdersPayments;
use backend\models\OrdersDetails;


use backend\models\Services;
use backend\models\SeatsAndChairs;
use backend\models\ServicesToCustomerSelectionForm;
use backend\models\PaymentReceiptForm;

use common\components\CustomerBonusesComp;


/**
 * OrderController implements the CRUD actions for Order model.
 */
class StationController extends Controller
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
     * Lists all Order models.
     * @return mixed
     */
    public function actionIndex()
    {    
		$seats = SeatsAndChairs::find()->asArray()->all();
		$customers_now = CustomersLog::find()->where(['status'=>'open'])->all();
        return $this->render('index',[
				'seats' => $seats,
				'customers_now' => $customers_now,
				]);
		
    }
	
	public function actionAddCustomer()
    {
        $request = Yii::$app->request;
        $model = new Customers(); 
		$logmodel = new CustomersLog();
		
		$existingCustomers = Customers::find()->asArray()->all();

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Start Session",
                    'content'=>$this->renderAjax('addcustomer', [
                        'model' => $model,
                        'existingCustomers' => $existingCustomers,
                    ]),
                    //'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                      //          Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            }else if($model->load($request->post())){
				if(CustomersLog::find()->orderBy(['id'=>SORT_DESC])->exists()){
					$lastSessionNo = CustomersLog::find()->orderBy(['id'=>SORT_DESC])->one()->session_no + 1;
				}else{
					$lastSessionNo = 10000;
				}
				if(!Customers::find()->where(['contact'=>$model->contact])->exists()){
					if(!$model->save()){
					return ['title'=> "Start Session",
                    'content'=>$this->renderAjax('addcustomer', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
					];
					}else{
						$logmodel->cust_id = $model->id;
						$logmodel->log_date = date('y-m-d');
						$logmodel->start_session_time = date('h:i:s');
						$logmodel->status = 'open';
						$logmodel->session_no = $lastSessionNo;
						if(!$logmodel->save()){
							return ['error'=>$logmodel->errors];
						}
						return [
							'id'=>$model->id,
							'name'=>$model->name,
							'gender'=>$model->gender,
							'entrystatus'=>true,
							'session_no'=>$lastSessionNo,
							'image'=>\yii\helpers\Url::to('@frontendimage'.'/new-'.$model->gender.'.png'),
						];
					}
				}else{
					$existmodel = Customers::find()->where(['contact'=>$model->contact])->one();
						$logmodel->cust_id = $existmodel->id;
						$logmodel->log_date = date('y-m-d');
						$logmodel->start_session_time = date('h:i:s');
						$logmodel->status = 'open';
						$logmodel->session_no = $lastSessionNo;
						if(!$logmodel->save()){
							return ['error'=>$logmodel->errors];
						}
						return [
							'id'=>$existmodel->id,
							'name'=>$existmodel->name,
							'gender'=>$existmodel->gender,
							'entrystatus'=>true,
							'session_no'=>$lastSessionNo,
							'image'=>\yii\helpers\Url::to('@frontendimage'.'/new-'.$existmodel->gender.'.png'),
						];
				}
				
               
            }else{           
                return [
                    'title'=> "Start Session",
                    'content'=>$this->renderAjax('addcustomer', [
                        'model' => $model,
                        'existingCustomers' => $existingCustomers,
						
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            }
        }
    }
	
	public function actionAddServicesToCustomer($id,$cust_session){
		
		$request = Yii::$app->request;
		//$customer = CustomersLog::find()->where(['cust_id'=>$id,'session_no'=>$cust_session])->one();
		$customer = Customers::findOne($id);
		
		if(CustomersServices::find()->where(['cust_id'=>$id,'session_no'=>$cust_session,'services_date'=>date('y-m-d')])->exists()){
			$customerSubtotal =  CustomersServices::find()->where(['cust_id'=>$id,'services_date'=>date('y-m-d')])->sum('services_price');
		}else{
			$customerSubtotal = 0;
		}
		$model = new ServicesToCustomerSelectionForm();
		if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> 'Add Services To '.ucwords($customer->name),
                    'content'=>$this->renderAjax('addservicestocustomer', [
                         'model'=>$model,
                         'customer'=>$customer,
                         'cust_session'=>$cust_session,
                         'customerSubtotal'=>$customerSubtotal,						 
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::a('<< To Services',['station/customer-services','id'=>$id,'cust_session'=>$cust_session],['class'=>'btn btn-primary','role'=>"modal-remote"])
        
                ];         
            }else{           
                return [
                    'title'=> "Add Services To ",
                    'content'=>$this->renderAjax('addservicestocustomer', [
                        'model'=>$model,
                        'customer'=>$customer,
						'cust_session'=>$cust_session,
                        'customerSubtotal'=>$customerSubtotal,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::a('<< To Services',['station/customer-services','id'=>$id,'cust_session'=>$cust_session],['class'=>'btn btn-primary','role'=>"modal-remote"])
        
                ];         
            }
        }
	}
	
	
	
	public function actionSubcat() {
    $out = [];
		if (isset($_POST['depdrop_parents'])) {
			$parents = $_POST['depdrop_parents'];
			if ($parents != null) {
				$cat_id = $parents[0];
				$categories = Categories::find()->where(['category_root'=>$cat_id])->all(); 
				// the getSubCatList function will query the database based on the
				// cat_id and return an array like below:
				// [
				//    ['id'=>'<sub-cat-id-1>', 'name'=>'<sub-cat-name1>'],
				//    ['id'=>'<sub-cat_id_2>', 'name'=>'<sub-cat-name2>']
				// ]
				
				foreach($categories as $category){
					$out[] = ['id' => $category['category_id'], 'name' => $category['category_name']];
				}
				echo \yii\helpers\Json::encode(['output'=>$out, 'selected'=>'']);
				return;
			}
		}
		echo Json::encode(['output'=>'', 'selected'=>'']);
	}
	
	public function actionAddServices() {
		Yii::$app->response->format = Response::FORMAT_JSON;
		$services = array_filter($_POST['services']);
		
		$customer_id = $_POST['ServicesToCustomerSelectionForm']['customer_id'];
		$cust_session = $_POST['ServicesToCustomerSelectionForm']['cust_session'];
		if(count(array_filter($services))>0){
			 sort($services);
			// return $services;
			for($i=0;$i<count($services);$i++){
				if($services[$i]!=""){
					$model = new CustomersServices();
					$model->cust_id = $customer_id;
					$model->service_id = $services[$i];
					$model->service_status = 'queue';
					$model->services_date = date('y-m-d');
					$model->services_price = Services::findOne($services[$i])->price;
					$model->session_no = $cust_session;
					if($model->save()){
						
						$servicessaved[] = ['id'=>$services[$i]]; 
					}
				}			
			}
			return $servicessaved;
		}else{
			return ['status'=>'nothing added'];
		}
	}
	
	public function actionDeleteServices($id,$cust_id,$cust_session)
    {
        $request = Yii::$app->request;
        CustomersServices::findOne($id)->delete();
        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
				return $this->actionCustomerServices($cust_id,$cust_session) ; 
        }
    }
	
	public function actionServices()
	{
		Yii::$app->response->format = Response::FORMAT_JSON;
		$cat[] = $_POST['catid'];
		$cat[] = $_POST['subcatid'];
		$customer_id = $_POST['custid'];
		$cust_session = $_POST['cust_session'];
		$output = '<table class="table table-bordered servicestable"><tr class="bg-primary"><th class="text-center">Image</th><th class="text-center">Service Name</th><th class="text-center">Price</th></tr><tbody>';
		$endformsubmit ='';	
			if (isset($_POST)) {
				$services = Services::find()->where(['category_id'=>$cat])->all();
				if(count($services)>0){
					$endformsubmit = '<div class="form-group text-right"><input type="submit" id="addservicesbutton" value="Add Services" class="btn btn-success" /></div>';
				}else{
					$output .='<tr><td colspan="3" class="text-center"><h3>Nothing To Display</h3></td></tr>';
				}
				foreach($services as $service){
					if($this->checkServices($service->id,$customer_id,$cust_session)){
						$output .= '<tr id="servicetr'.$service->id.'" data-id="'.$service->id.'" onclick="getservice(this)">';
						$output .= '<input type="hidden" name="services[]" value="" id="services'.$service->id.'">';
						$output .= '<td id="imgtd'.$service->id.'" class="text-center"><img width="40px" src="'.\yii\helpers\Url::to('@frontendimage'.'/services/'.$service->services_icon ).'"></td>';
					
					}else{
						$output .= '<tr class="bg-success">';
						$output .= '<td class="text-center"><i class="fa fa-check pull-left text-success" style="margin-top: 10px;"></i><img width="40px" src="'.\yii\helpers\Url::to('@frontendimage'.'/services/'.$service->services_icon ).'"></td>';
					
					}
					
					//$output .= '<td></td>';
					$output .= '<td class="text-center">'.$service->name.'</td>';
					$output .= '<td class="text-right">&#8377; '.$service->price.'</td>';
					$output .= '</tr>';
				}				
			}else{
				$output .= '<tr><td class="text-center">Nothing To Display</td></tr>';
			}
		$output .= '</tbody></table>'.$endformsubmit;
		
		return ['output'=>$output, 'selected'=>'123'];
	}
	
	protected function checkServices($serviceid,$customerid,$custsession)
	{
		if(CustomersServices::find()->where(['cust_id'=>$customerid,'service_id'=>$serviceid,'session_no'=>$custsession,'services_date'=>date('y-m-d')])->exists()){
			return false;
		}else{
			return true;
		}
	}
	
	
	
	public function actionFetchCustomerDetails(){
		Yii::$app->response->format = Response::FORMAT_JSON;
		$request = Yii::$app->request;
		$number = $request->post('number');
		return ['number'=>$number ];
	}
	
	public function actionServicesMeta($id) {		
		$request = Yii::$app->request;
        $services = Services::findOne($id);      
		
		if($request->isAjax){
		/*
		*   Process for ajax request
		*/
			Yii::$app->response->format = Response::FORMAT_JSON;
			 return ['price'=>$services->price];     
		}
	}

	public function actionCustomerServices($id,$cust_session){
		$request = Yii::$app->request;
		$customer = Customers::findOne($id);
		$customerservices = CustomersServices::find()->where(['cust_id'=>$id,'session_no'=>$cust_session,'services_date'=>date('y-m-d')])->all();

		if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    
                    'content'=>$this->renderAjax('customerservices', [
                         'customerservices'=>$customerservices,
                         'customer'=>$customer,
                         'cust_session'=>$cust_session,
                    ]),
                   'footer'=> \yii\helpers\Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                \yii\helpers\Html::a('Add Services', ['/station/add-services-to-customer','id'=>$id,'cust_session'=>$cust_session], ['class' => 'btn btn-success','role'=>'modal-remote']) ,
					
					'size'=>'large',
                ];         
            }else{           
                return [
                    //'title'=> "Add Services To CustomerName",
					
                    'content'=>$this->renderAjax('customerservices', [
                        'customerservices'=>$customerservices,
                        'customer'=>$customer,
						'cust_session'=>$cust_session,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::a('Add Services', ['/station/add-services-to-customer','id'=>$id,'cust_session'=>$cust_session], ['class' => 'btn btn-success','role'=>'modal-remote']) ,
					'size'=>'large',
                ];         
            }
        }
	}
	
	public function actionGenerateBill($id,$session_no){
		
		$request = Yii::$app->request;
		$customer = Customers::findOne($id);
			
			if($request->isAjax){
				Yii::$app->response->format = Response::FORMAT_JSON;
				if($request->isGet){
					if(Orders::find()->where(['cust_id'=>$id])->andWhere('find_in_set(:key, session_nos)', [':key' => $session_no])->exists()){
						
						$orders = Orders::find()->where(['cust_id'=>$id])->andWhere('find_in_set(:key, session_nos)', [':key' => $session_no])->one();
						
						$orderdetails = OrdersDetails::find()->where(['orders_id'=>$orders->id])->all();
						
						return [
							'title'=> "Bill Already Generated",
							'content'=>$this->renderAjax('order-to-pay',[
								'orderdetails'=>$orderdetails,
								'orders'=>$orders,
							]),
							'size'=>'large',
							'footer'=>Html::button('Close',['class'=>'btn btn-default btn-lg pull-left','data-dismiss'=>"modal"]).
									Html::a('Pay',['/station/order-payment','orderid'=>$orders->id],['class'=>'btn btn-success btn-lg','role'=>'modal-remote']) ,
						];
					}else{
						if(CustomersServices::find()->where(['cust_id'=>$id,'services_date'=>date('y-m-d')])->exists()){
    						$servicesSessions = CustomersServices::find()->select('session_no')->where(['cust_id'=>$id,'services_date'=>date('y-m-d')])->distinct()->asArray()->all();
    						for($i=0;$i<count($servicesSessions);$i++){
    							$cust_sessionsdata[$servicesSessions[$i]['session_no']] = CustomersServices::find()->where(['cust_id'=>$id,'services_date'=>date('y-m-d'),'session_no'=>$servicesSessions[$i]['session_no']])->all();
    						}
    						return [
    							'title'=> "Generate Bill",
    							'content'=>$this->renderAjax('bill', [
    								'customer'=>$customer,
    								'cust_sessionsdata'=>$cust_sessionsdata,
    							]),
    							'size'=>'large',
    							'footer'=>Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
    									Html::button('Pay',['class'=>'btn btn-primary','type'=>"submit"]) ,
    						
    						]; 
						}else{
							return [
								'title'=> 'No Services Are Attached',
								'content'=>'No Services Are Attached . Please Attach services ',
								'size'=>'large',
								'footer'=> \yii\helpers\Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]),
							];
						}
					}
				}
				if($request->isPost){
					$servicesdata = $_POST;
					unset($servicesdata['_csrf-backend']);
					
					$sessionsServices = '';
					$orderTotal = 0;
					foreach($servicesdata as $key=>$services){
						$sessionsServices .= $key.',';
						foreach($services as $servicekey=>$service){
							$orderTotal = $orderTotal + $service;
						}
					}
					//echo $sessionsServices . '<br>';
					//echo $orderTotal;
					
					$orders = new Orders();
					$orders->total_amount = $orderTotal;
					$orders->order_date = date('y-m-d');
					$orders->cust_id = $id;
					$orders->status = 'isdue';
					$orders->session_nos = $sessionsServices;
					$orders->due_amount = $orderTotal;
					$orders->save();
					foreach($servicesdata as $key=>$services){
						foreach($services as $servicekey=>$service){
							$orderdetails = new OrdersDetails();
							$orderdetails->services_price = $service;
							$orderdetails->services_id = $servicekey;
							$orderdetails->orders_id = $orders->id;
							$orderdetails->session_no = $key;
							if(!$orderdetails->save()){
								return [
								'title'=> 'Pay is',
								'content'=>print_r($orderdetails->errors),
								//'size'=>'large',
								//'footer'=>Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]),
								];
							}
						}
					}
				
					
						return [
						'title'=> 'Pay is',
						'content'=>'',
						//'size'=>'large',
						//'footer'=>Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]),
						];
				}
			}else{
				
				
			}
			
		
		
	}

	public function actionOrderPayment($orderid = null){
		$request = Yii::$app->request;
		
		$orders = Orders::findOne($orderid);
		$custid = $orders->cust_id;
		$ordersPayments = OrdersPayments::find()->where(['orders_id'=>$orderid])->all();
		$paymentReceiptForm = new PaymentReceiptForm();
		$customerBonusesComp = new CustomerBonusesComp();
		$bonuses = $customerBonusesComp->checkBonuses($custid);
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
					]),
					'size'=>'large',
					
				];
			}else if($paymentReceiptForm->load($request->post())){
				return $this->actionPaymentsuccess($paymentReceiptForm->orderid) ; 
				
			}else{
				echo "1bcbc";
			}
		}
	}
	
	public function actionPaymentsuccess($orderid){
		$request = Yii::$app->request;
		if($request->isAjax){
			Yii::$app->response->format = Response::FORMAT_JSON;
			return [
                    'title'=> "Categories #",
                    'content'=>$this->renderAjax('payment-success', [
                        
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Edit',['update','id'=>1],['class'=>'btn btn-primary','role'=>'modal-remote'])
                ];
		}
		
	}

}
