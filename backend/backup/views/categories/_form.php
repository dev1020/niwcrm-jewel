<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use common\components\Multilevel;
use backend\models\Categories;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model backend\models\Categories */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="categories-form">

    <?php $form = ActiveForm::begin(['options'=>['enctype'=>'multipart/form-data']]); ?>
		
		<div class="col-lg-12" style="padding:0px">
			<div class="col-lg-6 " style="padding:0px">
				<div class="col-lg-6 " style="padding:0px">
					<?php if($model->category_pic){?>
						<img src="<?= Url::to('@frontendimage'.'/categorypic/'.$model->category_pic)?>" >
						
					<?php } else { ?>
						<img src="<?= Url::to('@frontendimage'.'/noimage.png')?>" >
					<?php } ?>
				</div>
				<div class="col-lg-6" style="padding:0px">
					<?= $form->field($model, 'category_pic')->fileInput(); ?>
				</div>
				<div class="col-lg-12" style="padding:0px">
					<?= $form->field($model, 'category_name')->textInput(['maxlength' => true,'onkeyup' => 'showsuggestion(this.value)']) ?>
				</div>
			</div>
			<div class="col-lg-6" style="padding:0px">
				<div class="col-lg-12" style="padding:0px" id="test"></div>
			</div>
		</div>
			
	
	

    
	
	
	<!--  use of multilevel Component -->	
	<?php $ml = new Multilevel();?>
	<?= $form->field($model, 'category_root')->widget(Select2::classname(), [
							'data' => $ml->makeDropDown(0,$model),
							'language' => 'en',
							'options' => ['multiple' =>false, 'placeholder' => 'Select categories ...'],
							'pluginOptions' => [
								'allowClear' => true,
								'tabindex' => 'off',
							],
						]);
					?>
	<!--  End of multilevel Component -->
	<?= $form->field($model, 'category_displayorder')->textInput(['maxlength' => true,]) ?>
    <?= $form->field($model, 'category_status')->dropDownList([ 'Active' => 'Active', 'Inactive' => 'Inactive', ]) ?>

	<?= $form->field($model, 'description')->textarea(['rows' => '6']) ?>
					
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
<script>
function showsuggestion(string){
	if(string.length>0){
		//alert(string);
	$.get("<?= Url::toRoute('/categories/suggestlist?')?>string="+string, 
			function(data){
				$("#test").html(data.content);
			});
	}
	
	
}
</script>
