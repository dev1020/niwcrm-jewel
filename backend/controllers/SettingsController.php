<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;

use yii\helpers\Url;
use yii\filters\VerbFilter;

use \yii\web\Response;

use yii\base\InvalidParamException;
use yii\web\ForbiddenHttpException;
use yii\web\BadRequestHttpException;

use backend\models\SettingsOptions;
use yii\Helpers\Html;
use yii\helpers\BaseFileHelper;
/**
 * Site controller
 */
class SettingsController extends Controller {

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
                    
                ],
            ],
        ];
    }
    public function actionSettingsAddAttributes() {
        $request = Yii::$app->request;
        $model = new SettingsOptions();
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Add new Attributes",
                    'content' => $this->renderAjax('formSettingsAttribute', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post())) {
				$model->save();
                return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "Create new Attributes",
                    'content'=>'<span class="text-success">Attributes Created successfully</span>',
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Create More',['create'],['class'=>'btn btn-primary','role'=>'modal-remote'])
        
                ];
            } else {
                return [
                    'title' => "Add new Attributes",
                    'content' => $this->renderAjax('formSettingsAttribute', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            }
        } else {
            
        }
    }
	
	public function actionSettingsDeleteAttributes($id) {
        $request = Yii::$app->request;
        $model = SettingsOptions::findOne($id);
		$model->delete();
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
			return [
				'forceClose'=>true,'forceReload'=>'#crud-datatable-pjax',
			];
        } else {
            
        }
    }

    public function actionIndex() {
        $request = Yii::$app->request;

        $model = SettingsOptions::find()->all();

        $output = '';
        foreach ($model as $modeldetails) {
            if ($modeldetails->settings_attribute_type == 'fileInput') {
                $output .= '<fieldset><legend>'. $modeldetails->settings_attribute_label .'<span class=" label bg-purple pull-right">'.$modeldetails->settings_attribute_name.'</span></legend>
										
											<div class="form-group">
                                                <div class="col-xs-12">
													<img style="max-width:200px; max-height:100px" src="'.($modeldetails->settings_attribute_value ? Url::to('@frontendimage'.'/settings/'.$modeldetails->settings_attribute_value) : Url::to('@frontendimage'.'/noimage.png')).'">
												</div>
                                                <div class="col-xs-11 ">
                                                        <input type="file" class="pull-right" name="' . $modeldetails->settings_attribute_name . '" id="' . $modeldetails->settings_attribute_name . '">
                                                </div>
												<div class="col-xs-1" style="padding:0">'.
													\yii\helpers\Html::a('<i class="fa fa-trash"></i> ', ['settings-delete-attributes','id'=>$modeldetails->id],
                    ['role'=>'modal-remote','title'=>'Delete', 'class'=>'btn btn-danger btn-sm',
                          'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                          'data-request-method'=>'post',
                          'data-toggle'=>'tooltip',
                          'data-confirm-title'=>'Are you sure?',
                          'data-confirm-message'=>'Are you sure want to delete this item']).'
												</div>
												
                                        </div></fieldset>';
            } else {
                $output .= '<fieldset><legend>'. $modeldetails->settings_attribute_label .'<span class="label bg-purple pull-right">'.$modeldetails->settings_attribute_name.'</span></legend> <div class="form-group">
                                                
                                                <div class="col-xs-11">
                                                        <input type="text" value="' . ($modeldetails->settings_attribute_value ? $modeldetails->settings_attribute_value : '') . '" class="form-control" name="' . $modeldetails->settings_attribute_name . '" id="' . $modeldetails->settings_attribute_name . '">
                                                </div>
												<div class="col-xs-1" style="padding:0">'.
													\yii\helpers\Html::a('<i class="fa fa-trash"></i> ', ['settings-delete-attributes','id'=>$modeldetails->id],
                    ['role'=>'modal-remote','title'=>'Delete', 'class'=>'btn btn-danger btn-sm',
                          'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                          'data-request-method'=>'post',
                          'data-toggle'=>'tooltip',
                          'data-confirm-title'=>'Are you sure?',
                          'data-confirm-message'=>'Are you sure want to delete this item']).'
												</div>
                            </div></fieldset>';
            }
        }


        if ($request->isAjax) {
			Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return $this->renderAjax('settingsForm', [
                            'output' => $output,
                ]);      
            }
        } else {
            if (Yii::$app->request->post()) {
				
				foreach($_FILES as $key=>$inputfiles){
					$model = SettingsOptions::find()->where(['settings_attribute_name' => $key])->One();
					$settings_attribute_value_old = $model->settings_attribute_value;
						if($inputfiles['tmp_name']){
							$pathimage = Yii::getAlias('@frontend').'/web/uploads/settings/';
							BaseFileHelper::createDirectory($pathimage,0777,false);
							$extension = explode(".",$inputfiles['name']);
							$fileExt = end($extension);  
							$newfilename = $model->settings_attribute_name .".".$fileExt;
							$uploadfile = $pathimage . $newfilename ;
							if(move_uploaded_file($inputfiles['tmp_name'], $uploadfile)){
								$model->settings_attribute_value = $newfilename;
							}
						}else{
							$model->settings_attribute_value = $settings_attribute_value_old;
						}
					$model->save();
				}
				
                $postvalues = Yii::$app->request->post();
                foreach ($postvalues as $key => $posts) {
                    if (SettingsOptions::find()->where(['settings_attribute_name' => $key])->exists()) {
                        $model = SettingsOptions::find()->where(['settings_attribute_name' => $key])->One();
                        //print_r($model);
                        $model->settings_attribute_value = htmlspecialchars($posts);
                        if (!$model->save()) {
                            echo 'Error In saving <strong>' . $key . '</strong>';
                        }
                    }
                }
				
                Yii::$app->session->setFlash('success', "Settings Saved successfully.");
                $this->refresh();
            } else {
                return $this->render('settingsForm', [
                            'output' => $output,
                ]);
            }
        }
    }
}
