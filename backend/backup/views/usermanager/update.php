<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\Partners;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

//use dosamigos\datepicker\DatePicker;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = 'Update User: ' . $model->username;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->username, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="user-update">

    
<div class="row">
	<div class="col-md-2">
			<div class="box box-info">
				<div class="box-header">
					<h3 class="box-title"><strong>Quick Links</strong></h3>
				</div>
				<div class="box-body sidebar">
					<ul class="sidebar-menu">
						<li>
							<?= Html::a('Employees', ['index']) ?>
						</li>
						<li>
							<?= Html::a('Customers', ['customers']) ?>
						</li>
						<li>
							<?= Html::a('Add Employee', ['create']) ?>
						</li>
						<li>
							<?= Html::a('Add Customer by OTP', ['createbyotp']) ?>
						</li>
					</ul>
				</div><!-- /.box-body -->
			</div>
		</div>
	<div class="col-md-10 ">
		<div class="box box-info">
			<div class="box-header">
				<h3 class="box-title"><strong>List of Users</strong> <?= Html::a('Create Users', ['create'], ['class' => 'btn btn-success right']) ?> <?= Html::a('user by otp', ['createbyotp'], ['class' => 'btn btn-primary right']) ?></h3>
			</div>
		<div class="box-body ">
			
			<hr>
			<?php $form = ActiveForm::begin(['options'=>['enctype'=>'multipart/form-data']]); ?>
				<div class="row">
					<div class="col-md-6 ">
						
						<?= $form->field($model, 'first_name')->textInput(['autofocus' => true]) ?>

						<?= $form->field($model, 'last_name')->textInput() ?>

						<?= $form->field($model, 'username')->textInput() ?>
						
						<?= $form->field($model, 'contact_number')->textInput() ?>
						
						<?= $form->field($model, 'dob')->textInput();?>
					</div>
					<div class="col-md-6 ">
						
					
						<?= $form->field($model, 'email') ?>					
						
						<?= $form->field($model, 'address')->textInput() ?>
						<?= $form->field($model, 'address2')->textInput() ?>
						
						<?= $form->field($model, 'pin')->textInput() ?>
						
						<?= $form->field($model, 'facebook_page')->textInput() ?>
						
						
						<?= $form->field($model, 'usertype')->dropDownList(['backenduser'=>'Backend Personnel','suppliers'=>'Suppliers','user'=>'Users']) ?>
						
						<?= $form->field($model, 'partners_id')->widget(Select2::classname(), [
							'data' =>  ArrayHelper::map(Partners::find()->orderBy(['partners_name' => SORT_ASC])->all(),'id','partners_name'),
							'language' => 'en',
							'options' => ['multiple' => false, 'placeholder' => 'Select Partners ...'],
							'pluginOptions' => [
								'allowClear' => true,
								'tabindex' => 'off',
							],
						]);
					?>
						<?= $form->field($model, 'reg_for_sos')->dropDownList(['N'=>'No','Y'=>'Yes'])->label('Registered For SOS') ?>
						<?= $form->field($model, 'profilepic')->fileInput(); ?>
					</div>
					<div class="col-md-6 ">
						<div class="form-group">
							<?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
						</div>
					</div>
				</div>

			<?php ActiveForm::end(); ?>
		</div>
	</div>
</div>
