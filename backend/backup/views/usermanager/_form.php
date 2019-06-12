<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use dosamigos\datepicker\DatePicker;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */
?>


	<div class="user-form">

		<?php $form = ActiveForm::begin(['options'=>['enctype'=>'multipart/form-data'],'enableAjaxValidation'=>true,'validationUrl' => Url::toRoute('usermanager/validation')]); ?>
				<div class="row">
					<div class="col-md-6 ">
					
					<?= $form->errorSummary($signupmodel); ?>
						<?= $form->field($signupmodel, 'first_name')->textInput(['autofocus' => true]) ?>

						<?= $form->field($signupmodel, 'last_name')->textInput() ?>

						<?= $form->field($signupmodel, 'username')->textInput() ?>
						
						<?= $form->field($signupmodel, 'password')->passwordInput() ?>

						<?= $form->field($signupmodel, 'contact_number')->textInput() ?>
						
						
						
						<?= $form->field($signupmodel, 'dob')->textInput(['placeholder'=>'For example 1990-01-31']) ?>
					</div>
					<div class="col-md-6 ">
						<?= $form->field($signupmodel, 'email') ?>					
						
						<?= $form->field($signupmodel, 'address')->textInput() ?>
						<?= $form->field($signupmodel, 'address2')->textInput() ?>
						
						<?= $form->field($signupmodel, 'pin')->textInput() ?>
						
						<?= $form->field($signupmodel, 'facebook_page')->textInput() ?>
						
						
						<?= $form->field($signupmodel, 'usertype')->dropDownList(['backenduser'=>'Backend Personnel','user'=>'Users']) ?>
						
						
					</div>
					
					<div class="col-md-12 text-center">
						<div class="form-group">
							<?= Html::submitButton($usermodel->isNewRecord ? 'Create' : 'Update', ['class' => $usermodel->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
						</div>
					</div>
				</div>
				
		

		<?php ActiveForm::end(); ?>

	
</div>
