<?php

namespace backend\controllers;

use Yii;
use backend\models\Companies;
use common\models\User;
use common\models\UserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use common\components\StatsQueryComp;


/**
 * CompanySettingsController implements the CRUD actions for CompanySettings model.
 */
class CompanyUsersController extends Controller
{
    /**
     * @inheritdoc
     */
	public $company_id;
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
	
	public function init()
	{
		$session = Yii::$app->session;
		$this->company_id = $session['company.company_id'];
		if((!yii::$app->user->can('Admin')) && (!yii::$app->user->can('manager'))){
			throw new \yii\web\ForbiddenHttpException;
		}
	}
	
	public function actionIndex()
    {    
        $searchModel = new UserSearch();
		$searchModel->company_id = $this->company_id;
		
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'company'=>Companies::findOne($this->company_id),
        ]);
    }
	
	public function actionStats($id)
    {    
	
		$request = Yii::$app->request;
		$user = User::findOne($id);
		$company = $this->company_id;
		
		$statsQuery = new StatsQueryComp();
		$order_total_amount_today 	= $statsQuery->ordersValueToday($company,'',$id);
		$orders_today           	= $statsQuery->ordersCountToday($company,'',$id);
		$orders_pending_today   	= $statsQuery->ordersPendingToday($company,'',$id);
		$orders_cancelled_today 	= $statsQuery->ordersCancelledToday($company,'',$id);
		//var_dump($orders_today);
		//exit;
		
		
		$connection = Yii::$app->getDb();
		$sales_for_chart = [];
		for($i=1;$i<=12;$i++){
			$query = "SELECT SUM(total_amount) as total_amount FROM `orders` WHERE MONTH(order_date) = $i AND DATE_FORMAT(order_date, '%y') = DATE_FORMAT(NOW(), '%y')";
			if($company!='' || $company!=NULL){
			$query .= "AND company_id = $company" ;
			}
			$commandnc = $connection->createCommand($query);
			$high_chart_sales = $commandnc->queryOne();
			$sales_for_chart[] = (int)$high_chart_sales['total_amount'];
		}
		
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                    'title'=> "Companies #".$id,
                    'content'=>$this->renderAjax('stats', [
                        //'model' => $this->findModel($id),
                    ]),
					'size'=>'large',
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Edit',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                ];    
        }else{
            return $this->render('stats', [
                'user' => $user,
                'order_total_amount_today' => $order_total_amount_today,
				'sales_for_chart'=>$sales_for_chart,
				'orders_today'=>$orders_today,
				'orders_pending_today'=>$orders_pending_today,
				'orders_cancelled_today'=>$orders_cancelled_today,
            ]);
        }
    }
	
    
}
