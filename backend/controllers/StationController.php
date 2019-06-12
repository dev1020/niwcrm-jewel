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
use backend\models\CustomersDeliveryAddress;

use backend\models\Orders;
use backend\models\OrdersPayments;
use backend\models\OrdersDetails;
use backend\models\OpenSaleForm;


use backend\models\Services;
use backend\models\SeatsAndChairs;
use backend\models\ServicesToCustomerSelectionForm;
use backend\models\PaymentReceiptForm;
use backend\models\SettingsOptions;


use common\components\CustomerBonusesComp;
use common\components\CustomerBonusesGenerateComp;
use common\components\Sms;

use common\components\Ordersmailandpdfwidgets\Ordersmailandpdf;
use common\components\SettingsGetter;


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
	public function actionGetDeliveryAddress($cust_id=NULL,$contact=NULL)
    {
		$request = Yii::$app->request;
		if(is_numeric($contact) && $contact !=''){
			if(Customers::find()->where(['contact'=>$contact])->exists()){
				$customer  = Customers::find()->where(['contact'=>$contact])->one();
				$cust_id = $customer->id;
			}
		}
		if($request->isAjax){
			Yii::$app->response->format = Response::FORMAT_JSON;
			$output = '';
			if(CustomersDeliveryAddress::find()->where(['customer_id'=>$cust_id])->exists()){
				$delivery_address = CustomersDeliveryAddress::find()->where(['customer_id'=>$cust_id])->all();
				//return $delivery_address;
				$output .= '<div class="form-group"><label class="control-label" for="customers-addresses">Existing Addresses</label>';
				foreach($delivery_address as $address){
					$output .='<div class="radio addresses">
						<label>
						  <input type="radio" name="deliveryaddress" value="'.$address->id.'" >
						  '.ucwords($address->delivery_address).'
						</label>
					  </div>' ;
				}
				$output .= '</div>';
			}			
			$output .= '<div class="form-group"><label class="control-label" for="customers-addresses">New Address</label>';
			$output .= '<textarea class="form-control" name="newaddress"></textarea></div>';
			return ['output'=>$output,'name'=>isset($customer->name)? $customer->name :'','id'=>isset($customer->id)? $customer->id :''];
		}
	}
	
	public function actionGetFreeseats()
    {
		$request = Yii::$app->request;
		if($request->isAjax){
			$seats = SeatsAndChairs::find()->asArray()->all();
			Yii::$app->response->format = Response::FORMAT_JSON;
			$output = '';
			if(SeatsAndChairs::find()->exists()){
				$seats = SeatsAndChairs::find()->all();
				//return $delivery_address;
				$output .='<div class="col-lg-12 col-xs-12 text-center">' ;
				foreach($seats as $seat){
					if($seat->status == 'free'){
						$output .='<button type="button" class="btn btn-success btn-lg tableseat" data-id="'.$seat->id.'" style="margin:5px">';
						$output .=$seat->seatlabel;
						$output .='</button>';
					}else{
						$output .='<button type="button" class="btn btn-danger btn-lg split"  data-id="'.$seat->id.'" style="margin:5px">';
						$output .=$seat->seatlabel;
						$output .='</button>';
					}
					
				}
				$output .= '</div>';
			}			
			return $output;
		}
	}
	public function actionAddCustomer()
    {
        $request = Yii::$app->request;
        $model = new OpenSaleForm(); 
		
		
		$settings_getter = new SettingsGetter();
		//if($settings_getter->get_attribute_value('show-customer-mobile-number') == 'yes'){
			$data= Customers::find()->select(['contact as value', 'concat(name," ",contact) as  label','name','id','gender','contact'])->asArray()->all();
		/*}else{
			$data= Customers::find()->select(['concat("C",id) as value', 'concat(name," [ ",concat("C",id)," ]") as  label','name','id','gender','contact'])->asArray()->all();
		} */  
		 
        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Customer Details",
                    'content'=>$this->renderAjax('addcustomer', [
                        'model' => $model,
                        'data' => $data,
                    ]),
                    //'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                      //          Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            }else if($model->load($request->post())){
				
				/*if(CustomersLog::find()->orderBy(['id'=>SORT_DESC])->exists()){
					$lastSessionNo = CustomersLog::find()->MAX('session_no') + 1;
				}else{
					$lastSessionNo = 100000;
				}*/
				$lastSessionNo = time();
				
				$customersLog = new CustomersLog();
				$customersLog->scenario = 'opensale';
				
				$customersLog->log_date = date('y-m-d');
				$customersLog->start_session_time = date('h:i:s');
				$customersLog->status = 'open';
				$customersLog->session_no = $lastSessionNo;
				$customersLog->type = $model->sale_type;
				$customersLog->seat_id = $model->table_id;
				$customersLog->created_by = yii::$app->user->identity->id;
				if(!Yii::$app->user->can('Admin')){
					$customersLog->assigned_executive_id  = yii::$app->user->identity->id;
				}
				if($model->other !=''){
					if(Customers::find()->where(['contact'=>$model->other])->exists()){
						$exist_customer = Customers::find()->where(['contact'=>$model->other])->one();
						$customersLog->cust_id = $exist_customer->id;
					}else{
						$new_customer = new Customers();
						$new_customer->contact = $model->other;
						$new_customer->name = $model->customer_name;
						if(!$new_customer->save()){
							return ['title'=> "Customer Details",
							'content'=>$this->renderAjax('addcustomer', [
								'model' => $model,
								'data' => $data,
							]),
							'error'=>Html::errorSummary($new_customer),
							'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
										Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
							];
						}
						
						$customersLog->cust_id = $new_customer->id;
					}
				}
				
				if($model->sale_type == 'table'){
					
					if(!$customersLog->save()){
					
						return ['title'=> "Customer Details",
						'content'=>$this->renderAjax('addcustomer', [
							'model' => $model,
							'data' => $data,
						]),
						'error'=>Html::errorSummary($model),
						'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
									Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
						];
					}else{
						$seatsandchairs = SeatsAndChairs::findOne($customersLog->seat_id);
						$seatsandchairs->status = 'block';
						$seatsandchairs->save();
					}
				}elseif($model->sale_type == 'delivery'){
					if(isset($customersLog->cust_id)){
						if($request->post('newaddress')!=''){
						$customerdeliveryaddress = new CustomersDeliveryAddress();
						$customerdeliveryaddress->customer_id = $customersLog->cust_id;
						$customerdeliveryaddress->delivery_address = $request->post('newaddress');
						if(!$customerdeliveryaddress->save()){
							return ['title'=> "Customer Details",
							'content'=>$this->renderAjax('addcustomer', [
								'model' => $model,
								'data' => $data,
							]),
							'error'=>Html::errorSummary($customerdeliveryaddress),
							'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
										Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
							];
						}
						
						$customersLog->address_id = $customerdeliveryaddress->id;
						}else if($request->post('deliveryaddress')!=''){
							$customersLog->address_id = $request->post('deliveryaddress');
						}
					}else{
						$model->addError('other','Customer Detail is needed for Delivery');
						return ['title'=> "Customer Details",
						'content'=>$this->renderAjax('addcustomer', [
							'model' => $model,
							'data' => $data,
						]),
						'error'=>Html::errorSummary($model),
						'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
									Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
						];
					}
					if(!$customersLog->save()){
						return $customersLog->errors;
					}
				}
				return [
							'id'=>isset($customersLog->cust_id)?$customersLog->cust_id:'',
							'name'=>isset($customersLog->cust->name)?$customersLog->cust->name:'',
							'contact'=>isset($customersLog->cust->contact)?$customersLog->cust->contact:'',
							'seatid'=>isset($customersLog->seat_id)?$customersLog->seat_id:'',
							'seatlabel'=>isset($customersLog->seat->seatlabel)?$customersLog->seat->seatlabel:'',
							'entrystatus'=>true,
							'session_no'=>$lastSessionNo,
							'type'=>$customersLog->type,
						];
			}else{
				return [
                    'title'=> "Customer Details",
                    'content'=>$this->renderAjax('addcustomer', [
                        'model' => $model,
                        'data' => $data,
                    ]),
                    //'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                      //          Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
        
                ];  
			}
        }
    }
	protected function checkcustomerinsession($custid=NULL,$session_no,$seat_id=NULL)
	{
		if(CustomersLog::find()->where(['session_no'=>$session_no,])->andFilterWhere(['cust_id'=>$custid,'seat_id'=>$seat_id,])->exists())
		{
			return true;
		}else{
			return false;
		}
	} 
	public function actionAddServicesToCustomer($id=NULL,$cust_session,$seat_id=NULL){
		
		$request = Yii::$app->request;
		//$customer = CustomersLog::find()->where(['cust_id'=>$id,'session_no'=>$cust_session])->one();
		$customer = Customers::findOne($id);
		
		if(CustomersServices::find()->where(['cust_id'=>$id,'seat_id'=>$seat_id,'session_no'=>$cust_session,'services_date'=>date('y-m-d')])->exists()){
			$customerSubtotal =  CustomersServices::find()->where(['cust_id'=>$id,'seat_id'=>$seat_id,'services_date'=>date('y-m-d')])->sum('services_price');
		}else{
			$customerSubtotal = 0;
		}
		if($this->checkcustomerinsession($id,$cust_session,$seat_id) && $this->checkIfBillGenerated($id,$cust_session,$seat_id)){
		//if($this->checkcustomerinsession($id,$cust_session)){
		
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
							 'seat_id'=>$seat_id,
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
							'seat_id'=>$seat_id,
							'cust_session'=>$cust_session,
							'customerSubtotal'=>$customerSubtotal,
						]),
						'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
									Html::a('<< To Services',['station/customer-services','id'=>$id,'cust_session'=>$cust_session],['class'=>'btn btn-primary','role'=>"modal-remote"])
			
					];         
				}
			}else{
				return $this->render('addservicestocustomer', [
							'model'=>$model,
							'customer'=>$customer,
							'seat_id'=>$seat_id,
							'cust_session'=>$cust_session,
							'customerSubtotal'=>$customerSubtotal,
						]); 
			}
		}else{
			throw new \yii\web\NotFoundHttpException();
		}
	}
	
	
	
	public function actionSubcat() {
    $out = [];
		if (isset($_POST['depdrop_parents'])) {
			$parents = $_POST['depdrop_parents'];
			if ($parents != null) {
				$cat_id = $parents[0];
				$categories = Categories::find()->where(['category_root'=>$cat_id])->orderBy(['category_name'=>SORT_ASC])->all(); 
				// the getSubCatList function will query the database based on the
				// cat_id and return an array like below:
				// [
				//    ['id'=>'<sub-cat-id-1>', 'name'=>'<sub-cat-name1>'],
				//    ['id'=>'<sub-cat_id_2>', 'name'=>'<sub-cat-name2>']
				// ]
				
				foreach($categories as $category){
					$out[] = ['id' => $category['category_id'], 'name' => $category['category_name'],];
				}
				echo \yii\helpers\Json::encode(['output'=>$out, 'selected'=>'']);
				return;
			}
		}
		echo Json::encode(['output'=>'', 'selected'=>'']);
	}
	
	public function actionAddServices() {
		
		$services = $_POST['services'];
		$quantity = $_POST['quantity'];
		
		$customer_id = $_POST['ServicesToCustomerSelectionForm']['customer_id'];
		$seat_id = $_POST['ServicesToCustomerSelectionForm']['seat_id'];
		$cust_session = $_POST['ServicesToCustomerSelectionForm']['cust_session'];
		
		if($this->checkcustomerinsession($customer_id,$cust_session,$seat_id) && $this->checkIfBillGenerated($customer_id,$cust_session,$seat_id)){
			Yii::$app->response->format = Response::FORMAT_JSON;
			if(count($services)>0){
			 //return $services;
			// return $quantity;
				for($i=0;$i<count($services);$i++){
					if(CustomersServices::find()->where(['service_id'=>$services[$i],'session_no'=>$cust_session,])->andFilterWhere(['cust_id'=>$customer_id,'seat_id'=>$seat_id])->exists()){
						$model = CustomersServices::find()->where(['service_id'=>$services[$i],'session_no'=>$cust_session,])->andFilterWhere(['cust_id'=>$customer_id,'seat_id'=>$seat_id])->one();
						if($quantity[$i]>0){
							$model->services_quantity=$quantity[$i];
							$model->save();
						}else{
							$model->delete();
						}
						$servicessaved[] = [];
					}else{
						if($quantity[$i]!=0){
						$model = new CustomersServices();
						$model->cust_id = $customer_id;
						$model->service_id = $services[$i];
						$model->service_status = 'queue';
						$model->services_date = date('y-m-d');
						$model->services_quantity = $quantity[$i];
						$model->services_price = Services::findOne($services[$i])->price;
						$model->session_no = $cust_session;
						$model->seat_id = $seat_id;
						$model->created_by = yii::$app->user->identity->id;
							if($model->save()){
								
								$servicessaved[] = ['id'=>$services[$i]]; 
							}
						}
					}
								
				}
				return $servicessaved;
			}else{
				return ['status'=>false,'msg'=>'Nothing Added'];
			}
		}else{
			Yii::$app->response->format = Response::FORMAT_JSON;
			return ['status'=>false,'msg'=>'Bill already Generated Or Not in Session'];
		}
		
		
	}
	protected function checkIfBillGenerated($custid=null,$session_no,$seat_id=null)
	{
		if(Orders::find()->andWhere('find_in_set(:session_no, `session_nos`)', [':session_no' => $session_no])->andFilterWhere(['cust_id'=>$custid,'seat_id'=>$seat_id])->exists())
		{
			return false;
		}else{
			return true;
		}
	} 
	public function actionDeleteServices($id,$cust_id,$cust_session,$seat_id)
    {
        $request = Yii::$app->request;
		if($this->checkIfBillGenerated($cust_id,$cust_session,$seat_id)){
			CustomersServices::findOne($id)->delete();
			if($request->isAjax){
				/*
				*   Process for ajax request
				*/
				Yii::$app->response->format = Response::FORMAT_JSON;
				return ['forceClose'=>true,'forceReload'=>'#customers1234'];
			}
		}else{
			if($request->isAjax){
				/*
				*   Process for ajax request
				*/
				Yii::$app->response->format = Response::FORMAT_JSON;
				return [
					'title'=>'Warning',
					'content'=>'<h4 class="text-danger text-center">Bill Already generated. You cannot delete services</h4>',
					];
			}
		}
        
    }
	
	public function actionServices()
	{
		Yii::$app->response->format = Response::FORMAT_JSON;
		$cat[] = $_POST['catid'];
		$cat[] = $_POST['subcatid'];
		$customer_id = $_POST['custid'];
		$seat_id = $_POST['seat_id'];
		$cust_session = $_POST['cust_session'];
		$output = '<table class="table table-bordered servicestable"><thead><tr class="bg-primary"><th class="text-center">Item</th><th class="text-center" width="12%">Qnty</th><th class="text-center" width="100px">Action</th></tr></thead><tbody>';
		$endformsubmit ='';	
			if (isset($_POST)) {
				$services = Services::find()->where(['category_id'=>$cat])->orderBy(['name'=>SORT_ASC])->all();
				if(count($services)>0){
					$endformsubmit = '<div class="form-group">'.\yii\helpers\Html::a('<i class="fa fa-file-text"></i> Bill', ['station/generate-bill','id'=>$customer_id,'session_no'=>$cust_session,'seat_id'=>$seat_id], ['class' => 'btn btn-lg bg-purple pull-left',]).'<span class="error pull-left alert-danger"></span><button type="submit" id="addservicesbutton" class="btn btn-success btn-lg pull-right"><i class="fa fa-check"></i> OK </button></div>';
					//$endformsubmit = '';
				}else{
					$output .='<tr><td colspan="4" class="text-center"><h3>Nothing To Display</h3></td></tr>';
				}
				foreach($services as $service){
				
					if($this->checkServices($service->id,$customer_id,$cust_session,$seat_id)){
						$output .= '<tr id="servicetr'.$service->id.'" data-id="'.$service->id.'" >';
						$output .= '<input type="hidden" name="services[]" value="'.$service->id.'">';
						
						//$output .= '<td id="imgtd'.$service->id.'" class="text-center"><img width="40px" src="'.$imageicon.'"></td>';
						$output .= '<td class="text-center"><span class="pull-left" id="nametd'.$service->id.'"></span><strong>'.$service->name.'</strong><br><i class="fa fa-inr"></i> '.$service->price.'</td>';
						
						$output .= '<td class="text-right" ><input type="tel" name="quantity[]" onclick="this.select()" size="4" maxlength="4" class="form-control numeric no-padding" value="0" id="serquantity'.$service->id.'"></td>';
					}else{
						
						$output .= '<tr class="bg-success" data-service="done">';
						$output .= '<input type="hidden" name="services[]" value="'.$service->id.'">';
						
						$output .= '<td class="text-center"><span class="pull-left"><i class="fa fa-check  text-success" style="margin-top: 10px;"></i></span><strong>'.$service->name.'</strong><br><i class="fa fa-inr"></i> '.$service->price.'</td>';
						
						$output .= '<td class="text-right" ><input type="tel" name="quantity[]" size="4" onclick="this.select()" maxlength="4" class="form-control numeric no-padding" value="'.$this->getServicesQuantity($service->id,$customer_id,$cust_session,$seat_id).'" id="serquantity'.$service->id.'"></td>';
					
					}
					
					//$output .= '<td></td>';
					$output .= '<td class="text-right"><span class="btn btn-danger minus" style="margin:2px" data-id="'.$service->id.'"><i class="fa fa-minus"></i></span><span class="btn btn-success plus" style="margin:2px" data-id="'.$service->id.'"><i class="fa fa-plus"></i></span></td>';
					$output .= '</tr>';
				}				
			}else{
				$output .= '<tr><td class="text-center">Nothing To Display</td></tr>';
			}
		$output .= '</tbody></table>'.$endformsubmit;
		
		return ['output'=>$output, 'selected'=>'123'];
	}
	
	protected function checkServices($serviceid,$customerid=NULL,$custsession,$seat_id=NULL)
	{
		if(CustomersServices::find()->where(['service_id'=>$serviceid,'session_no'=>$custsession,])->andFilterWhere(['cust_id'=>$customerid,'seat_id'=>$seat_id])->exists()){
			return false;
		}else{
			return true;
		}
	}
	
	protected function getServicesQuantity($serviceid,$customerid=NULL,$custsession,$seat_id=NULL)
	{
		return CustomersServices::find()->where(['service_id'=>$serviceid,'session_no'=>$custsession,])->andFilterWhere(['cust_id'=>$customerid,'seat_id'=>$seat_id])->one()->services_quantity;
			
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

	public function actionCustomerServices($id=NULL,$cust_session,$seat_id=NULL){
		$request = Yii::$app->request;
		$customer = Customers::findOne($id);
		$customerservices = CustomersServices::find()->where(['session_no'=>$cust_session])->andFilterWhere(['cust_id'=>$id,'seat_id'=>$seat_id])->all();
		$customerlog = CustomersLog::find()->where(['session_no'=>$cust_session,])->andFilterWhere(['cust_id'=>$id,'seat_id'=>$seat_id])->one();
		if($this->checkcustomerinsession($id,$cust_session,$seat_id)){
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
							'seat_id'=>$seat_id,
							'cust_session'=>$cust_session,
							'billgenerated'=>$this->checkIfBillGenerated($id,$cust_session,$seat_id),
							'orders'=>Orders::find()->andWhere('find_in_set(:key, session_nos)', [':key' => $cust_session])->andFilterWhere(['cust_id'=>$id,'seat_id'=>$seat_id])->one(),
							'assigned_executive'=>isset($customerlog->executive->username)? $customerlog->executive->username : '',
						]),
					   'footer'=> \yii\helpers\Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
									\yii\helpers\Html::a('Add Services', ['/station/add-services-to-customer','id'=>$id,'cust_session'=>$cust_session,'seat_id'=>$seat_id], ['class' => 'btn btn-success','role'=>'modal-remote']) ,
						
						'size'=>'large',
					];         
				}else{           
					return [
						//'title'=> "Add Services To CustomerName",
						
						'content'=>$this->renderAjax('customerservices', [
							'customerservices'=>$customerservices,
							'customer'=>$customer,
							'seat_id'=>$seat_id,
							'cust_session'=>$cust_session,
							'billgenerated'=>$this->checkIfBillGenerated($id,$cust_session,$seat_id),
							'orders'=>Orders::find()->andFilterWhere(['cust_id'=>$id,'seat_id'=>$seat_id])->andWhere('find_in_set(:key, session_nos)', [':key' => $cust_session])->one(),
							'assigned_executive'=>isset($customerlog->executive->username)? $customerlog->executive->username : '',
						]),
						'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
									Html::a('Add Services', ['/station/add-services-to-customer','id'=>$id,'cust_session'=>$cust_session,'seat_id'=>$seat_id], ['class' => 'btn btn-success','role'=>'modal-remote']) ,
						'size'=>'large',
					];         
				}
			}else{
				return $this->render('customerservices', [
							'customerservices'=>$customerservices,
							'customer'=>$customer,
							'cust_session'=>$cust_session,
							'seat_id'=>$seat_id,
							'billgenerated'=>$this->checkIfBillGenerated($id,$cust_session,$seat_id),
							'orders'=>Orders::find()->andFilterWhere(['cust_id'=>$id,'seat_id'=>$seat_id])->andWhere('find_in_set(:key, session_nos)', [':key' => $cust_session])->one(),
							'assigned_executive'=>isset($customerlog->executive->username)? $customerlog->executive->username : '',
						]);
			}
		}else{
			throw new \yii\web\NotFoundHttpException();
		}
	}
	
	public function actionAssignExecutiveToCustomer($id=NULL,$cust_session,$seat_id=NULL){
		$request = Yii::$app->request;
		if($request->isAjax){
			Yii::$app->response->format = Response::FORMAT_JSON;
			$model = CustomersLog::find()->where(['session_no'=>$cust_session])->andFilterWhere(['cust_id'=>$id,'seat_id'=>$seat_id])->one();
			$model->scenario = 'opensale';
			if($request->isGet){
				return [
					'title'=>'OrderBoy',
					'content'=>$this->renderAjax('assignexecutive',[
									'model'=>$model,
								]),
					'size'=>'normal',
					'footer'=>Html::button('Close',['class'=>'btn btn-default btn-lg pull-left','data-dismiss'=>"modal"]).
							 Html::button('Assign',['class'=>'btn btn-primary','type'=>"submit"]) ,
							
				];
			}else if($model->load($request->post()) ){
				
				$model->scenario = 'opensale';
				$model->save();
				return ['forceClose'=>true,'forceReload'=>'#customers1234'];
				/*return [
					'title'=>'OrderBoy Assigned Successfully',
					'content'=>'<h4><strong class="text-success">'.ucwords($model->executive->username).' </strong> is Assigned To this Job.</h4>',
					'size'=>'normal',
					'footer'=>Html::button('Done',['class'=>'btn btn-success btn-lg pull-right','data-dismiss'=>"modal"]),
							 
							
				];*/
			}else{
				return [
					'title'=>'OrderBoy',
					'content'=>$this->renderAjax('assignexecutive',[
									'model'=>$model,
								]),
					'size'=>'normal',
					'footer'=>Html::button('Close',['class'=>'btn btn-default btn-lg pull-left','data-dismiss'=>"modal"]).
							 Html::button('Assign',['class'=>'btn btn-primary','type'=>"submit"]) ,
							
				];
			}
			
		}
	}
	
	public function actionCloseSession($id=NULL,$cust_session,$seat_id=NULL,$mode){
		$request = Yii::$app->request;
		if($request->isAjax){
			if($this->checkIfBillGenerated($id,$cust_session)){
				$model = CustomersLog::find()->where(['cust_id'=>$id,'session_no'=>$cust_session])->one();
				$model->delete();
				$this->redirect(['station/index']);				
			}else{
				$model = CustomersLog::find()->where(['cust_id'=>$id,'session_no'=>$cust_session])->one();
				$model->status = 'closed';
				$model->save();
				$this->redirect(['station/index']);
			}
		}
	}
	
	public function actionGenerateBill($id=NULL,$session_no,$seat_id=NULL){
		
		$request = Yii::$app->request;
		$customer = Customers::findOne($id);
		if($this->checkcustomerinsession($id,$session_no,$seat_id)){
			$customerLog = CustomersLog::find()->where(['session_no'=>$session_no])->andFilterWhere(['cust_id'=>$id,'seat_id'=>$seat_id])->one();
			$order_boy_id = $customerLog->assigned_executive_id;
			$order_type = $customerLog->type;
			$order_delivery_address = $customerLog->address_id;
			
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
						if(CustomersServices::find()->where(['cust_id'=>$id,'services_date'=>date('y-m-d'),'billing_status'=>'unbilled'])->exists()){
    						$servicesSessions = CustomersServices::find()->select('session_no')->where(['cust_id'=>$id,'services_date'=>date('y-m-d'),'billing_status'=>'unbilled'])->distinct()->asArray()->all();
    						for($i=0;$i<count($servicesSessions);$i++){
    							$cust_sessionsdata[$servicesSessions[$i]['session_no']] = CustomersServices::find()->where(['cust_id'=>$id,'services_date'=>date('y-m-d'),'session_no'=>$servicesSessions[$i]['session_no'],'billing_status'=>'unbilled'])->all();
    						}
    						return [
    							'title'=> "Generate Bill",
    							'content'=>$this->renderAjax('bill', [
    								'customer'=>$customer,
    								'cust_sessionsdata'=>$cust_sessionsdata,
    							]),
    							'size'=>'large',
    							'footer'=>Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
    									Html::button('Generate Bill',['class'=>'btn btn-primary','type'=>"submit"]) ,
    						
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
					$orders->seat_id = $seat_id;
					$orders->created_by = yii::$app->user->identity->id;
					$orders->order_boy_id = $order_boy_id;
					$orders->order_type = $order_type;
					$orders->status = 'isdue';
					$orders->session_nos = $sessionsServices;
					$orders->due_amount = $orderTotal;
					$orders->save();
					foreach($servicesdata as $key=>$services){
						
						foreach($services as $servicekey=>$service){
							
							$customerServices = CustomersServices::find()->where(['session_no'=>$key,'service_id'=>$servicekey])->andFilterWhere(['cust_id'=>$id,'seat_id'=>$seat_id])->one();
							$customerServices->billing_status = 'billed';
							$customerServices->save();
							
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
					$ordersmailandpdf = new Ordersmailandpdf();
					$ordersmailandpdf->sentMailAndPdf($orders->id);
					
					/*$sms = new Sms();
					$text =str_replace('_CRMSPA_','%0D%0A',rawurlencode('Dear '.ucwords($orders->cust->name).',_CRMSPA_Invoice-'.str_pad($orders->id, 10, '0', STR_PAD_LEFT).'is generated._CRMSPA_Amount - Rs. '.$orders->total_amount.' is due. _CRMSPA_For any query contact _CRMSPA_ADITI\'S SALON'));
					$sms->sendSms($orders->cust->contact,$text);*/
					return [
							'title'=> "Bill Generated ",
							'content'=>$this->renderAjax('order-to-pay',[
								'orderdetails'=>$orders->ordersDetails,
								'orders'=>$orders,
							]),
							'size'=>'large',
							'footer'=>Html::button('Close',['class'=>'btn btn-default btn-lg pull-left','data-dismiss'=>"modal"]).
									Html::a('Pay',['/station/order-payment','orderid'=>$orders->id],['class'=>'btn btn-success btn-lg','role'=>'modal-remote']) ,
						];
				}
			}else{
				if($request->isPost){
					$servicesdata = $_POST;
					unset($servicesdata['_csrf-backend']);
					//print_r($_POST);
					
					$sessionsServices = '';
					$orderTotal = 0;
					foreach($servicesdata as $key=>$services){
						$sessionsServices .= $key.',';
						foreach($services as $servicekey=>$service){
							$orderTotal = $orderTotal + $service['price']*$service['quantity'];
							//print_r($service);
						}
					}
					//echo $sessionsServices . '<br>';
					//echo $orderTotal;
					//exit;
					$orders = new Orders();
					$orders->total_amount = $orderTotal;
					$orders->order_date = date('y-m-d');
					
					$orders->cust_id = $id;
					$orders->seat_id = $seat_id;
					$orders->created_by = yii::$app->user->identity->id;
					$orders->order_boy_id = $order_boy_id;
					$orders->order_type = $order_type;
					$orders->status = 'isdue';
					$orders->session_nos = $sessionsServices;
					$orders->due_amount = $orderTotal;
					if(!$orders->save()){
								
								print_r($orders->errors);
								
								exit;
								
							}
					
					foreach($servicesdata as $key=>$services){
						
						foreach($services as $servicekey=>$service){
							
							$customerServices = CustomersServices::find()->where(['session_no'=>$key,'service_id'=>$servicekey])->andFilterWhere(['cust_id'=>$id,'seat_id'=>$seat_id])->one();
							$customerServices->billing_status = 'billed';
							$customerServices->save();
							
							$orderdetails = new OrdersDetails();
							$orderdetails->services_price = $service['price'];
							$orderdetails->services_quantity = $service['quantity'];
							$orderdetails->services_id = $servicekey;
							$orderdetails->orders_id = $orders->id;
							$orderdetails->session_no = $key;
							if(!$orderdetails->save()){
								
								print_r($orderdetails->errors);
								
								exit;
								
							}
						}
					}
					// free The table
					if(isset($orders->seat_id) && $orders->seat_id !=NULL){
						$seatandchairs = SeatsAndChairs::findOne($orders->seat_id);
						$seatandchairs->status = 'free';
						$seatandchairs->save();
					}
					
					/*
					$ordersmailandpdf = new Ordersmailandpdf();
					$ordersmailandpdf->sentMailAndPdf($orders->id);
					*/
					//$sms = new Sms();
					//$text =str_replace('_CRMSPA_','%0D%0A',rawurlencode('Dear '.ucwords($orders->cust->name).',_CRMSPA_Invoice-'.str_pad($orders->id, 10, '0', STR_PAD_LEFT).'is generated._CRMSPA_Amount - Rs. '.$orders->total_amount.' is due. _CRMSPA_For any query contact _CRMSPA_ADITI\'S SALON'));
					//$sms->sendSms($orders->cust->contact,$text);
					return $this->render('order-to-pay',[
								'orderdetails'=>$orders->ordersDetails,
								'orders'=>$orders,
								'billgenerated'=>true,
								'msg'=>'BILL GENERATED',
							]);
				}else{
					if(Orders::find()->andWhere('find_in_set(:key, session_nos)', [':key' => $session_no])->andFilterWhere(['cust_id'=>$id,'seat_id'=>$seat_id])->exists()){
						
						$orders = Orders::find()->andWhere('find_in_set(:key, session_nos)', [':key' => $session_no])->andFilterWhere(['cust_id'=>$id,'seat_id'=>$seat_id])->one();
						
						$orderdetails = OrdersDetails::find()->where(['orders_id'=>$orders->id])->all();
						
						//BILL ALREADY GENERATED
						return $this->render('order-to-pay',[
								'orderdetails'=>$orderdetails,
								'orders'=>$orders,
								'billalreadygenerated'=>true,
								'msg'=>'BILL ALREADY GENERATED',
							]);
					}else{
						if(CustomersServices::find()->where(['billing_status'=>'unbilled'])->andFilterWhere(['cust_id'=>$id,'seat_id'=>$seat_id])->exists()){
    						$servicesSessions = CustomersServices::find()->select('session_no')->where(['billing_status'=>'unbilled'])->andFilterWhere(['cust_id'=>$id,'seat_id'=>$seat_id])->distinct()->asArray()->all();
    						for($i=0;$i<count($servicesSessions);$i++){
    							$cust_sessionsdata[$servicesSessions[$i]['session_no']] = CustomersServices::find()->where(['session_no'=>$servicesSessions[$i]['session_no'],'billing_status'=>'unbilled'])->andFilterWhere(['cust_id'=>$id,'seat_id'=>$seat_id])->all();
    						}
							
							//GENERATE BILL 
    						return $this->render('bill', [
    								'customer'=>$customer,
    								'seat_id'=>$seat_id,
    								'cust_sessionsdata'=>$cust_sessionsdata,
									'generatebill'=>true,
									'msg'=>'PREVIEW BILL ',
    							]);
						}else{
							return $this->render('noservicesattached',[
								'customer'=>$customer,
								'seat_id'=>$seat_id,
								'cust_session'=>$session_no,
							]);
						}
						
					}
				}
				
			}
		}else{
			throw new \yii\web\NotFoundHttpException();
		}
	}

	public function actionOrderPayment($orderid = null){
		$request = Yii::$app->request;
		
		$orders = Orders::findOne($orderid);
		
		$ordersPayments = OrdersPayments::find()->where(['orders_id'=>$orderid])->all();
		$totalpaymenttillnow = OrdersPayments::find()->where(['orders_id'=>$orderid])->sum('amount');
		$paymentReceiptForm = new PaymentReceiptForm();
		
		//if($orders->cust_id != NULL || $orders->cust_id !=''){
			$custid = $orders->cust_id;
			$customerBonusesComp = new CustomerBonusesComp();
			$bonuses = $customerBonusesComp->checkBonuses($custid);
			$bonus_available = $bonuses['available'];
			$max_redeem_points = ($orders->due_amount > $bonus_available)? $bonus_available : $orders->due_amount;
		//}
		
		
		
        if($request->isAjax){
            /*
            *   Process for ajax request
            */
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
					]),
					'size'=>'large',
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
                ];         
            }else if($paymentReceiptForm->load($request->post())&& $paymentReceiptForm->validate()){
				$totalpayment = 0;
				foreach($paymentReceiptForm->pay as $key=>$pay){
					
					if($pay !=null){
						
						$ordersPayment = new OrdersPayments();
						$ordersPayment->orders_id = $paymentReceiptForm->orderid;
						$ordersPayment->payment_type = $key;
						$ordersPayment->amount = $pay;
						$ordersPayment->payment_date = date('y-m-d');
						
						if(!$ordersPayment->save()){
							 return [
								//'forceReload'=>'#crud-datatable-pjax',
								'title'=> "Error in Payment Method #",
								'content'=>print_r($ordersPayment->errors),
								'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"])										
							]; 
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
					return [
								//'forceReload'=>'#crud-datatable-pjax',
								'title'=> "Error in Order saving #",
								'content'=>print_r($orders->errors),
								'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"])										
							];
				}
				$ordersPayments = OrdersPayments::find()->where(['orders_id'=>$paymentReceiptForm->orderid])->all();
				$totalpaymenttillnow = OrdersPayments::find()->where(['orders_id'=>$paymentReceiptForm->orderid])->sum('amount');
				
				if($orders->status == 'completed'){
					$ordersSessionNos = explode(',',$orders->session_nos);
					array_pop($ordersSessionNos);
					// Loyalty Bonus Generation
					$custNewBonuses = new CustomerBonusesGenerateComp();
					$bonus = $custNewBonuses->generateLoyaltyBonuses($orders->id);
					print_r($bonus);exit;
					$loyaltybonus = $bonus['bonus'];
					$custNewBonuses->generateReferralBonuses($orders->id);
					
					//$cust_log = CustomersLog::updateAll(['status' => 'closed'],['AND', 'cust_id'=>$orders->cust_id, ['=','session_no',$ordersSessionNos]]);
					$cust_log = CustomersLog::updateAll(['status' => 'closed'],['cust_id' => $orders->cust_id, 'session_no' => $ordersSessionNos ]);
				}else{
					$ordersSessionNos = [];
				}
				
				//$model->save();
                return [
                    //'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "Categories #",
                    'content'=>$this->renderAjax('payment-success',[
                        'ordersPayments'=>$ordersPayments,
                        'totalpaymenttillnow'=>$totalpaymenttillnow,
                        'orders'=>$orders,
                        'loyaltybonus'=>$loyaltybonus ? : 0 ,
                        'ordersSessionNos'=>json_encode($ordersSessionNos),
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
					]),
					'size'=>'large',
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
                ];       
            }
        }else{
			if($paymentReceiptForm->load($request->post())&& $paymentReceiptForm->validate()){
				$totalpayment = 0;
				foreach($paymentReceiptForm->pay as $key=>$pay){
					
					if($pay !=null){
						
						$ordersPayment = new OrdersPayments();
						$ordersPayment->orders_id = $paymentReceiptForm->orderid;
						$ordersPayment->payment_type = $key;
						$ordersPayment->amount = $pay;
						$ordersPayment->payment_date = date('y-m-d');
						if(!$ordersPayment->save()){
							 return print_r($ordersPayment->errors); 
						}
						if($key=='points' && isset($custid)){
							$customerredeembonusmodel = new CustomersBonuses();
							$customerredeembonusmodel->cust_id = $custid;
							$customerredeembonusmodel->type = 'redeem';
							$customerredeembonusmodel->order_id = $paymentReceiptForm->orderid;;
							$customerredeembonusmodel->created_date = date('y-m-d');
							$customerredeembonusmodel->bonus_amount = $pay;
							$customerredeembonusmodel->save();
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
					return print_r($orders->errors);
				}
				$ordersPayments = OrdersPayments::find()->where(['orders_id'=>$paymentReceiptForm->orderid])->all();
				$totalpaymenttillnow = OrdersPayments::find()->where(['orders_id'=>$paymentReceiptForm->orderid])->sum('amount');
				
				if($orders->status == 'completed'){
					$ordersSessionNos = explode(',',$orders->session_nos);
					array_pop($ordersSessionNos);
					
					//$cust_log = CustomersLog::updateAll(['status' => 'closed'],['AND', 'cust_id'=>$orders->cust_id, ['=','session_no',$ordersSessionNos]]);
					$cust_log = CustomersLog::updateAll(['status' => 'closed'],['session_no' => $ordersSessionNos ]);
					
					if($orders->cust_id != NULL || $orders->cust_id !=''){
					// Loyalty Bonus Generation
						$custNewBonuses = new CustomerBonusesGenerateComp();
						$bonusloyal = $custNewBonuses->generateLoyaltyBonuses($orders->id);					
						$loyaltybonus = $bonusloyal['bonus'];
						$bonusrefer = $custNewBonuses->generateReferralBonuses($orders->id);
						$referralbonus = $bonusrefer['bonus'];
						
						
						$custbonuses = new CustomerBonusesComp();
						$totalcustbonuses = $custbonuses->checkBonuses($orders->cust_id);
						$availablecustbonuses = $totalcustbonuses['available'];
						
						$sms = new Sms();
						$text =str_replace('_CRMSPA_','%0D%0A',rawurlencode('Dear '.ucwords($orders->cust->name).',_CRMSPA_Thanks for visiting_CRMSPA_ADITI\'S Lifestyle salon. _CRMSPA_Your today\'s Bill Details:_CRMSPA_ID:C'.$orders->cust->id.'_CRMSPA_Amt:'.$orders->total_amount.'/-_CRMSPA_Points got:'.$loyaltybonus.'_CRMSPA_Total Points Available:'.$availablecustbonuses.'_CRMSPA_Visit aditisly.hg.ly'));
						$sms->sendSms($orders->cust->contact,$text);
						
						if(isset($orders->cust->introducerCustomer->id)){
							$totalintroducerbonuses = $custbonuses->checkBonuses($orders->cust->introducerCustomer->id);
							$introduceravailablebonuses = $totalintroducerbonuses['available'];
							
							$textreferral =str_replace('_CRMSPA_','%0D%0A',rawurlencode('Dear '.ucwords($orders->cust->introducerCustomer->name).',_CRMSPA_Your referral '.ucwords($orders->cust->name).'has visited_CRMSPA_ADITI\'S Lifestyle salon on '.$orders->order_date.'_CRMSPA_ Bill Details:_CRMSPA_Amt:'.$orders->total_amount.'/-_CRMSPA_Your Referral Points:'.$referralbonus.'_CRMSPA_Total Points Available:'.$introduceravailablebonuses.'_CRMSPA_Visit aditisly.hg.ly'));
							$sms->sendSms($orders->cust->introducerCustomer->contact,$textreferral);
						}
					}				
				}else{
					$ordersSessionNos = [];
				}
				
				//$model->save();
                return $this->render('payment-success',[
                        'ordersPayments'=>$ordersPayments,
                        'totalpaymenttillnow'=>$totalpaymenttillnow,
                        'orders'=>$orders,
                        'ordersSessionNos'=>json_encode($ordersSessionNos),
                    ]);    
            }else{
				return $this->render('order-payment',[
						'orders'=>$orders,
						'paymentReceiptForm'=>$paymentReceiptForm,
						'bonuses'=>$bonuses,
						'max_redeem_points'=>round($max_redeem_points),
						'bonus_available'=>round($bonus_available),
						'ordersPayments'=>$ordersPayments,
						'totalpaymenttillnow'=>$totalpaymenttillnow,
					]);
			}
		}
	}
	
	

}
