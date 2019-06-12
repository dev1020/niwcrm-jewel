<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use backend\models\Customers;
use yii\web\JsExpression;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model backend\models\Customers */
/* @var $form yii\widgets\ActiveForm */
?>
<style>
.image img{
	max-width:100%;
}
</style>
<div class="customers-form">

    <?php $form = ActiveForm::begin(); ?>

	<?= $form->errorSummary($model); ?>
	

    <?= $form->field($model, 'contact_number')->textInput(['maxlength' => true]) ?>
	
	

	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>

<?php $script = <<< JS
$(function(){
	$(".imgupload").change(function(){
    readURL(this);
	});
	function readURL(input){

		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function (e) {
				if(input.id=="customerpic"){
					$('#imagepreview').attr('src', e.target.result);
				}          
			}
			reader.readAsDataURL(input.files[0]);
		}
	}
});
JS;
$this->registerJs($script);
?>