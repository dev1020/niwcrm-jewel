<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\Helpers\Html;
use yii\Helpers\Url;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use \yii\web\Response;
use common\models\LoginForm;
use yii\base\InvalidParamException;
use yii\web\ForbiddenHttpException;
use yii\web\BadRequestHttpException;
use backend\models\PasswordResetRequestForm;
use backend\models\ResetPasswordForm;
use common\models\User;
use backend\models\ChangepasswordForm;
use backend\models\Orders;
use backend\models\Services;
use backend\models\Customers;
use backend\models\CustomersBonuses;
use backend\models\CompanyCustomers;
use backend\models\Companies;

use yii\web\UploadedFile;
use yii\helpers\BaseFileHelper;
use yii\data\ActiveDataProvider;
use common\components\SettingsGetter;
/**
 * Site controller
 */
class SiteController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'request-password-reset','reset-password', 'error', 'list'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'maintainance','index', 'profile', 'changepassword', 'getcontrollersandactions', 'getuserlocation', 'settings', 'settings-add-attributes','settings-delete-attributes','set-company','set-branch','search','profile-edit'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['get', 'post'],
                ],
            ],
        ];
    }

    public function beforeAction($action) {
        if (parent::beforeAction($action)) {
            // change layout for error action
            //echo "dd"; exit;
            if ($action->id == 'error')
                $this->layout = 'loginLayout';
            return true;
        } else {
            return false;
        }
    }

    /**
     * @inheritdoc
     */
    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }
	public function init(){
        $session = Yii::$app->session;
        $settings_getter = new SettingsGetter();
        Yii::$app->name = $settings_getter->get_attribute_value('brand_name',$session['company.company_id']) ? $settings_getter->get_attribute_value('brand_name',$session['company.company_id']) : Yii::$app->name;
    }
	public function actionSetCompany(){
		$request = yii::$app->request;
		
		if($request->isAjax){
			Yii::$app->response->format = Response::FORMAT_JSON;
			if($request->isPost){
				$session = yii::$app->session;
				$session->open();
				if($request->post('company_id')!=NULL || $request->post('company_id')!=''){
					$session['company.company_id'] = $request->post('company_id');
				}else{
					$session->remove('company.company_id');
				}
				return $this->redirect(Yii::$app->request->referrer);
			}			
		}
	}
	public function actionSetBranch(){
		$request = yii::$app->request;
		
		if($request->isAjax){
			Yii::$app->response->format = Response::FORMAT_JSON;
			if($request->isPost){
				$session = yii::$app->session;
				$session->open();
				if($request->post('branch_id')!=NULL || $request->post('branch_id')!=''){
					$session['company.branch_id'] = $request->post('branch_id');
				}else{
					$session->remove('company.branch_id');
				}
				
				return $this->redirect(Yii::$app->request->referrer);
			}			
		}
	}
    /**
     * Displays homepage.
     *
     * @return string
     */
   public function actionIndex() {
		$request = Yii::$app->request;
		$session = Yii::$app->session;
		
		if(yii::$app->user->can('executive')){
			$useridentity = yii::$app->user->identity->id ;
		}else{
			$useridentity = null;
		}
		$orders_count = Orders::find()->count();
		
		$company = $session['company.company_id'];
		$branch = $session['company.branch_id'];
		
		// Check Active upto
		
		
		$order_total_amount = Orders::find()->sum('total_amount');
		
		$connection = Yii::$app->getDb();
	
	//TOTAL CUSTOMERS
		if($company !='' || $company != NULL){
			$customers_count = CompanyCustomers::find()->where(['company_id'=>$company])->count();
		}else{
			$customers_count = Customers::find()->count();
		}

	
	// TOTAL SALES THIS MONTH STARTS
		$query = "SELECT SUM(total_amount) as total_amount FROM `orders` WHERE DATE_FORMAT(order_date, '%y-%m') = DATE_FORMAT(NOW(), '%y-%m')";
		if($company!='' || $company!=NULL){
			$query .= " AND company_id = $company" ;
		}
		if($branch!='' || $branch!=NULL){
			$query .= " AND branch_id = $branch" ;
		}
		if(yii::$app->user->can('executive')){
			$query .= " AND created_by = $useridentity" ;
		}
		$command = $connection->createCommand($query);
		$order_total_amount_this_month = $command->queryOne();
	// TOTAL SALES THIS MONTH ENDS
		
	// TODAY'S SALES STARTS
		$query = "SELECT SUM(total_amount) as total_amount FROM `orders` WHERE DATE_FORMAT(order_date, '%y-%m-%d') = DATE_FORMAT(NOW(), '%y-%m-%d')";
		if($company!='' || $company!=NULL){
			$query .= " AND company_id = $company";
		}
		if($branch!='' || $branch!=NULL){
			$query .= " AND branch_id = $branch";
		}
		if(yii::$app->user->can('executive')){
			$query .= " AND created_by = $useridentity";
		}
		$command = $connection->createCommand($query);
		$order_total_amount_today = $command->queryOne();
		
		$query ="SELECT SUM(bonus_amount) as loyaltybonus FROM `customers_bonuses` LEFT JOIN `orders` on customers_bonuses.order_id = orders.id WHERE DATE_FORMAT(orders.order_date, '%y-%m-%d') = DATE_FORMAT(NOW(), '%y-%m-%d') AND customers_bonuses.type = 'loyalty'";
		if($company!='' || $company!=NULL){
			$query .= " AND orders.company_id = $company" ;
		}
		if($branch!='' || $branch!=NULL){
			$query .= " AND orders.branch_id = $branch" ;
		}
		if(yii::$app->user->can('executive')){
			$query .= " AND created_by = $useridentity" ;
		}
		$command = $connection->createCommand($query);
		$result = $command->queryOne();
		$total_loyaltybonus_today = (int)$result['loyaltybonus'];
		
		
	// TODAY'S SALES ENDS
	
	//FOR NEW CUSTOMERS STARTS
	    $query = "SELECT COUNT(*) as customer_count FROM `company_customers` WHERE DATE_FORMAT(created_date, '%y-%m') = DATE_FORMAT(NOW(), '%y-%m')";
		if($company!='' || $company!=NULL){
			$query .= " AND company_id = $company" ;
		}
		
		$command1 = $connection->createCommand($query);
		$customers_this_month = $command1->queryOne();
		
		$newcustomers_for_chart = [];
		for($i=1;$i<=12;$i++){
			$query = "SELECT COUNT(*) as nc_count FROM `company_customers` WHERE MONTH(created_date) = $i AND DATE_FORMAT(created_date, '%y') = DATE_FORMAT(NOW(), '%y')";
			if($company!='' || $company!=NULL){
			$query .= " AND company_id = $company" ;
			}
			
			$commandnc = $connection->createCommand($query);
			$high_chart_ncustomers = $commandnc->queryOne();
			$newcustomers_for_chart[] = (int)$high_chart_ncustomers['nc_count'];
		}
		$sales_for_chart = [];
		for($i=1;$i<=12;$i++){
			$query = "SELECT SUM(total_amount) as total_amount FROM `orders` WHERE MONTH(order_date) = $i AND DATE_FORMAT(order_date, '%y') = DATE_FORMAT(NOW(), '%y')";
			if($company!='' || $company!=NULL){
			$query .= "AND company_id = $company" ;
			}
			if($branch!='' || $branch!=NULL){
			$query .= " AND branch_id = $branch" ;
			}
			$commandnc = $connection->createCommand($query);
			$high_chart_sales = $commandnc->queryOne();
			$sales_for_chart[] = (int)$high_chart_sales['total_amount'];
		}
		
		$visitedcustomers_for_chart = [];
		for($i=1;$i<=12;$i++){
			$query = "SELECT COUNT(*) as vc_count FROM `orders` WHERE MONTH(order_date) = $i AND DATE_FORMAT(order_date, '%y') = DATE_FORMAT(NOW(), '%y')";
			if($company!='' || $company!=NULL){
			$query .= "AND company_id = $company" ;
			}
			if($branch!='' || $branch!=NULL){
			$query .= " AND branch_id = $branch" ;
			}
			$commandnc = $connection->createCommand($query);
			$high_chart_visitedcustomers = $commandnc->queryOne();
			$visitedcustomers_for_chart[] = (int)$high_chart_visitedcustomers['vc_count'];
		}
		//Latest Order Part STARTS
		if(Orders::find()->orderBy(['id'=>SORT_DESC])->andFilterWhere(['company_id'=>$company,'branch_id'=>$branch,'created_by'=>$useridentity])->exists()){
			
			$latest_order = Orders::find()->andFilterWhere(['company_id'=>$company,'branch_id'=>$branch,'created_by'=>$useridentity])->orderBy(['id'=>SORT_DESC])->limit(1)->one();
			$latest_order_amount = $latest_order->total_amount;
			$latest_order_id = $latest_order->id;
			
			if(CustomersBonuses::find()->where(['order_id'=>$latest_order_id,'type'=>'loyalty'])->exists()){
				$latest_order_loyaltypoints = CustomersBonuses::find()->where(['order_id'=>$latest_order_id,'type'=>'loyalty'])->one()->bonus_amount;
			}else{
				$latest_order_loyaltypoints = 0;
			}
			if(CustomersBonuses::find()->where(['order_id'=>$latest_order_id,'type'=>'referral'])->exists()){
				$latest_order_referralpoints = CustomersBonuses::find()->where(['order_id'=>$latest_order_id,'type'=>'referral'])->one()->bonus_amount;
			}else{
				$latest_order_referralpoints =0;
			}
			
		}else{
			$latest_order_amount = 0;
			$latest_order_id = 0;
			$latest_order_loyaltypoints = 0;
			$latest_order_referralpoints =0;
		}
		//Latest Order Part ENDS
		
		//Approval Pending Orders Starts
			if(orders::find()->andFilterWhere(['company_id'=>$company,'branch_id'=>$branch,'order_approved'=>'no'])->exists()){
				$orders_approval_pending_count = orders::find()->andFilterWhere(['company_id'=>$company,'branch_id'=>$branch,'order_approved'=>'no'])->count();
			}else{
				$orders_approval_pending_count = 0;
			}
		//Approval Pending Orders Ends
			
		if($request->isAjax){
			
			//print_r($customers_this_month);exit;
			return $this->renderAjax('dashboard',[
				'customers_count'=>$customers_count,
				'orders_count'=>$orders_count,
				'order_total_amount'=>$order_total_amount,
				'order_total_amount_this_month'=>(int)$order_total_amount_this_month['total_amount'],
				'customers_this_month'=>$customers_this_month['customer_count'],
				'newcustomers_for_chart'=>$newcustomers_for_chart,
				'sales_for_chart'=>$sales_for_chart,
				'visitedcustomers_for_chart'=>$visitedcustomers_for_chart,
				'order_total_amount_today'=>(int)$order_total_amount_today['total_amount'],
				'latest_order_amount'=>(int)$latest_order_amount,
				'latest_order_id'=>$latest_order_id,
				'expiry_alert_msg'=>isset($expiry_alert_msg)? $expiry_alert_msg:false,
				'latest_order_loyaltypoints'=>(int)$latest_order_loyaltypoints,
				'latest_order_referralpoints'=>(int)$latest_order_referralpoints,
				'total_loyaltybonus_today'=>isset($total_loyaltybonus_today)? $total_loyaltybonus_today:0,
				'orders_approval_pending_count'=>$orders_approval_pending_count,
			]);
		}else{
			
			return $this->render('dashboard',[
				'customers_count'=>$customers_count,
				'orders_count'=>$orders_count,
				'order_total_amount'=>$order_total_amount,
				'order_total_amount_this_month'=>(int)$order_total_amount_this_month['total_amount'],
				'customers_this_month'=>$customers_this_month['customer_count'],
				'newcustomers_for_chart'=>$newcustomers_for_chart,
				'sales_for_chart'=>$sales_for_chart,
				'visitedcustomers_for_chart'=>$visitedcustomers_for_chart,
				'order_total_amount_today'=>(int)$order_total_amount_today['total_amount'],
				'latest_order_amount'=>(int)$latest_order_amount,
				'latest_order_id'=>$latest_order_id,
				'expiry_alert_msg'=>isset($expiry_alert_msg)? $expiry_alert_msg:false,
				'latest_order_loyaltypoints'=>(int)$latest_order_loyaltypoints,
				'latest_order_referralpoints'=>(int)$latest_order_referralpoints,
				'total_loyaltybonus_today'=>isset($total_loyaltybonus_today)? $total_loyaltybonus_today:0,
				'orders_approval_pending_count'=>$orders_approval_pending_count,
			]);
		}
		
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin() {

        $this->layout = "loginLayout";
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            if(!Yii::$app->user->can('Admin')){
				$session = yii::$app->session;
				$session->open();
				
				$session['company.company_id'] = yii::$app->user->identity->company_id;
				if(yii::$app->user->identity->branch_id != '' || yii::$app->user->identity->branch_id != NULL){
					$session['company.branch_id'] = yii::$app->user->identity->branch_id;
				}
			}
			
            return $this->goHome();
            
        } else {
            return $this->render('login', [
                        'model' => $model,
            ]);
        }
    }
	
	public function actionSearch(){
		$request = Yii::$app->request;
		$session = Yii::$app->session;
		
		$company = $session['company.company_id'];
		
		if($request->isAjax){
			if($request->isPost){
				$searchitems = $request->post('searchitems');
				
				if(CompanyCustomers::find()->where(['company_id'=>$company])->joinWith('cust')
					->andFilterWhere(['or',['like', 'customer_number', $searchitems],['like', 'name', $searchitems]])->exists()){
					$customers = CompanyCustomers::find()->where(['company_id'=>$company])->joinWith('cust')
					->andFilterWhere(['or',['like', 'customer_number', $searchitems],['like', 'name', $searchitems]])->all();
				}else{
					$customers = [];
				}
				if(Orders::find()->where(['company_id'=>$company])->andWhere(['like', 'session_nos', $searchitems])->exists()){
					$orders = Orders::find()->where(['company_id'=>$company])->andWhere(['like', 'session_nos', $searchitems])->all();
				}else{
					$orders = [];
				}
				
				return $this->renderAjax('searchresults',[
					"customers" => $customers, 
					"orders" => $orders, 
				]);
			}
			
		}
	}
	
	public function actionMaintainance(){
	     $this->layout = "loginLayout";
		return $this->render('maintainance',[
                        
        ]);
	}

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout() {
        Yii::$app->user->logout();

        return $this->goHome();
    }
    
    
    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset() {
        $this->layout = "loginLayout";
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
            }
        }

        return $this->render('requestPasswordResetToken', [
                    'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token) {
        $this->layout = "loginLayout";
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password was saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
                    'model' => $model,
        ]);
    }

    /**
     * User Profile.
     *
     */
    public function actionProfile() {
        $model = User::findIdentity(Yii::$app->user->identity->id);

        return $this->render('profile', [
                    'model' => $model,
        ]);
    }
    public function actionProfileEdit() {
        $model = User::findIdentity(Yii::$app->user->identity->id);
		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			
			if(UploadedFile::getInstance($model,'profilepic')){
						$path = Yii::getAlias('@frontend').'/web/uploads/profilepic/';
						BaseFileHelper::createDirectory($path,0777,false);
						$model->profilepic = UploadedFile::getInstance($model,'profilepic');
						$model->profilepic->saveAs(Yii::getAlias($path.$model->username.'.'.$model->profilepic->extension));
						$model->profilepic = $model->username.'.'.$model->profilepic->extension;
			}
			$model->save();
			return $this->redirect(['profile']);
		} else{
			return $this->render('profileedit', [
                    'model' => $model,
			]);
		}
        
    }

    public function actionChangepassword() {
        $user = User::findIdentity(Yii::$app->user->identity->id);
        //print_r($user);exit;
        $model = new ChangepasswordForm();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                //print_r($model);
                //echo $model->newpassword;exit;
                $user->password_hash = Yii::$app->security->generatePasswordHash($model->newpassword);

                $user->save();
                //print_r($user->errors);exit;
                Yii::$app->session->setFlash('danger', 'New password was saved.');
                return $this->goHome();
            } else {
                return $this->render('changepass', [
                            'model' => $model,
                ]);
            }
        } else {
            return $this->render('changepass', [
                        'model' => $model,
            ]);
        }
    }

    public function actionGetuserlocation() {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = 'json';
            if (!empty($_POST['latitude']) && !empty($_POST['longitude'])) {
                //Send request and receive json data by latitude and longitude
                $url = 'http://maps.googleapis.com/maps/api/geocode/json?latlng=' . trim($_POST['latitude']) . ',' . trim($_POST['longitude']) . '&sensor=false';
                $json = @file_get_contents($url);
                $data = json_decode($json);
                $status = $data->status;
                if ($status == "OK") {
                    //Get address from json data
                    $location['data'] = $data->results[0];
                    $location['address1'] = $data->results[0]->address_components[0]->short_name . ' ,' . $data->results[0]->address_components[1]->short_name;
                    $location['address2'] = $data->results[0]->address_components[2]->short_name;
                    $location['city'] = $data->results[0]->address_components[4]->short_name;
                    $location['pin'] = $data->results[0]->address_components[7]->short_name;
                    $location['place_id'] = $data->results[0]->place_id;
                    $location['status'] = 'true';
                } else {
                    $location['status'] = 'false';
                }
                //Print address 
                return $location;
            }
        }
    }

    public function actionGetcontrollersandactions() {
        $controllerlist = [];
        $handle = opendir(Yii::getAlias('@backend/controllers'));
        if ($handle) {
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != ".." && substr($file, strrpos($file, '.') - 10) == 'Controller.php') {
                    $controllerlist[] = $file;
                }
            }
            closedir($handle);
        }
        asort($controllerlist);
        $fulllist = [];
		$permission_names = [];
        foreach ($controllerlist as $controller):
            $handle = fopen(Yii::getAlias('@backend/controllers/') . $controller, "r");
            if ($handle) {
                while (($line = fgets($handle)) !== false) {
                    if (preg_match('/public function action(.*?)\(/', $line, $display)):
                        if (strlen($display[1]) > 2):
                            $fulllist[substr($controller, 0, -4)][] = strtolower($display[1]);
							$permission_names[substr($controller, 0, -4).'_'.strtolower($display[1])] = substr($controller, 0, -4).'_'.strtolower($display[1]);
                        endif;
                    endif;
                }
            }
            fclose($handle);
        endforeach;
		
		
		
        return $permission_names;
    }

}
