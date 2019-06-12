<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

use backend\models\Categories;

/* @var $this yii\web\View */
/* @var $model backend\models\Services */
/* @var $form yii\widgets\ActiveForm */
?>
<style>
.image img{
	max-width:100%;
}
</style>
<div class="services-form">

    <?php $form = ActiveForm::begin(); ?>
	<div class="col-lg-12">
		<div class="col-lg-6 image" >
							<img id="iconpreview" src="<?= ($model->services_icon)?(Url::to('@frontendimage'.'/services/'.$model->services_icon)):(Url::to('@frontendimage'.'/noimage.png'))?>" alt="your image" />
							
		</div>
		<div class="col-lg-6">
		 <?= $form->field($model, 'services_icon')->fileInput(['class'=>'imgupload','id'=>'serviceicon'])?>
		</div>
	</div>
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'name_hindi')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'name_local')->textInput(['maxlength' => true]) ?>
	<?= $form->field($model, 'fixedprice')->dropDownList([ 'yes' => 'Yes', 'no' => 'No', ]) ?>
    <?= $form->field($model, 'price')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'price_max')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'servicefor')->dropDownList([ 'female' => 'Female','male' => 'Male',  ]) ?>

   
	<?= $form->field($model, 'category_id')->widget(Select2::classname(), [
							'data' => ArrayHelper::map(Categories::find()->orderBy(['category_id' => SORT_ASC])->all(),'category_id','category_name'),
							'language' => 'en',
							'options' => ['multiple' =>false, 'placeholder' => 'Select user by phone number ...'],
							'pluginOptions' => [
								'allowClear' => true,
								'tabindex' => '-1',
							],
						]);
					?>

 
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
	function readURL(input) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function (e) {
				if(input.id=="serviceicon"){
					$('#iconpreview').attr('src', e.target.result);
				}           
			}
			reader.readAsDataURL(input.files[0]);
		}
	}	
});

JS;

$this->registerJs($script);
?>