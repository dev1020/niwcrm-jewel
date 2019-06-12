<?php

use yii\widgets\DetailView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Orders */
?>

<style>
.modal-header{
	display:none;
}
.orders-view .page-header{
	font-size:18px;
}
.orders-view thead tr{background-color: #F03389;color: #fff;}

@media only screen and (max-width: 600px) {
  .orders-view .orderdetails{font-size:0.7em !important}
}
</style>
<div class="orders-view" style="background:#ffffff">
 
    <div class="row">
		<div class="col-lg-12 col-xs-12 no-gutter page-header">
			<div class="col-lg-4 col-xs-4 text-left">
				<label class="text-success"><?= date('d-M-Y',strtotime($model->order_date)) ?></label>
			</div>
			<div class="col-lg-4 col-xs-4 text-center">
				<label class="text-success"><?= $model->session_nos?></label>
			</div>
			<div class="col-lg-4 col-xs-4 text-right">
				<label class="text-success"><i class="fa fa-inr"></i>&nbsp;<?= $model->total_amount?></label>
			</div>
		</div>
		<div class="col-lg-12 col-xs-12 ">
			<table class="table">
				<tr>
					<td><i class="fa fa-user"></i>&nbsp;&nbsp;<?= $model->cust->name?><br><i class="fa fa-phone"></i>&nbsp;<?= $model->cust->contact?><br><i class="fa fa-home"></i>&nbsp;<?= $model->cust->address?></td>
					
					<td class="text-center"><?= ($model->due_amount == 0)? Html::img('@web/images/paid.png', ['alt'=>'Saltlake.in','style'=>'width:75px']):'' ?></td>
				</tr>
				
			</table>
		</div>
		<?php $form = ActiveForm::begin(); ?>
		<?= $form->field($model, 'order_approved')->hiddenInput(['value'=>'yes'])->label(false) ?>
		<?php if($package=='b'){?>
		<div class="col-lg-12 col-xs-12 " style="margin-bottom:20px">
			<div class="col-lg-12 col-xs-12 no-gutter page-header">
				<h3>Payment<?php if($model->due_amount>0){?><span class=" pull-right label label-danger">Due- <i class="fa fa-inr"></i>&nbsp;<?= $model->due_amount ?> </span><?php } ?></h3>
				<table class="table table-bordered table-striped table-condensed orderdetails">
					<thead>
						<tr>
							<th class="text-left">Pay Date</th>
							<th class="text-center">Pay Type</th>
							<th class="text-right">Amount</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($model->ordersPayments as $payments){?>
						<tr>
							<td class="text-left"><?= $payments->payment_date ?></td>
							<td class="text-center"><?= $payments->payment_type ?></td>
							<td class="text-right"><?= $payments->amount ?></td>
						</tr>
						<?php }?>
					</tbody>
				</table>
			</div>
			<?= $form->field($model, 'session_nos',[
											'template' => "{label}<div class='col-md-4'>{input}</div>{hint}{error}",
											'labelOptions' => [ 'class' => 'col-md-6 ' ]
							])->textInput(['maxlength' => true,'placeholder'=>"Please Enter Invoice No"]) ?>
			
			
		</div>
		<?php } ?>
			
		<?php 
		if($model->status == 'completed' || $package=='b'){
			echo '<div class="form-group">
				<label class="col-lg-6">Points Generated In this order (can be modified as neccessary).</label>
				<div class="col-lg-4 text-left">
				<input type="text" name="givenpoints" value="'.$points_to_generate.'" onclick="this.select()" class="form-control numeric">
				</div>
			</div>';
		}
		if($model->status == 'completed'){
			echo '<label class="text-info" style="margin-left:25px;font-size:20px"><input type="checkbox" name="sendsms" value="1" checked>&nbsp; Generate Points and Send SMS to Customer ? </label>';
		}elseif($package=='b'){
			echo '<label class="text-info" style="margin-left:25px;font-size:20px"><input type="checkbox" name="sendsms" value="1" checked>&nbsp; Generate Points and Send SMS to Customer ? </label>';
		}
		
		
		ActiveForm::end(); ?>
		
		<div class="col-lg-12 col-xs-12 ">
			<h5 >Order is created by <span class="text-primary"><?= ucfirst($model->createdBy->username) ?></span>. </h5>
		</div>
	
	</div>
	

</div>
<?php 
$scripts = <<< JS
$(function(){
	$(".numeric").numeric();
})
JS;
$this->registerJs($scripts);
?>