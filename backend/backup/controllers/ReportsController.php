<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use backend\models\BpostsSearch;
use backend\models\OrderSearch;
use common\models\User;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

use backend\models\Bussiness;
use backend\models\BussinessSearch;


/**
 * Site controller
 */
class ReportsController extends Controller
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
	
	public function actionIndex()
    {    
		$searchModel = new BpostsSearch();
		if(Yii::$app->user->can('Admin')){
			//echo Yii::$app->user->identity->partners_id; exit;
			$usersdata = ArrayHelper::map(User::find()->where(['<>','usertype','user'])->orderBy('id')->asArray()->all(), 'id', 'username');
			
				 $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		}elseif(Yii::$app->user->can('Partners')){
			
			//$usersofpartner = User::find()->where(['partners_id'=>Yii::$app->user->identity->partners_id])->asArray()->all(); 
			$usersdata = ArrayHelper::map(User::find()->where(['partners_id'=>Yii::$app->user->identity->partners_id])->orderBy('id')->asArray()->all(), 'id', 'username'); 
			$users = array_keys($usersdata);
			
			$dataProvider = $searchModel->search(Yii::$app->request->queryParams,$users);
		}elseif(Yii::$app->user->can('Field Executive')){
			$usersdata = ArrayHelper::map(User::find()->where(['id'=>Yii::$app->user->identity->id])->orderBy('id')->asArray()->all(), 'id', 'username');
			$users = array_keys($usersdata);
			$dataProvider = $searchModel->search(Yii::$app->request->queryParams,$users);
		}else{
			Yii::$app->user->logout();
			throw new ForbiddenHttpException;
		}
		/*echo "<pre>";
		$roles = \Yii::$app->authManager->getRolesByUser(Yii::$app->user->identity->id);
		print_r($roles);die();
        */
		//$searchModel = new BpostsSearch();
        //$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'usersdata' => $usersdata,
        ]);
		
    }
	
	public function actionOrders()
    {    
		$searchModel = new OrderSearch();
		if(Yii::$app->user->can('Admin')){
			//echo Yii::$app->user->identity->partners_id; exit;
			$usersdata = ArrayHelper::map(User::find()->where(['<>','usertype','user'])->orderBy('id')->asArray()->all(), 'id', 'username');
			//print_r($usersdata);exit;
				 $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		}elseif(Yii::$app->user->can('Partners')){
			
			//$usersofpartner = User::find()->where(['partners_id'=>Yii::$app->user->identity->partners_id])->asArray()->all(); 
			$usersdata = ArrayHelper::map(User::find()->where(['partners_id'=>Yii::$app->user->identity->partners_id])->orderBy('id')->asArray()->all(), 'id', 'username'); 
			$users = array_keys($usersdata);
			
			$dataProvider = $searchModel->search(Yii::$app->request->queryParams,$users);
		}elseif(Yii::$app->user->can('Field Executive')){
			
			$usersdata = ArrayHelper::map(User::find()->where(['id'=>Yii::$app->user->identity->id])->orderBy('id')->asArray()->all(), 'id', 'username');
			$users = array_keys($usersdata);
			$dataProvider = $searchModel->search(Yii::$app->request->queryParams,$users);
		}else{
				Yii::$app->user->logout();
				throw new ForbiddenHttpException;
		}
		
		
		/*echo "<pre>";
		$roles = \Yii::$app->authManager->getRolesByUser(Yii::$app->user->identity->id);
		print_r($roles);die();
        */
		//$searchModel = new BpostsSearch();
        //$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('order', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'usersdata' => $usersdata,
        ]);
		
    }
	public function actionFilterwiseListing($mode=null)
	{
		if(isset($mode)){
			if($mode == 'category'){
				$searchModel = new BussinessSearch();
				$dataProvider = $searchModel->categorywise(Yii::$app->request->queryParams);
				
				return $this->render('filterlisting/filterwiselisting',[
					'searchModel' => $searchModel,
					'dataProvider' => $dataProvider,
					'mode' =>$mode,
				]);
			}else if($mode == 'location'){
				$searchModel = new BussinessSearch();
				$dataProvider = $searchModel->locationwise(Yii::$app->request->queryParams);
				
				return $this->render('filterlisting/filterwiselisting',[
					'searchModel' => $searchModel,
					'dataProvider' => $dataProvider,
					'mode' =>$mode,
				]);
			}else if($mode == 'package'){
				
			}else{
				throw new \yii\web\NotFoundHttpException();
			}
		
		}else{
			$searchModel = new BussinessSearch();
			$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
			
			return $this->render('filterlisting/filterwiselisting',[
				'searchModel' => $searchModel,
				'dataProvider' => $dataProvider,
			]);
		}
		
	}

}
