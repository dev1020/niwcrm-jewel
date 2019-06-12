<?php

namespace backend\controllers;

use Yii;
use common\models\User;
use common\models\UserSearch;
use backend\models\SignupForm;
use backend\models\CompanyBranches;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\base\ErrorException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\widgets\ActiveForm;

use common\components\Sms;

use \yii\web\Response;
use yii\web\UploadedFile;
use yii\helpers\BaseFileHelper;

/**
 * UsermanagerController implements the CRUD actions for User model.
 */
class UsermanagerController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
			/*'access' => [
            'class' => AccessControl::className(),
            'rules' => [
				[
						'actions' => ['index'], // these action are accessible 
													   //only the yourRole1 and yourRole2
						'allow' => true,
						'roles' => ['@'],
					],
					   
				],
			], */
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
		//print_r(Yii::$app->authManager->getRolesByUser(Yii::$app->user->identity->id));
		//exit;
		if(Yii::$app->user->can((new \ReflectionClass($this))->getShortName().'_'.$this->action->id)){
			$searchModel = new UserSearch();
			$dataProvider = $searchModel->search(Yii::$app->request->queryParams,'employees');
			$dataProvider->sort = ['defaultOrder' => ['company_id' => 'DESC']]; 

			return $this->render('index', [
				'searchModel' => $searchModel,
				'dataProvider' => $dataProvider,
			]);
		}else{
			throw new ForbiddenHttpException;
		}
    }
	
	public function actionViewuserdetailbycontact($id)
    {
		 $request = Yii::$app->request;
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
			$userdetail = new User();
			$userdetail = $userdetail->findIdentity($id) ;
			return $userdetail;
		}
    }
	
	public function actionCheckuniqueuser($contact)
    {
		$request = Yii::$app->request;
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
			$usercount = User::find()->where(['contact_number'=>$contact])->count();
			if($usercount>0){
				$response['msg'] = 'User Already registered with this number';
			}else{
				$response['msg'] = 'success';
			}
			return $response;
		}
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
		if(Yii::$app->user->can((new \ReflectionClass($this))->getShortName().'_'.$this->action->id)){
			return $this->render('view', [
				'model' => $this->findModel($id),
			]);
		}else{
			throw new ForbiddenHttpException;
		}
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
		if(Yii::$app->user->can((new \ReflectionClass($this))->getShortName().'_'.$this->action->id)){
			$usermodel = new User();
			$signupmodel = new SignupForm();
			//$signupmodel->scenario = 'backend';

			if ($signupmodel->load(Yii::$app->request->post())) {
				
				//$signupmodel->profilepic = UploadedFile::getInstance($signupmodel,'profilepic');
				//$signupmodel->profilepic->saveAs('uploads/'.$signupmodel->username.'.'.$signupmodel->profilepic->extension);
				
				if($user = $signupmodel->signup()){
					//echo $user->address;
					//echo $user->profilepic = 'uploads/'.$signupmodel->username.'.'.$signupmodel->profilepic->extension;exit;
					//$user->save();
					return $this->redirect(['view', 'id' => $user->id]);
				} else{
					return $this->render('create', [
					'signupmodel' => $signupmodel,
					'usermodel' => $usermodel,
					]);
				}          
			} else{
				return $this->render('create', [
					'signupmodel' => $signupmodel,
					'usermodel' => $usermodel,
				]);
			}
		}else{
			throw new ForbiddenHttpException;
		}
        
    }
	public function actionGetbranches() {
    $out = [];
		if (isset($_POST['depdrop_parents'])) {
			$parents = $_POST['depdrop_parents'];
			if ($parents != null) {
				$company_id = $parents[0];
				$branches = CompanyBranches::find()->where(['company_id'=>$company_id])->orderBy(['branch_name'=>SORT_ASC])->all(); 
				// the getSubCatList function will query the database based on the
				// cat_id and return an array like below:
				// [
				//    ['id'=>'<sub-cat-id-1>', 'name'=>'<sub-cat-name1>'],
				//    ['id'=>'<sub-cat_id_2>', 'name'=>'<sub-cat-name2>']
				// ]
				
				foreach($branches as $branch){
					$out[] = ['id' => $branch['id'], 'name' => $branch['branch_name'],];
				}
				return \yii\helpers\Json::encode(['output'=>$out, 'selected'=>'']);
			}
		}
		return Json::encode(['output'=>'', 'selected'=>'']);
	}
    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
		if(Yii::$app->user->can((new \ReflectionClass($this))->getShortName().'_'.$this->action->id)){
			$user = User::findIdentity($id);
			//print_r($user);exit;
			$profilepic = $user->profilepic;
			$model = $this->findModel($id);
			

			if ($user->load(Yii::$app->request->post())) {
				//print_r($_POST);die;
				if(UploadedFile::getInstance($user,'profilepic')){
					//echo "pic";
					$user->profilepic = UploadedFile::getInstance($user,'profilepic');
					$user->profilepic->saveAs(Yii::getAlias('@frontend').'/web/uploads/profilepic/'.$user->username.'.'.$user->profilepic->extension);
					$user->profilepic = $user->username.'.'.$user->profilepic->extension;
				}else{
					$user->profilepic = $profilepic ;
				}
				$user->save(false);
				$user = User::findIdentity($id);
				return $this->redirect(['view', 'id' => $user->id]);
			} else {
				return $this->render('update', [
					'model' => $user,
				]);
			}
		}else{
			
			throw new ForbiddenHttpException;
		}
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
		if(Yii::$app->user->can((new \ReflectionClass($this))->getShortName().'_'.$this->action->id)){
			$this->findModel($id)->delete();
			return $this->redirect(['index']);
		}else{
			
			throw new ForbiddenHttpException;
		}
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	
	public function actionValidation()
	{
		$signupmodel = new SignupForm();
		if(Yii::$app->request->isAjax && $signupmodel->load(Yii::$app->request->post())){
			Yii::$app->response->format = 'json';
			return ActiveForm::validate($signupmodel);
		
		}
		
	}
	
	
	/**
     * Lists all(Users) User models.
     * @return mixed
     */
    public function actionCustomers()
    {
		//if(Yii::$app->user->can((new \ReflectionClass($this))->getShortName().'_'.$this->action->id)){
			/*$dataProvider = new ActiveDataProvider([
				'query' => User::find()->where(['usertype'=>'user']),
			]);
			return $this->render('customers', [
				'dataProvider' => $dataProvider,
			]);*/
		//}else{
		//	throw new ForbiddenHttpException;
		//}
		
		$searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,'customer');

        return $this->render('customers', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
	
	/**
     * Lists all(Suppliers) User models.
     * @return mixed
     */
    public function actionSuppliers()
    {
		//if(Yii::$app->user->can((new \ReflectionClass($this))->getShortName().'_'.$this->action->id)){
			$dataProvider = new ActiveDataProvider([
				'query' => User::find()->where(['usertype'=>'backenduser']),
			]);
			return $this->render('backenduser', [
				'dataProvider' => $dataProvider,
			]);
		//}else{
		//	throw new ForbiddenHttpException;
		//}
    }
	
	public function actionCreatebyotp()
    {
		$request		 = Yii::$app->request;
        $model           = new SignupForm();
		$model->scenario = 'backendsignupotp';
		$bposts 		 = new Bposts();
		$bpostsGallery 	 = new BpostsGallery();
		$sms 			 = new Sms();
		$place 			 = new Place();
		$place->scenario = 'userbyotp';
		
		$bpostcategories = new Bpostcategories();
		
		if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = 'json';
            if($request->isGet){
                return [
                    'title'=> "Sign Up",
                    'content'=>$this->renderAjax('signupmodal', [
                        'model' => $model,
						'size'=> "normal",
                    ]),
                    
        
                ];         
            }else if($model->load($request->post()) && $bposts->load($request->post()) && $place->load($request->post()) ){
				//return $request->post();
				
				$session = Yii::$app->session;
				//return $response = ['response'=>$session->get('language')];
					
					$bposts->bpost_created_at = date('Y-m-d H:i:s');
					$bposts->bpost_reffered_by = Yii::$app->user->identity->id;					
					
					if($_POST['place_id'] ===''){
						$place->created_by = Yii::$app->user->identity->id;
						$place->created_at = date('Y-m-d H:i:s');
					}
					
					
				if($_POST['otp'] !=''){
					if($session->get('otp') == $_POST['otp']){
						if ($user = $model->backsignupbyotp()) {
							if($bposts->bpost_title != ""){
								$place->save();
								$bposts->bpost_place_id = $place->id;
								$bposts->bpost_smsnumber = $user->contact_number;
								
								$bposts->bpost_created_by = $user->id;
								
								$bposts->bpost_numberverified = 'Y';
								//$bposts->save();
								
								if(!$bposts->save()){
									return $bposts->errors; 
								}
								
								if(UploadedFile::getInstance($bposts,'bpost_image')){
									//return $bposts->bpost_image;
									$path = Yii::getAlias('@frontend').'/web/uploads/bpost/';
									BaseFileHelper::createDirectory($path,0777,false);
									$bposts->bpost_image = UploadedFile::getInstance($bposts,'bpost_image');
									$bposts->bpost_image->saveAs(Yii::getAlias($path.$bposts->bpost_slug.'.'.$bposts->bpost_image->extension));
									$bposts->bpost_image = $bposts->bpost_slug.'.'.$bposts->bpost_image->extension;
									$bposts->save();
								}
								//return $bposts->bpost_image;
								$bpostcategories->bpostcategories_bpost_id = $bposts->bpost_id;
								$bpostcategories->bpostcategories_categories_id = $bposts->category;
								$save = $bpostcategories->save();
								
							}
							$text = rawurlencode('Thanks for registering with SALTLAKE.IN – Local Search Engine of Kolkata. Go for PREMIUM Listing & get UNLIMITED Business Lead + Other Promo Tools. CALL NOW.');
							$sms->sendSms($user->contact_number, $text);
							return $response = ['msg'=>'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
							  <strong>Congratulation</strong> '.$user->first_name.' '.$user->last_name.' has Successfully registered '.$bposts->bpost_title .' with SALTLAKE.IN . Business number is <strong style="color:blue">verified</strong>','response'=>'Success'];
						} else{
							return $model->errors ;
						}
					}else{
						return $response = ['response'=>'Otp mismatch'];
					}
				}elseif($user = $model->backsignupbyotp()){
					$place->save();
					$bposts->bpost_place_id = $place->id;
					$bposts->bpost_smsnumber = $user->contact_number;
					$bposts->bpost_created_by = $user->id;
					if(!$bposts->save()){
						return $bposts->errors; 
					}
						if(UploadedFile::getInstance($bposts,'bpost_image')){
							$path = Yii::getAlias('@frontend').'/web/uploads/bpost/';
							BaseFileHelper::createDirectory($path,0777,false);
							$bposts->bpost_image = UploadedFile::getInstance($bposts,'bpost_image');
							$bposts->bpost_image->saveAs(Yii::getAlias($path.$bposts->bpost_slug.'.'.$bposts->bpost_image->extension));
							$bposts->bpost_image = $bposts->bpost_slug.'.'.$bposts->bpost_image->extension;
							$bposts->save();
						}
					$bpostcategories->bpostcategories_bpost_id = $bposts->bpost_id;
					$bpostcategories->bpostcategories_categories_id = $bposts->category;
					$save = $bpostcategories->save();
					$text = rawurlencode('Thanks for registering with SALTLAKE.IN – Local Search Engine of Kolkata. Go for PREMIUM Listing & get UNLIMITED Business Lead + Other Promo Tools. CALL NOW.');
					$sms->sendSms($user->contact_number, $text);
					return $response = ['msg'=>'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
							  <strong>Congratulation</strong> '.$user->first_name.' '.$user->last_name.' has Successfully registered '.$bposts->bpost_title .' with SALTLAKE.IN . Business number is <strong style="color:red">not verified</strong>','response'=>'Success'];
				}else{
					return $model->errors;
				}
            }
        }else{
            /*
            *   Process for non-ajax request
            */
            if($model->load($request->post()) && $bposts->load($request->post()) && $place->load($request->post()) ){
				//return $request->post();
				
				$session = Yii::$app->session;
				//return $response = ['response'=>$session->get('language')];
					
					$bposts->bpost_created_at = date('Y-m-d H:i:s');
					$bposts->bpost_reffered_by = Yii::$app->user->identity->id;					
					
					if($_POST['place_id'] ===''){
						$place->created_by = Yii::$app->user->identity->id;
						$place->created_at = date('Y-m-d H:i:s');
					}
					
					
				if($_POST['otp'] !=''){
					if($session->get('otp') == $_POST['otp']){
						if ($user = $model->backsignupbyotp()) {
							if($bposts->bpost_title != ""){
								$place->save();
								$bposts->bpost_place_id = $place->id;
								$bposts->bpost_smsnumber = $user->contact_number;
								
								$bposts->bpost_created_by = $user->id;
								
								$bposts->bpost_numberverified = 'Y';
								//$bposts->save();
								
								if(!$bposts->save()){
									return $bposts->errors; 
								}
								
								if(UploadedFile::getInstance($bposts,'bpost_image')){
									//return $bposts->bpost_image;
									$path = Yii::getAlias('@frontend').'/web/uploads/bpost/';
									BaseFileHelper::createDirectory($path,0777,false);
									$bposts->bpost_image = UploadedFile::getInstance($bposts,'bpost_image');
									$bposts->bpost_image->saveAs(Yii::getAlias($path.$bposts->bpost_slug.'.'.$bposts->bpost_image->extension));
									$bposts->bpost_image = $bposts->bpost_slug.'.'.$bposts->bpost_image->extension;
									$bposts->save();
								}
								//return $bposts->bpost_image;
								$bpostcategories->bpostcategories_bpost_id = $bposts->bpost_id;
								$bpostcategories->bpostcategories_categories_id = $bposts->category;
								$save = $bpostcategories->save();
								
							}
							$text = rawurlencode('Thanks for registering with SALTLAKE.IN – Local Search Engine of Kolkata. Go for PREMIUM Listing & get UNLIMITED Business Lead + Other Promo Tools. CALL NOW.');
							$sms->sendSms($user->contact_number, $text);
							
							\Yii::$app->getSession()->setFlash('msg', '<div class="alert alert-success alert-dismissable fade in">
										<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
										<strong>Congratulation</strong> '.$user->first_name.' '.$user->last_name.' has Successfully registered '.$bposts->bpost_title .' with SALTLAKE.IN . Business number is <strong style="color:blue">verified</strong>
									  </div>');
							/*return $response = ['msg'=>'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
							  <strong>Congratulation</strong> '.$user->first_name.' '.$user->last_name.' has Successfully registered '.$bposts->bpost_title .' with SALTLAKE.IN . Business number is <strong style="color:blue">verified</strong>','response'=>'Success'];
							*/
						} else{
							return $model->errors ;
						}
					}else{
						return $response = ['response'=>'Otp mismatch'];
					}
				}elseif($user = $model->backsignupbyotp()){
					$place->save();
					$bposts->bpost_place_id = $place->id;
					$bposts->bpost_smsnumber = $user->contact_number;
					$bposts->bpost_created_by = $user->id;
					if(!$bposts->save()){
						return $bposts->errors; 
					}
						if(UploadedFile::getInstance($bposts,'bpost_image')){
							$path = Yii::getAlias('@frontend').'/web/uploads/bpost/';
							BaseFileHelper::createDirectory($path,0777,false);
							$bposts->bpost_image = UploadedFile::getInstance($bposts,'bpost_image');
							$bposts->bpost_image->saveAs(Yii::getAlias($path.$bposts->bpost_slug.'.'.$bposts->bpost_image->extension));
							$bposts->bpost_image = $bposts->bpost_slug.'.'.$bposts->bpost_image->extension;
							$bposts->save();
						}
					$bpostcategories->bpostcategories_bpost_id = $bposts->bpost_id;
					$bpostcategories->bpostcategories_categories_id = $bposts->category;
					$save = $bpostcategories->save();
					$text = rawurlencode('Thanks for registering with SALTLAKE.IN – Local Search Engine of Kolkata. Go for PREMIUM Listing & get UNLIMITED Business Lead + Other Promo Tools. CALL NOW.');
					$sms->sendSms($user->contact_number, $text);
					
					\Yii::$app->getSession()->setFlash('msg', '<div class="alert alert-success alert-dismissable fade in">
										<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
										<strong>Congratulation</strong> '.$user->first_name.' '.$user->last_name.' has Successfully registered '.$bposts->bpost_title .' with SALTLAKE.IN . Business number is <strong style="color:red">not verified</strong>
									  </div>');
					//return $response = ['msg'=>'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
					//	  <strong>Congratulation</strong> '.$user->first_name.' '.$user->last_name.' has Successfully registered '.$bposts->bpost_title .' with SALTLAKE.IN . Business number is <strong style="color:red">not verified</strong>','response'=>'Success'];
				}else{
					return $model->errors;
				}
				
				return $this->redirect(['createbyotp']);
            } else {
			
                return $this->render('userbyotp', [
                    'model' => $model,
					'bposts' =>$bposts,
					'place' =>$place,
                ]);
            }
        }
		return $this->render('userbyotp',[
				'model' => $model,
				'bposts' =>$bposts,
				'place' =>$place,
			]);
		
    }
}
