<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use backend\models\ImportantDateTypes;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $model backend\models\CustomersImportantDates */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="customers-important-dates-form">

    <?php $form = ActiveForm::begin(); ?>
	<?php 
		if(!isset($model->cust_id)){
			echo $form->field($model, 'cust_id')->textInput();
		}else{
			echo '<input type="hidden" value="redirect" name="redirect" >';  // for redirection
		}
	?>
   
	<?= $form->field($model, 'imp_date')->widget(DatePicker::classname(), [
    'options' => ['placeholder' => 'Enter date ...'],
	'readonly'=>true,
    'pluginOptions' => [
        'autoclose'=>true,
        'format' => 'yyyy-mm-dd'
    ]
]);?>
	<?= $form->field($model, 'type')->widget(Select2::classname(), [
							'data' => ArrayHelper::Map(ImportantDateTypes::find()->asArray()->all(), 'id', 'type_name'),
							'language' => 'en',
							'options' => ['multiple' =>false, 'placeholder' => 'Select Types ...'],
							'pluginOptions' => [
								'allowClear' => true,
								'tabindex' => 'off',
							],
						]);
					?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
