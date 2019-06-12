<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use backend\models\Companies;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use kartik\depdrop\DepDrop;

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
	
	<div class="col-md-12 ">
		<div class="box box-info">
			<div class="box-header">
				<h3 class="box-title"><strong>List of Users</strong> <?= Html::a('Create Users', ['create'], ['class' => 'btn btn-success right']) ?> </h3>
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
						
						
						
						<?= $form->field($model, 'usertype')->dropDownList(['backenduser'=>'Backend Personnel','suppliers'=>'Suppliers','user'=>'Users']) ?>
						
						<?= $form->field($model, 'company_id')->widget(Select2::classname(), [
							'data' =>  ArrayHelper::map(Companies::find()->orderBy(['company_name' => SORT_ASC])->all(),'id','company_name'),
							'language' => 'en',
							'options' => ['multiple' => false, 'placeholder' => 'Select Company'],
							'pluginOptions' => [
								'allowClear' => true,
								'tabindex' => 'off',
							],
						]);
					?>
					<?= $form->field($model, 'branch_id')->widget(DepDrop::classname(), [
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
							'depends'=>['user-company_id'],
							'placeholder'=>'Select...',
							'url'=>Url::to(['getbranches']),
							'dataType' => 'json',
						]
					]);?>
						
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
