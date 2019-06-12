<?php

use yii\widgets\DetailView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\CompanySettings */
?>

<div class="col-lg-12">
	<div class="box box-primary">
		<div class="box-header with-border">
		  <h3 class="box-title"><?= $model->company->company_name?></h3>
		</div>
		<!-- /.box-header -->
		<div class="box-body">
		  <strong><i class="fa fa-home margin-r-5"></i> Brand Name</strong> - <?= $model->brand_name ?>

		  
		  <hr>

		  <strong><i class="fa fa-map-marker margin-r-5"></i> Loyalty Bonus %</strong> - <?= $model->loyalty_bonus_percentage ?>

		  

		  <hr>
		  <strong><i class="fa fa-map-marker margin-r-5"></i> Referral Bonus %</strong> - <?= $model->referral_bonus_percentage ?>

		  

		  <hr>

		  <strong><i class="fa fa-pencil margin-r-5"></i> Site Logo</strong> - <?= Html::img('@web/images/logo.png', ['alt'=>Yii::$app->name])?>

		  

		  <hr>

		  <strong><i class="fa fa-file-text-o margin-r-5"></i> Multi Store</strong> - Yes
			 <hr>
		  <strong><i class="fa fa-envelope margin-r-5"></i> Welcome sms</strong> - <?= $model->welcome_sms ?>

		   <hr>
		  <strong><i class="fa fa-envelope margin-r-5"></i> Sms after Payment</strong> - <?= $model->sms_after_payment ?>

		   <hr>
		  <strong><i class="fa fa-envelope margin-r-5"></i> Sms after Order</strong> - <?= $model->sms_after_order ?>

		  
		  
		</div>
		<!-- /.box-body -->
		<div class="box-footer text-center">
		<?= Html::a('Edit', ['company-settings/update','id'=>$model->company_id], ['class' => 'btn btn-primary btn-lg']) ?>
		</div>
	</div>
</div>
