<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ResetPasswordForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Modal;
use johnitvn\ajaxcrud\CrudAsset; 
use yii\widgets\Pjax;

$this->title = 'Settings';
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);
?>
<?php Pjax::begin(['options'=>['id'=>'crud-datatable-pjax','data-pjax-container'=>'']]);?>
<div class="site-reset-password" style="background:#ffffff;padding:10px">
	<div class="container" >
		
		<div class="row">
			<div class="col-xs-12 col-lg-2 col-lg-push-10" style="margin-bottom:15px">
			
				<?= Html::a('<i class="glyphicon glyphicon-plus"></i> Add Setings Attribute', ['settings-add-attributes'],
                    ['role'=>'modal-remote','title'=> 'Add new Attributes','class'=>'btn btn-success'])?>
			</div>
			<div class="col-xs-12 col-lg-10 col-lg-pull-2">
				
				<?php $form = ActiveForm::begin(['id' => 'reset-password-form','options'=>['class'=>'form-horizontal','enctype'=>'multipart/form-data']]); ?>
					
					<?= $output ?>
					<hr style="border-top: 2px solid #6f4b4b;">
					<div class="col-md-offset-4 col-md-4">
						<div class="form-group text-center">
							<?= Html::submitButton('Save', ['class' =>'btn btn-success btn-block']) ?>
						</div>
					</div>
					
				<?php ActiveForm::end(); ?>
			</div>
			
		</div>
	</div>
</div>
<?php Pjax::end();?>

<?php Modal::begin([
    "id"=>"ajaxCrudModal",
    "footer"=>"",// always need it for jquery plugin
])?>
<?php Modal::end(); ?>