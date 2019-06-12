<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use \yii\web\Response;
use \yii\helpers\Html;

use backend\models\Customers;
use backend\models\CustomersSearch;
use backend\models\CustomersBonuses;
use backend\models\CustomersImportantDates;

use backend\models\Orders;
use backend\models\OrdersDetails;
use backend\models\CustomersServices;
use backend\models\Services;
use common\models\User;


use common\components\CustomerBonusesComp;

/**
 * Site controller
 */
class ProfileController extends Controller
{
	public $layout = 'profile';
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    
	public function actionIndex()
    {   
	
        $request = Yii::$app->request;
		
		if(Customers::find()->where(['user_id'=>yii::$app->user->identity->id])->exists()){
			$model = Customers::find()->where(['user_id'=>yii::$app->user->identity->id])->one();
			$id = $model->id;
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
						'content'=>$this->renderAjax('profiledetails', [
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
				return $this->render('profiledetails', [
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
		}else{
			throw new \yii\web\ForbiddenHttpException;
		}
		
		
    }
	public function actionView()
    {	
		$user = new User;
		return $this->render('profileview', [
				'model' => $user->findIdentity(Yii::$app->user->identity->id),
			]);
	}
	
	public function actionPointsDetails()
    {	
		if(Customers::find()->where(['user_id'=>yii::$app->user->identity->id])->exists()){
			$model = Customers::find()->where(['user_id'=>yii::$app->user->identity->id])->one();
			$id = $model->id;
			$bonuses = CustomersBonuses::find()->where(['cust_id'=>$id])->all();
			
			$bonusgenerated = new CustomerBonusesComp;
				$bonus = $bonusgenerated->checkBonuses($id);
				$availablebonus = $bonus['available'];
			return $this->render('pointspage', [
				'bonuses' => $bonuses,
				'availablebonus' => $availablebonus,
			]);
		}
	}
	public function actionReferrals()
    {	
		if(Customers::find()->where(['user_id'=>yii::$app->user->identity->id])->exists()){
			$model = Customers::find()->where(['user_id'=>yii::$app->user->identity->id])->one();
			$id = $model->id;
			// FOR THE REFERRALS STARTS
			$queryreferrals = new \yii\db\Query;    
				$queryreferrals->select('customers.id,customers.name,customers.contact,customers.gender,SUM(bonus_amount) as bonus')
					->from('customers')
					
					->leftJoin('orders','orders.cust_id=customers.id')
					->leftJoin('customers_bonuses','customers_bonuses.order_id=orders.id')
					->where(['introducer_customer_id'=>$id,'customers_bonuses.type'=>'referral'])
					->orderBy('customers.id')
					->groupBy('customers.id');
				$command = $queryreferrals->createCommand();
				
				//echo $command->getRawSql();
				//exit;
				$referrals = $command->queryAll();
			
			// FOR THE REFERRALS ENDS
			$referrals_count = Customers::find()->where(['introducer_customer_id'=>$id])->count();
			return $this->render('referrals', [
				'referrals' => $referrals,
				'referrals_count' => $referrals_count,
			]);
		}
	}
	
	public function actionOrdersView($id)
    {   
        $request = Yii::$app->request;
		$model = Orders::findOne($id);
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                    'title'=> "Order #".$id,
                    'content'=>$this->renderAjax('order-view', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn bg-purple pull-right','data-dismiss'=>"modal"])
                ];    
        }else{
            return $this->render('order-view', [
                'model' => $model,
            ]);
        }
    }
	public function actionOrdersPriceBreakup($id)
    {   
        $request = Yii::$app->request;
		$model = Orders::findOne($id);
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                    'title'=> "Order #".$id,
                    'content'=>$this->renderAjax('order-price-breakup', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn bg-purple pull-right','data-dismiss'=>"modal"])
                ];    
        }else{
            return $this->render('order-price-breakup', [
                'model' => $model,
            ]);
        }
    }
	
	public function actionOrders()
	{
		if(Customers::find()->where(['user_id'=>yii::$app->user->identity->id])->exists()){
			$model = Customers::find()->where(['user_id'=>yii::$app->user->identity->id])->one();
			$id = $model->id;
			
			$orders = Orders::find()->where(['cust_id'=>$id])->orderBy(['order_date'=>SORT_DESC])->all();
			
			return $this->render('orders',[
				'orders'=>$orders,
			]);
			
		}
	}
    
}
