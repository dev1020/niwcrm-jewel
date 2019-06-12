<?php

namespace backend\controllers;

use Yii;
use backend\models\CompanyCustomers;
use backend\models\Customers;
use backend\models\Orders;
use backend\models\CompanyCustomersSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;

use yii\web\UploadedFile;
use yii\helpers\BaseFileHelper;

use common\components\CustomerBonusesComp;
use backend\models\CompanyCustomersExcelForm;

/**
 * CompanyCustomersController implements the CRUD actions for CompanyCustomers model.
 */
class CompanyCustomersController extends Controller
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
     * Lists all CompanyCustomers models.
     * @return mixed
     */
    public function init()
	{
		if((!yii::$app->user->can('Admin')) && (!yii::$app->user->can('manager'))){
			throw new \yii\web\ForbiddenHttpException;
		}
	}
    public function actionIndex()
    {   
		$session = Yii::$app->session;
		$company = $session['company.company_id'];	
        $searchModel = new CompanyCustomersSearch();
        $searchModel->company_id = $company;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		
		

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'company' => $company,
        ]);
    }


    /**
     * Displays a single CompanyCustomers model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {   
        $request = Yii::$app->request;
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                    'title'=> "CompanyCustomers #".$id,
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
	
	public function actionCompanycustomerslist($q = null, $id = null) {
		$session = Yii::$app->session;
		$company = $session['company.company_id'];	
		
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		$out = ['results' => ['id' => '', 'text' => '']];
		if (!is_null($q)){
			$query = new yii\db\Query;
			$query->select(['customers.id,name,customers.contact, concat(customers.name," [",concat("C",customers.id)," ",customers.contact,"]") as text'])
				->from('company_customers')
				->leftJoin('customers', 'company_customers.cust_id = customers.id')
				->where(['company_customers.company_id'=>$company])
				->andWhere([
					'or',
					['like', 'customers.name', $q],
					['like','customers.contact',$q],
					['like','concat("C",customers.id)',$q],
				])
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
	
	public function actionCustomerStats($id)
    {   
        $request = Yii::$app->request;
		$session = Yii::$app->session;
		$company = $session['company.company_id'];	
		$model = Customers::findOne($id);
		$customerbonus = new CustomerBonusesComp();
		$customerbonus = $customerbonus->checkBonuses($id,$company);
		
		/*if(Customers::find()->where(['introducer_customer_id'=>$id])->exists()){
			$referrals = Customers::find()->where(['introducer_customer_id'=>$id])->all();
			}else{
			$referrals = [];
		}*/
		// FOR THE REFERRALS STARTS
		/*$queryreferrals = new \yii\db\Query;    
			$queryreferrals->select('customers.id,customers.name,SUM(bonus_amount) as bonus')
				->from('customers')
				->leftJoin('customers_bonuses','customers.id=customers_bonuses.cust_id')
				->where(['introducer_customer_id'=>$id])
				->orderBy('customers.id')
				->groupBy('customers.id');
			$command = $queryreferrals->createCommand();
			$referrals = $command->queryAll();
		*/
		// FOR THE REFERRALS ENDS
		
		// IMPORTANT DATES STARTS
		
		$connection = Yii::$app->getDb();
		$command = $connection->createCommand("
			SELECT * FROM `customers_important_dates` WHERE (`cust_id`=$id) AND DATE_FORMAT(imp_date, '%m-%d') >= DATE_FORMAT(NOW(), '%m-%d') ORDER BY imp_date LIMIT 0, 1");

		$importantdates = $command->queryAll();
		 
		// IMPORTANT DATES ENDS
		
		if(Orders::find()->where(['cust_id'=>$id,'company_id'=>$company])->exists()){
			$orders = Orders::find()->where(['cust_id'=>$id,'company_id'=>$company])->orderBy(['id'=>SORT_DESC])->limit(5)->all();
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
		
		
		// For The Pie Chart Ends
		//echo "<pre>";
		//print_r($categorywisesum);
		//exit;
		
		$totalOrderValue = Orders::find()->where(['cust_id'=>$id,'company_id'=>$company,'status'=>'completed'])->sum('total_amount');
		//$totalPendingValue = Orders::find()->where(['cust_id'=>$id,'status'=>'isdue'])->sum('due_amount');
		
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                    'title'=> "",
                    'content'=>$this->renderAjax('customerdetails', [
                        'model' => $model,
                        'customerbonusavailable' => $customerbonus['available'],
						//'referrals'=>$referrals,
						'referrals'=>0,
						'importantdates'=>$importantdates,
						'orders'=>$orders,
						//'servicesdata1'=>$vc,
						
						'totalOrderValue'=>($totalOrderValue)? $totalOrderValue : 0, 
						 
                    ]),
                    'footer'=> Html::button('Done',['class'=>'btn btn-success pull-right','data-dismiss'=>"modal"]),
								
								
                    'size'=>'large',       
                ];    
        }else{
            return $this->render('customerdetails', [
						'model' => $model,
                        'customerbonusavailable' => $customerbonus['available'],
						//'referrals'=>$referrals,
						'referrals'=>0,
						'importantdates'=>$importantdates,
						'orders'=>$orders,
						//'servicesdata1'=>$vc,
						
						'totalOrderValue'=>($totalOrderValue)? $totalOrderValue : 0, //for pie chart
						
            ]);
        }
    }
    /**
     * Creates a new CompanyCustomers model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
		$session = Yii::$app->session;
		$company = $session['company.company_id'];	
        $request = Yii::$app->request;
        $model = new CompanyCustomers();  
		$customermodel = new Customers();
		$customermodel->scenario = 'companycustomer';

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Create new CompanyCustomers",
                    'content'=>$this->renderAjax('create', [
                        
                        'customermodel' => $customermodel,
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            }else if($customermodel->load($request->post())){
					if(Customers::find()->where(['contact'=>$customermodel->contact])->exists()){
						$cust_id = Customers::find()->where(['contact'=>$customermodel->contact])->one()->id;
					}else if(!$customermodel->save()){
							return [
							'title'=> "Create new CompanyCustomers",
							'content'=>$this->renderAjax('create', [
								'customermodel' => $customermodel,
								'model' => $model,
							]),
							'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
										Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
				
						];
						
					}
					$cust_id = $customermodel->id;
					if(CompanyCustomers::find()->where(['company_id'=>$company,'cust_id'=>$cust_id])->exists()){
						return [
							'forceReload'=>'#crud-datatable-pjax',
							'title'=> "Warning",
							'content'=>'<span class="text-warning">Customer already existed</span>',
							'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
									Html::a('Create More',['create'],['class'=>'btn btn-primary','role'=>'modal-remote'])
				
						];
					}else{
						$model->company_id = $company;
						$model->cust_id = $cust_id;
						$model->customer_number = $customermodel->contact;
						$model->created_date = date('Y-m-d');
						$model->save();
						return [
							'forceReload'=>'#crud-datatable-pjax',
							'title'=> "Create new CompanyCustomers",
							'content'=>'<span class="text-success">Create CompanyCustomers success</span>',
							'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
									Html::a('Create More',['create'],['class'=>'btn btn-primary','role'=>'modal-remote'])
				
						]; 
					}  
            }else{           
                return [
                    'title'=> "Create new CompanyCustomers",
                    'content'=>$this->renderAjax('create', [
                        
						'customermodel' => $customermodel,
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
                    
					'customermodel' => $customermodel,
                ]);
            }
        }
       
    }

    /**
     * Updates an existing CompanyCustomers model.
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
                    'title'=> "Update CompanyCustomers ",
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
                ];         
            }else if($model->load($request->post())){
				if($model->cust_id == $model->introducer_id){
					$model->addError('introducer_id','Customer him/herself cannot be his/her introducer');
					return [
                    'title'=> "Update CompanyCustomers",
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
					];
				}else{
					$model->save();
					return [
						'forceReload'=>'#crud-datatable-pjax',
						'title'=> "Update CompanyCustomers",
						'content'=>$this->renderAjax('view', [
							'model' => $model,
						]),
						'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
								Html::a('Edit',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
					];
				}
				    
            }else{
                 return [
                    'title'=> "Update CompanyCustomers",
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
     * Delete an existing CompanyCustomers model.
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
     * Delete multiple existing CompanyCustomers model.
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
    
    public function actionImportExcel()
    {        
		$inputFile = 'uploads/test.xlsx' ;
		$request = Yii::$app->request;
		$session = Yii::$app->session;
		$company = $session['company.company_id'];	
		
		$model = new CompanyCustomersExcelForm();
		
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
					
					$model->excel_file->saveAs(Yii::getAlias($path.'Customers'.date('y-m-d h-i-s').'.'.$model->excel_file->extension));
					$inputFile = $path.'Customers'.date('y-m-d h-i-s').'.'.$model->excel_file->extension;
					
					try{
						$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFile);
						$sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
						
					}catch (InvalidArgumentException $e) {
						$helper->log('Error loading file "' . pathinfo($inputFile, PATHINFO_BASENAME) . '": ' . $e->getMessage());
					}
					for($i=1;$i<=count($sheetData);$i++){
						$datatoinsert = array_values($sheetData[$i]);
						$datatoinsert[5]=preg_replace('/[^0-9]/', '', $datatoinsert[5]);
						if($datatoinsert[5]!=''){
							
							if(Customers::find()->where(['contact'=>$datatoinsert[5]])->exists()){
								$customer = Customers::find()->where(['contact'=>$datatoinsert[5]])->one();
								
							}else{
								$customer = new Customers();
								$customer->scenario = 'companycustomer';
								$customer->contact = $datatoinsert[5];
								$customer->name = $datatoinsert[0];
								$customer->address = $datatoinsert[1].' '.$datatoinsert[2].' '.$datatoinsert[3].' '.$datatoinsert[4];
								if(!$customer->save()){
									return [
										'title'=> "Error",
										'content'=>print_r($customer->errors),
										'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]),
									];
								}
							}
							$cust_id = $customer->id;
							$companyCustomer = new CompanyCustomers();
							$companyCustomer->company_id = $company;
							$companyCustomer->cust_id = $cust_id;
							$companyCustomer->customer_number = $customer->contact;
							$companyCustomer->created_date = date('Y-m-d');
							$companyCustomer->save();
							if(!$companyCustomer->save()){
								return [
									'title'=> "Error",
									'content'=>print_r($companyCustomer->errors),
									'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]),
								];
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
    
    /**
     * Finds the CompanyCustomers model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CompanyCustomers the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CompanyCustomers::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
