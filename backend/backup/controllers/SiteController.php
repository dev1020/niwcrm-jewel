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

use yii\helpers\BaseFileHelper;
use yii\data\ActiveDataProvider;
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
                        'actions' => ['login', 'request-password-reset', 'error', 'list'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'lockscreen','index', 'profile', 'changepassword', 'getcontrollersandactions', 'getuserlocation', 'settings', 'settings-add-attributes','settings-delete-attributes'],
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
	
    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex() {
		$request = Yii::$app->request;
		
		$customers_count = Customers::find()->count();
		$orders_count = Orders::find()->count();
		$services_count = Services::find()->count();
		
		$order_total_amount = Orders::find()->sum('total_amount');

		$order_total_due = Orders::find()->sum('due_amount');
		$order_total_amount_cleared = $order_total_amount-$order_total_due;
		
		$connection = Yii::$app->getDb();
		$command = $connection->createCommand("SELECT SUM(total_amount) as total_amount FROM `orders` WHERE DATE_FORMAT(order_date, '%y-%m') = DATE_FORMAT(NOW(), '%y-%m')");
		$order_total_amount_this_month = $command->queryOne();
		// today's Sale
		$command = $connection->createCommand("SELECT SUM(total_amount) as total_amount FROM `orders` WHERE DATE_FORMAT(order_date, '%y-%m-%d') = DATE_FORMAT(NOW(), '%y-%m-%d')");
		$order_total_amount_today = $command->queryOne();
		//for new customers
		$command1 = $connection->createCommand("SELECT COUNT(*) as customer_count FROM `customers` WHERE DATE_FORMAT(created_date, '%y-%m') = DATE_FORMAT(NOW(), '%y-%m')");
		$customers_this_month = $command1->queryOne();
		
		$newcustomers_for_chart = [];
		for($i=1;$i<=12;$i++){
			$commandnc = $connection->createCommand("SELECT COUNT(*) as nc_count FROM `customers` WHERE MONTH(created_date) = $i AND DATE_FORMAT(created_date, '%y') = DATE_FORMAT(NOW(), '%y')");
			$high_chart_ncustomers = $commandnc->queryOne();
			$newcustomers_for_chart[] = (int)$high_chart_ncustomers['nc_count'];
		}
		$sales_for_chart = [];
		for($i=1;$i<=12;$i++){
			$commandnc = $connection->createCommand("SELECT SUM(total_amount) as total_amount FROM `orders` WHERE MONTH(order_date) = $i AND DATE_FORMAT(order_date, '%y') = DATE_FORMAT(NOW(), '%y')");
			$high_chart_sales = $commandnc->queryOne();
			$sales_for_chart[] = (int)$high_chart_sales['total_amount'];
		}
		
		$visitedcustomers_for_chart = [];
		for($i=1;$i<=12;$i++){
			$commandnc = $connection->createCommand("SELECT COUNT(*) as vc_count FROM `orders` WHERE MONTH(order_date) = $i AND DATE_FORMAT(order_date, '%y') = DATE_FORMAT(NOW(), '%y')");
			$high_chart_visitedcustomers = $commandnc->queryOne();
			$visitedcustomers_for_chart[] = (int)$high_chart_visitedcustomers['vc_count'];
		}
			
		if($request->isAjax){
			
			//print_r($customers_this_month);exit;
			return $this->renderAjax('dashboard',[
				'customers_count'=>$customers_count,
				'orders_count'=>$orders_count,
				'order_total_amount'=>$order_total_amount,
				'order_total_due'=>(int)$order_total_due,
				'order_total_amount_cleared'=>(int)$order_total_amount_cleared,
				'services_count'=>$services_count,
				'order_total_amount_this_month'=>(int)$order_total_amount_this_month['total_amount'],
				'customers_this_month'=>$customers_this_month['customer_count'],
				'newcustomers_for_chart'=>$newcustomers_for_chart,
				'sales_for_chart'=>$sales_for_chart,
				'visitedcustomers_for_chart'=>$visitedcustomers_for_chart,
				'order_total_amount_today'=>(int)$order_total_amount_today['total_amount'],
			
			]);
		}else{
			
			//var_dump($sales_for_chart);
			//var_dump($customers_for_chart1);
			//exit;
			return $this->render('dashboard',[
				'customers_count'=>$customers_count,
				'orders_count'=>$orders_count,
				'order_total_amount'=>$order_total_amount,
				'order_total_due'=>(int)$order_total_due,
				'order_total_amount_cleared'=>(int)$order_total_amount_cleared,
				'services_count'=>$services_count,
				'order_total_amount_this_month'=>(int)$order_total_amount_this_month['total_amount'],
				'customers_this_month'=>$customers_this_month['customer_count'],
				'newcustomers_for_chart'=>$newcustomers_for_chart,
				'sales_for_chart'=>$sales_for_chart,
				'visitedcustomers_for_chart'=>$visitedcustomers_for_chart,
				'order_total_amount_today'=>(int)$order_total_amount_today['total_amount'],
			
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
            
                return $this->goHome();
            
        } else {
            return $this->render('login', [
                        'model' => $model,
            ]);
        }
    }
	
	public function actionLockscreen(){
		$this->layout = "loginLayout";
		return $this->render('lockscreen', [
                        
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
