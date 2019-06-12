<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use dosamigos\datepicker\DatePicker;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use backend\models\Companies;
use kartik\depdrop\DepDrop;

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
						
						<?= $form->field($signupmodel, 'usertype')->dropDownList(['backenduser'=>'Backend Personnel','user'=>'Users']) ?>
						
						<?= $form->field($signupmodel, 'company_id')->widget(Select2::classname(), [
							'data' => ArrayHelper::map(Companies::find()->orderBy(['id' => SORT_ASC])->all(),'id','company_name'),
							'language' => 'en',
							'options' => ['multiple' =>false, 'placeholder' => 'Select Company...'],
							'pluginOptions' => [
								'allowClear' => true,
								'tabindex' => '-1',
							],
						]);
						?>
						
						<?= $form->field($signupmodel, 'branch_id')->widget(DepDrop::classname(), [
						//'data'=> [6=>'Bank'],
						'options' => ['id'=>'subcatid','placeholder' => 'Select ...'],
						'type' => DepDrop::TYPE_SELECT2,
						'select2Options'=>[
							'pluginOptions'=>[
								'allowClear'=>true,
							],
							'hideSearch'=>true,
							
						],
						'pluginOptions'=>[
							'depends'=>['signupform-company_id'],
							'placeholder'=>'Select...',
							'url'=>Url::to(['getbranches']),
							'dataType' => 'json',
						]
					]);?>
					</div>
					
					<div class="col-md-12 text-center">
						<div class="form-group">
							<?= Html::submitButton($usermodel->isNewRecord ? 'Create' : 'Update', ['class' => $usermodel->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
						</div>
					</div>
				</div>
				
		

		<?php ActiveForm::end(); ?>

	
</div>
