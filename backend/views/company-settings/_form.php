<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\CompanySettings */
/* @var $form yii\widgets\ActiveForm */
?>



<div class="col-lg-12">
 <?php $form = ActiveForm::begin(); ?>
	<div class="box box-primary">
		<div class="box-header with-border">
		  <h3 class="box-title"><?= isset($model->company->company_name)? $model->company->company_name : ''?></h3>
		</div>
		<!-- /.box-header -->
		<div class="box-body padding-5">
			<div class="col-lg-12">
				<div class="col-lg-6">
					<strong><i class="fa fa-home margin-r-5"></i> Brand Name</strong> - <?= $form->field($model, 'brand_name')->textInput(['maxlength' => true])->label(false) ?>
				</div>
				<div class="col-lg-6">
					<strong><i class="fa fa-pencil margin-r-5"></i> Site Logo</strong> - <?= Html::img('@web/images/logo.png', ['alt'=>Yii::$app->name])?> <?= $form->field($model, 'site_logo')->fileInput()->label(false) ?>
				</div>
				<hr>
			</div>
			<div class="col-lg-12">
				<div class="col-lg-6">
					<strong><i class="fa fa-gift margin-r-5"></i> Loyalty Bonus %</strong> - <?= $form->field($model, 'loyalty_bonus_percentage')->textInput(['maxlength' => true])->label(false) ?>
				</div>
				<div class="col-lg-6">
					<strong><i class="fa fa-trophy margin-r-5"></i> Referral Bonus %</strong> - <?= $form->field($model, 'referral_bonus_percentage')->textInput(['maxlength' => true])->label(false) ?>
				</div>
				<hr>
			</div>
			<div class="col-lg-12">
				<div class="col-lg-4">
					<strong><i class="fa fa-envelope margin-r-5"></i> Welcome sms </strong> - <?= $form->field($model, 'welcome_sms')->dropDownList([ 'yes' => 'Yes', 'no' => 'No', ])->label(false) ?>
				</div>
				<div class="col-lg-4">
					<strong><i class="fa fa-envelope margin-r-5"></i> Sms after Payment </strong> - <?= $form->field($model, 'sms_after_payment')->dropDownList([ 'yes' => 'Yes', 'no' => 'No', ])->label(false) ?>
				</div>
				<div class="col-lg-4">
					<strong><i class="fa fa-envelope margin-r-5"></i> Sms after Order </strong> - <?= $form->field($model, 'sms_after_order')->dropDownList([ 'yes' => 'Yes', 'no' => 'No', ])->label(false) ?>
				</div>
				<hr>
			</div>
			<div class="col-lg-12">
				<div class="col-lg-5">
					<strong><i class="fa fa-envelope margin-r-5"></i> Enable Multiple Payments Type </strong> - <?= $form->field($model, 'enable_multiple_payment_type')->dropDownList([ 'yes' => 'Yes', 'no' => 'No', ])->label(false) ?>
				</div>
				<div class="col-lg-4">
					<strong><i class="fa fa-gift margin-r-5"></i> Enable Bonus Redemption </strong> - <?= $form->field($model, 'bonus_redemption')->dropDownList([ 'yes' => 'Yes', 'no' => 'No', ])->label(false) ?>
				</div>
				<div class="col-lg-3">
					<strong><i class="fa fa-calendar margin-r-5"></i> Bonus Validity(Days) </strong> - <?= $form->field($model, 'bonus_valid_days')->textInput(['maxlength' => true])->label(false) ?>
				</div>
				<hr>
			</div>
			<?php if(Yii::$app->user->can('Admin')){?>
			<h3> For Admin </h3>
			<div class="col-lg-12">
				<div class="col-lg-6">
					<strong><i class="fa fa-file-text-o margin-r-5"></i> Multi Store</strong> - <?= $form->field($model, 'multi_store')->dropDownList([ 'yes' => 'Yes', 'no' => 'No', ])->label(false) ?>
				</div>
				<div class="col-lg-6">
					<strong><i class="fa fa-database margin-r-5"></i> Package</strong> - <?= $form->field($model, 'package')->dropDownList([ 'b' => 'Basic', 'bp' => 'Basic+Pay','dp'=>'Detail Bill Pay' ])->label(false) ?>
				</div>
				<hr>
			</div>
			<div class="col-lg-12">
				<div class="col-lg-3">
					<strong><i class="fa fa-calendar margin-r-5"></i> SMS SenderId</strong> - <?= $form->field($model, 'sms_senderid')->textInput(['maxlength' => 8])->label(false) ?>
				</div>
				<div class="col-lg-9">
					<strong><i class="fa fa-edit"></i> Welcome SMS Text</strong> - <?= $form->field($model, 'welcome_sms_text')->textArea(['column' => 8])->label(false) ?>
				</div>
				<div class="col-lg-6">
					<strong><i class="fa fa-edit"></i> SMS After Payment Text</strong> - <?= $form->field($model, 'sms_text_after_payment')->textArea(['column' => 8])->label(false) ?>
				</div>
				<div class="col-lg-6">
					<strong><i class="fa fa-edit"></i> Referral SMS Text</strong> - <?= $form->field($model, 'referral_text_after_payment')->textArea(['column' => 8])->label(false) ?>
					<p class="help-block">USE <strong> BILLNO </strong>,<strong> BILLVALUE </strong>,<strong> TPOINTS </strong>,<strong> LPOINTS </strong>,<strong> RPOINTS </strong>,<strong> COMPANY </strong>, <strong> ORDERDATE  </strong>,<strong> BRANCH  </strong></p>
				</div>
				<hr>
			</div>
			<?php } ?>

		  
		  

		  
		  
		
		</div>
		
		  
		<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group text-center">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success btn-lg' : 'btn btn-primary btn-lg']) ?>
	    </div>
	<?php } ?>
		<!-- /.box-body -->
	</div>
	 <?php ActiveForm::end(); ?>
</div>
