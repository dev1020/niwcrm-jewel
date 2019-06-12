<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use backend\models\CompanyBranches;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model backend\models\Orders */
/* @var $form yii\widgets\ActiveForm */
?>
<style>
.modal-header{
	background:#D81B60;
	color:#fff;
}
</style>
<div class="row">
<div class="orders-form">
	
	<?php if (!Yii::$app->request->isAjax){ ?>
	<div class="container" style="background:#ffffff">
		<h2> Add New Sale </h2>
		<hr>
	<?php } ?>
		<?php $form = ActiveForm::begin(); ?>
		
		<div class="col-lg-12">
		<?= $form->field($model, 'order_date')->widget(DatePicker::classname(), [
		'options' => ['placeholder' => 'Enter Order date ...'],
		'readonly'=>true,
		'pluginOptions' => [
			'autoclose'=>true,
			'format' => 'yyyy-mm-dd',
			
			]
		]);?>
		</div>
		<div class="col-lg-6">
			<?= $form->field($model, 'customer_contact')->textInput(['type'=>'tel','id'=>'customercontact','class'=>'numeric form-control','maxlength'=>10])->label('Customer Contact without +91') ?>
		</div>
		<div class="col-lg-6">
			<?= $form->field($model, 'customer_name')->textInput(['id'=>'customername']) ?>
		</div>
		<div class="col-lg-6">
			<?= $form->field($model, 'session_nos')->textInput(['maxlength' => 12]) ?>
		</div>
		<div class="col-lg-6">
			<?= $form->field($model, 'total_amount')->textInput(['maxlength' => true,'type'=>'tel','class'=>'numeric form-control']) ?>
		</div>
	
		<div class="col-lg-6">
			<?= $form->field($model, 'weight')->textInput(['class'=>'numeric form-control'])  ?>
		</div>
		<?php if(yii::$app->user->can('manager') || yii::$app->user->can('Admin')){?>
			<div class="col-lg-6">
			<?= $form->field($model, 'branch_id')->widget(Select2::classname(), [
							'data' => ArrayHelper::map(CompanyBranches::find()->where(['company_id'=>$model->company_id])->orderBy(['branch_name' => SORT_ASC])->all(),'id','branch_name'),
							'language' => 'en',
							'options' => ['multiple' =>false, 'placeholder' => 'Select branch'],
							'pluginOptions' => [
								'allowClear' => true,
								'tabindex' => '-1',
							],
						])->label('Change Branch.');
					?>
			</div>

		<?php } ?>
		<div class="col-lg-12">
			<?= $form->field($model, 'order_note')->textarea(['rows' => '2'])  ?>
		</div>
	  
		<?php if (!Yii::$app->request->isAjax){ ?>
			<div class="form-group">
				<?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
			</div>
		<?php } ?>

		<?php ActiveForm::end(); ?>
    <?php if (!Yii::$app->request->isAjax){ ?>
	</div>
	<?php } ?>
</div>
</div>
<?php 
$getusername = Url::to(['orders/get-customername']);
$scripts = <<< JS
$(function(){
	$(".numeric").numeric();
	$('#customercontact').on('input',function(){
		var contact = $(this).val();
		if(contact.length ==10){
			$.post( '$getusername', { number:contact })
		  .done(function( data ) {
			  $('#customername').val(data.name);
		  })
		  .fail(function() {
			alert( "error" );
		  });
		  
		}
	});
})
JS;
$this->registerJs($scripts);
?>