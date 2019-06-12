<?php

namespace backend\controllers;

use Yii;
use backend\models\Customers;
use backend\models\CustomersSearch;
use backend\models\CustomersImportantDates;

use backend\models\Orders;
use backend\models\OrdersDetails;
use backend\models\CustomersServices;
use backend\models\Services;
use backend\models\SettingsOptions;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;

use backend\models\SignupForm;
use common\models\User;
use yii\web\UploadedFile;
use yii\helpers\BaseFileHelper;

use common\components\CustomerBonusesComp;
use common\components\Sms;

/**
 * CustomersController implements the CRUD actions for Customers model.
 */
class CustomersController extends Controller
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
     * Lists all Customers models.
     * @return mixed
     */
    public function actionIndex()
    {    
        $searchModel = new CustomersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
	public function actionCustomerslist($q = null, $id = null) {
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		$out = ['results' => ['id' => '', 'text' => '']];
		if (!is_null($q)) {
			$query = new yii\db\Query;
			$query->select(['id,name,contact, concat(name," [",concat("C",id)," ",contact,"]") as text'])
				->from('customers')
				->where(['like', 'name', $q])
				->orWhere(['like','contact',$q])
				->orWhere(['like','concat("C",id)',$q])
				->limit(20);
			$command = $query->createCommand();
			$data = $command->queryAll();
			$out['results'] = array_values($data);
		}
		elseif ($id > 0) {
			$out['results'] = ['id' => $id, 'text' => Customers::find($id)->name];
		}
		return $out;
	}

    /**
     * Displays a single Customers model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {   
        $request = Yii::$app->request;
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                    'title'=> "Customers #".$id,
                    'content'=>$this->renderAjax('view', [
                        'model' => $this->findModel($id),
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Edit',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                ];    
        }else{
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }
    }
	
	public function actionCustomerStats($id)
    {   
	
        $request = Yii::$app->request;
		$model = $this->findModel($id);
		$customerbonus = new CustomerBonusesComp();
		$customerbonus = $customerbonus->checkBonuses($id);
		
		/*if(Customers::find()->where(['introducer_customer_id'=>$id])->exists()){
			$referrals = Customers::find()->where(['introducer_customer_id'=>$id])->all();
			}else{
			$referrals = [];
		}*/
		// FOR THE REFERRALS STARTS
		$queryreferrals = new \yii\db\Query;    
			$queryreferrals->select('customers.id,customers.name,SUM(bonus_amount) as bonus')
				->from('customers')
				->leftJoin('customers_bonuses','customers.id=customers_bonuses.cust_id')
				->where(['introducer_customer_id'=>$id])
				->orderBy('customers.id')
				->groupBy('customers.id');
			$command = $queryreferrals->createCommand();
			$referrals = $command->queryAll();
		
		// FOR THE REFERRALS ENDS
		
		// IMPORTANT DATES STARTS
		
		$connection = Yii::$app->getDb();
		$command = $connection->createCommand("
			SELECT * FROM `customers_important_dates` WHERE (`cust_id`=$id) AND DATE_FORMAT(imp_date, '%m-%d') >= DATE_FORMAT(NOW(), '%m-%d') ORDER BY imp_date LIMIT 0, 1");

		$importantdates = $command->queryAll();
		 
		// IMPORTANT DATES ENDS
		
		if(Orders::find()->where(['cust_id'=>$id])->exists()){
			$orders = Orders::find()->where(['cust_id'=>$id])->orderBy(['id'=>SORT_DESC])->limit(5)->all();
		}else{
			$orders = [];
		}
		// For The Pie Chart Starts
		/*if(CustomersServices::find()->select(['service_id'])->where(['cust_id'=>$id])->exists()){
			$services = CustomersServices::find()->select(['service_id'])->where(['cust_id'=>$id,'billing_status'=>'billed'])->groupBy('service_id')->all();
		
			foreach($services as $service){
				 $servicename = $service->service->name;
				 $servicecount = count(CustomersServices::find()->where(['cust_id'=>$id,'billing_status'=>'billed','service_id'=>$service->service_id])->all());
				
				$servicesdata[] = [$servicename,$servicecount];
				
				$categorywise[$service->service->category->category_name][] = $servicecount;
				
			}
			foreach($categorywise as $key=>$category){
				$categorywisesum[] = [$key.'-'.array_sum($category),array_sum($category)];
			}
		}else{
			$servicesdata = [];
			$categorywisesum = [];
		}*/
		
		if(OrdersDetails::find()->select(['services_id'])->joinWith('orders')->where(['cust_id'=>$id])->exists()){
			$services = OrdersDetails::find()->select(['services_id'])->joinWith('orders')->where(['cust_id'=>$id])->groupBy('services_id')->all();
			foreach($services as $service){
				 $servicename = $service->services->name;
			$servicecount = count(OrdersDetails::find()->joinWith('orders')->where(['cust_id'=>$id,'services_id'=>$service->services_id])->all());
			$servicetotalamount = OrdersDetails::find()->joinWith('orders')->where(['cust_id'=>$id,'services_id'=>$service->services_id])->sum('services_price');
				$servicesdata[] = [$servicename.'-'.(int)$servicetotalamount,$servicecount];
				
				$categorywise[$service->services->category->category_name]['count'][] = $servicecount;
				$categorywise[$service->services->category->category_name]['total'][] = $servicetotalamount;
				
			}
			foreach($categorywise as $key=>$category){
				
				$categorywisesum[] = [$key.'-'.(int)array_sum($category['total']),array_sum($category['count'])];
			}
		}else{
			$servicesdata = [];
			$categorywisesum = [];
		}
		// For The Pie Chart Ends
		//echo "<pre>";
		//print_r($categorywisesum);
		//exit;
		
		$totalOrderValue = Orders::find()->where(['cust_id'=>$id,'status'=>'completed'])->sum('total_amount');
		$totalPendingValue = Orders::find()->where(['cust_id'=>$id,'status'=>'isdue'])->sum('due_amount');
		
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                    'title'=> "",
                    'content'=>$this->renderAjax('customerdetails', [
                        'model' => $model,
                        'customerbonusavailable' => $customerbonus['available'],
						'referrals'=>$referrals,
						'importantdates'=>$importantdates,
						'orders'=>$orders,
						//'servicesdata1'=>$vc,
						'servicesdata'=>$servicesdata, //for pie chart
						'categorywisesum'=>$categorywisesum, //for pie chart
						'totalOrderValue'=>($totalOrderValue)? $totalOrderValue : 0, //for pie chart
						'totalPendingValue'=>($totalPendingValue) ? $totalPendingValue : 0, //for pie chart
                    ]),
                    'footer'=> Html::button('Done',['class'=>'btn btn-success pull-right','data-dismiss'=>"modal"]),
								
								
                    'size'=>'large',       
                ];    
        }else{
            return $this->render('customerdetails', [
						'model' => $model,
                        'customerbonusavailable' => $customerbonus['available'],
						'referrals'=>$referrals,
						'importantdates'=>$importantdates,
						'orders'=>$orders,
						//'servicesdata1'=>$vc,
						'servicesdata'=>$servicesdata, //for pie chart
						'categorywisesum'=>$categorywisesum, //for pie chart
						'totalOrderValue'=>($totalOrderValue)? $totalOrderValue : 0, //for pie chart
						'totalPendingValue'=>($totalPendingValue) ? $totalPendingValue : 0, //for pie chart
            ]);
        }
    }

    /**
     * Creates a new Customers model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new Customers(); 
		//$userform = new SignupForm();		
		$sms = new Sms();
        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Create new Customers",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
						//'userform'=>$userform,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            }else if($model->load($request->post())){
				if($model->user_id =='yes'){
					$user = new User();
					$user->scenario = 'customer';
					$user->username = $model->contact;
					$user->contact_number = $model->contact;
					$user->usertype = 'user';
					$user->setPassword($model->contact);
					$user->generateAuthKey();
					
					if(!$user->save()){
						return [
						'forceReload'=>'#crud-datatable-pjax',
						'title'=> "Error in User model",
						'content'=>Html::errorSummary($user),
						'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
								Html::a('Create More',['create'],['class'=>'btn btn-primary','role'=>'modal-remote'])
						];
					}
					$model->user_id = $user->id;
				}
				
				if(!$model->save()){
					return [
						'forceReload'=>'#crud-datatable-pjax',
						'title'=> "Error in User model",
						'content'=>Html::errorSummary($model),
						'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
								Html::a('Create More',['create'],['class'=>'btn btn-primary','role'=>'modal-remote'])
						];
				}else{
					
					if(UploadedFile::getInstance($model,'customer_pic')){
						$path = Yii::getAlias('@frontend').'/web/uploads/customers/';
						BaseFileHelper::createDirectory($path,0777,false);
						$model->customer_pic = UploadedFile::getInstance($model,'customer_pic');
						$model->customer_pic->saveAs(Yii::getAlias($path.$model->id.'-'.time().'.'.$model->customer_pic->extension));
						$model->customer_pic = $model->id.'-'.time().'.'.$model->customer_pic->extension;
						$model->save();
					}
					if($request->post('sendsms')=='1'){
						if(SettingsOptions::find()->where(['settings_attribute_name'=>'welcome-offer-msg'])->exists())
							{
								$newOfferSms = SettingsOptions::find()->where(['settings_attribute_name'=>'welcome-offer-msg'])->one()->settings_attribute_value;
							}else{
								$newOfferSms = '';
							}
							$text =str_replace('_CRMSPA_','%0D%0A',rawurlencode('Thanks for coming at _CRMSPA_'.ucwords(yii::$app->name).'_CRMSPA_Your Customer ID : C'.$model->id.'_CRMSPA_Mention it for all future visits to_CRMSPA_get Reward Point, Refferal Bonus, Cashback & Offers._CRMSPA_'.$newOfferSms));
							$sms->sendSms($model->contact,$text);
					}
					return [
					'forceClose'=>true,
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "Create new Customers",
                    'content'=>"Customer Added Successfully",
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Create More',['create'],['class'=>'btn btn-primary','role'=>'modal-remote'])
        
					];
				}
                         
            }else{           
                return [
                    'title'=> "Create new Customers",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
						'userform'=>$userform,
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
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
       
    }

    /**
     * Updates an existing Customers model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);       
		$customerpic = $model->customer_pic;
        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Update <strong class='text-primary'>".ucwords($model->name)."</strong>",
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
                ];         
            }else if($model->load($request->post())){
				if($model->user_id =='yes'){
					$user = new User();
					$user->scenario = 'customer';
					$user->username = $model->contact;
					$user->contact_number = $model->contact;
					$user->usertype = 'user';
					$user->setPassword($model->contact);
					$user->generateAuthKey();
					
					if(!$user->save()){
						return [
						'forceReload'=>'#crud-datatable-pjax',
						'title'=> "Error in User model",
						'content'=>Html::errorSummary($user),
						'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
								Html::a('Create More',['create'],['class'=>'btn btn-primary','role'=>'modal-remote'])
						];
					}
					$model->user_id = $user->id;
				}
				
				if(!$model->save()){
					return [
						'forceReload'=>'#crud-datatable-pjax',
						'title'=> "Error in User model",
						'content'=>Html::errorSummary($model),
						'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
								Html::a('Create More',['create'],['class'=>'btn btn-primary','role'=>'modal-remote'])
						];
				}
				if(UploadedFile::getInstance($model,'customer_pic')){
						$path = Yii::getAlias('@frontend').'/web/uploads/customers/';
						BaseFileHelper::createDirectory($path,0777,false);
						$model->customer_pic = UploadedFile::getInstance($model,'customer_pic');
						$model->customer_pic->saveAs(Yii::getAlias($path.$model->id.'-'.time().'.'.$model->customer_pic->extension));
						$model->customer_pic = $model->id.'-'.time().'.'.$model->customer_pic->extension;
						$model->save();
				}else{
					$model->customer_pic = $customerpic;
					$model->save();
				}
                return ['forceClose'=>true,'forceReload'=>'#crud-datatable-pjax'];    
            }else{
                 return [
                    'title'=> "Update <strong class='text-primary'>".ucwords($model->name)."</strong>",
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

    /**
     * Delete an existing Customers model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $this->findModel($id)->delete();

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
     * Delete multiple existing Customers model.
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
            $model->delete();
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
     * Finds the Customers model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Customers the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Customers::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
