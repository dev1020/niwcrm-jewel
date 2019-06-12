<?php

namespace backend\controllers;

use Yii;
use backend\models\Categories;
use backend\models\CategoriesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;

use yii\web\UploadedFile;
use yii\helpers\BaseFileHelper;

/**
 * CategoriesController implements the CRUD actions for Categories model.
 */
class CategoriesController extends Controller
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
	public function actionSuggestlist($string)
    {
		$request = Yii::$app->request;
		 if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
			
			$countbpostcategories = Categories::find()->where(['like', 'category_name', $string])->count();
			$categories = Categories::find()->where(['like', 'category_name', $string])->all();
			$content = '<ul style="list-style:none;padding:0px !important">';
			if($countbpostcategories > 0){
				
				foreach($categories as $categoriesname){
					//echo $categoriesname->category_name;
					$content .= '<li style="display:inline-block; margin:5px 2px"><span style="font-size:15px; padding-right:15px !important" class="tag label label-primary"><span>'.$categoriesname->category_name.'</span></span></li>';
				}
			}else{
				$content .= '<li><label style="color:red"> Nothing matches</label></li>';
			}
			
			$content .= '</ul>';
			$head = '<h4>Suggested Categories </h4><hr>';
			
			$body = '<div class="col-md-12" style="height:150px; overflow-y:scroll;padding:0px">'.$content.'</div>';
			$result = $head.$body;
			
			return [
				'content' => $result
			];
		 }else{
			
			$countbpostcategories = Categories::find()->where(['like', 'category_name', $string])->count();
			$categories = Categories::find()->where(['like', 'category_name', $string])->all();
			$content = '';
			if($countbpostcategories > 0){
				
				foreach($categories as $categoriesname){
					//echo $categoriesname->category_name;
					$content .= '<span style="font-size:15px; padding-right:25px !important" class="tag label label-primary"><span>'.$categoriesname->category_name.'</span></span>';
				}
			}else{
				$content .= '<label style="color:red"> Nothing matches</label>';
			}
			
			$head = '<h4>Suggested Categories </h4><hr>';
			
			$body = '<div class="col-md-12" style="height:150px; padding:15px 5px; overflow-y:scroll">'.$content.'</div>';
			$result = $head.$body;
			
		
			echo result;
			 
		 }
	}
    /**
     * Lists all Categories models.
     * @return mixed
     */
    public function actionIndex()
    {    
        $searchModel = new CategoriesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single Categories model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {   
        $request = Yii::$app->request;
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                    'title'=> "Categories #".$id,
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

    /**
     * Creates a new Categories model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new Categories();  

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Create new Categories",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            }else if($model->load($request->post())){
				if(!($model->category_root) ){
					$model->category_root = '0';
					
				}	
				$model->save();
					if(UploadedFile::getInstance($model,'category_pic')){
					$path = Yii::getAlias('@frontend').'/web/uploads/categorypic/';
					BaseFileHelper::createDirectory($path,0777,false);
					$model->category_pic = UploadedFile::getInstance($model,'category_pic');
					$model->category_pic->saveAs(Yii::getAlias($path.$model->category_slug.'.'.$model->category_pic->extension));
					$model->category_pic = $model->category_slug.'.'.$model->category_pic->extension;
				}
				
				$model->save();
				
                return [
					'forceClose'=>true,
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "Create new Categories",
                    'content'=>'<span class="text-success">Create Categories success</span>',
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Create More',['create'],['class'=>'btn btn-primary','role'=>'modal-remote'])
        
                ];         
            }else{           
                return [
                    'title'=> "Create new Categories",
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
            if ($model->load($request->post())) {
				if(!($model->category_root) ){
					$model->category_root = '0';
					
				}				
				if(UploadedFile::getInstance($model,'category_pic')){
					$path = Yii::getAlias('@frontend').'/web/uploads/categorypic/';
					BaseFileHelper::createDirectory($path,0777,false);
					$model->category_pic = UploadedFile::getInstance($model,'category_pic');
					$model->category_pic->saveAs(Yii::getAlias($path.$model->category_slug.'.'.$model->category_pic->extension));
					$model->category_pic = $model->category_slug.'.'.$model->category_pic->extension;
				}
				
                return $this->redirect(['view', 'id' => $model->category_id]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
       
    }

    /**
     * Updates an existing Categories model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);       
		$categorypic = $model->category_pic;
		
        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Update Categories #".$id,
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
                ];         
            }else if($model->load($request->post())){
				
				if(UploadedFile::getInstance($model,'category_pic')){
				$model->category_pic = UploadedFile::getInstance($model,'category_pic');
				$model->category_pic->saveAs(Yii::getAlias('@frontend').'/web/uploads/categorypic/'.$model->category_slug.'.'.$model->category_pic->extension);
				$model->category_pic = $model->category_slug.'.'.$model->category_pic->extension;
				}else{
					$model->category_pic = $categorypic ;
				}
				
				$model->save();
                return [
					'forceClose'=>true,
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "Categories #".$id,
                    'content'=>$this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Edit',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                ];    
            }else{
                 return [
                    'title'=> "Update Categories #".$id,
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
            if ($model->load($request->post())) {
				if(UploadedFile::getInstance($model,'category_pic')){
				$model->category_pic = UploadedFile::getInstance($model,'category_pic');
				$model->category_pic->saveAs(Yii::getAlias('@frontend').'/web/uploads/categorypic/'.$model->category_slug.'.'.$model->category_pic->extension);
				$model->category_pic = $model->category_slug.'.'.$model->category_pic->extension;
				}else{
					$model->category_pic = $categorypic ;
				}
				
				$model->save();
                return $this->redirect(['view', 'id' => $model->category_id]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Delete an existing Categories model.
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
     * Delete multiple existing Categories model.
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
     * Finds the Categories model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Categories the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Categories::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
