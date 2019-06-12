<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model backend\models\Companies */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="companies-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'company_name')->textInput(['maxlength' => true]) ?>
	
	<?= $form->field($model, 'company_contact')->textInput(['maxlength' => true]) ?>
	
	
	

    <?= $form->field($model, 'company_address')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'sms_quota')->textInput(['maxlength' => true]) ?>
	
	<?= $form->field($model, 'activated_upto')->widget(DatePicker::classname(), [
		'options' => ['placeholder' => 'Enter Expiry date ...'],
		'readonly'=>true,
		'pluginOptions' => [
			'autoclose'=>true,
			'format' => 'yyyy-mm-dd',
			
			]
		]);
	?>

  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
