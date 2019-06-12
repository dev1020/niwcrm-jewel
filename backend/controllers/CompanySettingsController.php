<?php

namespace backend\controllers;

use Yii;
use backend\models\CompanySettings;
use backend\models\CompanySettingsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;

use yii\web\UploadedFile;
use yii\helpers\BaseFileHelper;

/**
 * CompanySettingsController implements the CRUD actions for CompanySettings model.
 */
class CompanySettingsController extends Controller
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
	public function init(){
		$session = Yii::$app->session;
		$company = $session['company.company_id'];
		$this->company_id = $company;
		if(!$company && !yii::$app->user->can('Admin')){
			throw new \yii\web\NotFoundHttpException();
		}
	}
    /**
     * Lists all CompanySettings models.
     * @return mixed
     */
    
	
	public function actionList()
    {    
		if(yii::$app->user->can('Admin')){
			$searchModel = new CompanySettingsSearch();
			$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

			return $this->render('listall', [
				'searchModel' => $searchModel,
				'dataProvider' => $dataProvider,
			]);
		}else{
			throw new \yii\web\ForbiddenHttpException();
		}
        
    }


    /**
     * Displays a single CompanySettings model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {   
		$request = Yii::$app->request;
		if(!yii::$app->user->can('Admin') && ($this->company_id != $id)){
			throw new \yii\web\ForbiddenHttpException();
		}else{
			if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                    'title'=> "CompanySettings #".$id,
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
        
        
    }

    /**
     * Creates a new CompanySettings model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new CompanySettings();  
		if(yii::$app->user->can('Admin')){
			if($request->isAjax){
            /*
            *   Process for ajax request
            */
				Yii::$app->response->format = Response::FORMAT_JSON;
				if($request->isGet){
					return [
						'title'=> "Create new CompanySettings",
						'content'=>$this->renderAjax('create', [
							'model' => $model,
						]),
						'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
									Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
			
					];         
				}else if($model->load($request->post()) && $model->save()){
					return [
						'forceReload'=>'#crud-datatable-pjax',
						'title'=> "Create new CompanySettings",
						'content'=>'<span class="text-success">Create CompanySettings success</span>',
						'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
								Html::a('Create More',['create'],['class'=>'btn btn-primary','role'=>'modal-remote'])
			
					];         
				}else{           
					return [
						'title'=> "Create new CompanySettings",
						'content'=>$this->renderAjax('create', [
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
					return $this->render('create', [
						'model' => $model,
					]);
				}
			}
		}else{
			throw new \yii\web\ForbiddenHttpException();
		}
        
       
    }

    /**
     * Updates an existing CompanySettings model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
		$model = $this->findModel($id); 
		if(!yii::$app->user->can('Admin') && ($this->company_id != $model->company_id)){
			throw new \yii\web\ForbiddenHttpException();
		}else{
			
			if($request->isAjax){
				/*
				*   Process for ajax request
				*/
				Yii::$app->response->format = Response::FORMAT_JSON;
				if($request->isGet){
					return [
						'title'=> "Update CompanySettings #".$id,
						'content'=>$this->renderAjax('update', [
							'model' => $model,
						]),
						'size'=>'large',
						'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
									Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
					];         
				}else if($model->load($request->post()) && $model->save()){
					return [
						'forceReload'=>'#crud-datatable-pjax',
						'title'=> "CompanySettings #".$id,
						'content'=>$this->renderAjax('view', [
							'model' => $model,
						]),
						'size'=>'large',
						'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
								Html::a('Edit',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
					];    
				}else{
					 return [
						'title'=> "Update CompanySettings #".$id,
						'content'=>$this->renderAjax('update', [
							'model' => $model,
						]),
						'size'=>'large',
						'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
									Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
					];        
				}
			}else{
				/*
				*   Process for non-ajax request
				*/
				if ($model->load($request->post()) ) {
					
					if(UploadedFile::getInstance($model,'site_logo')){
						$path = Yii::getAlias('@backend').'/web/uploads/companies/';
						BaseFileHelper::createDirectory($path,0777,false);
						$model->site_logo = UploadedFile::getInstance($model,'site_logo');
						$model->site_logo->saveAs(Yii::getAlias($path.$model->company->company_slug.'.'.$model->site_logo->extension));
						$model->site_logo = $model->company->company_slug.'.'.$model->site_logo->extension;
						
					}
					$model->save();
					return $this->redirect(['view', 'id' => $id]);
				} else {
					return $this->render('update', [
						'model' => $model,
					]);
				}
			}
		}
        
    }

    /**
     * Delete an existing CompanySettings model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $request = Yii::$app->request;
		if(!yii::$app->user->can('Admin') && ($this->company_id != $id)){
			throw new \yii\web\ForbiddenHttpException();
		}else{
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
    }

     /**
     * Delete multiple existing CompanySettings model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionBulkDelete()
    {        
        $request = Yii::$app->request;
		if(!yii::$app->user->can('Admin') && ($this->company_id != $id)){
			throw new \yii\web\ForbiddenHttpException();
		}else{
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
    }

    /**
     * Finds the CompanySettings model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CompanySettings the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CompanySettings::find()->where(['company_id'=>$id])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
