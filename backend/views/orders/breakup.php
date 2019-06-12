<?php

use yii\widgets\DetailView;
use yii\helpers\Html;

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
				<label class="text-success"><?= $model->order_date?></label>
			</div>
			<div class="col-lg-4 col-xs-4 text-center">
				<label class="text-success"><?= str_pad($model->id, 10, '0', STR_PAD_LEFT)?></label>
			</div>
			<div class="col-lg-4 col-xs-4 text-right">
				<label class="text-success"><i class="fa fa-inr"></i>&nbsp;<?= $model->total_amount?></label>
			</div>
		</div>
		<div class="col-lg-12 col-xs-12 ">
			<table class="table">
				<tr>
					<td><i class="fa fa-user"></i>&nbsp;&nbsp;<?= $model->cust->name?><br><i class="fa fa-phone"></i>&nbsp;<?= $model->cust->contact?><br><i class="fa fa-home"></i>&nbsp;<?= $model->cust->address?></td>
					<td class="text-center"><?= ($model->status == 'completed')? '<img src="/admin/images/paid.png" style="width:75px">':'' ?></td>
				</tr>
				
			</table>
		</div>
	
	</div>
	<div class="row">
		<div class="col-lg-12 col-xs-12 no-gutter page-header">
			<h3>Payment Details <?php if($model->due_amount>0){?><span class=" pull-right label label-danger">Due- <i class="fa fa-inr"></i>&nbsp;<?= $model->due_amount ?> </span><?php } ?></h3>
			<table class="table table-bordered table-striped table-condensed orderdetails">
				<thead>
					<tr>
						<th class="text-left">Pay Date</th>
						<th class="text-center">Pay Type</th>
						<th class="text-right">Amount</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($orderPayments as $payments){?>
					<tr>
						<td class="text-left"><?= $payments->payment_date ?></td>
						<td class="text-center"><?= $payments->payment_type ?></td>
						<td class="text-right"><?= $payments->amount ?></td>
					</tr>
					<?php }?>
				</tbody>
			</table>
		</div>
		<?php if($model->due_amount>0){
			echo '<div class="form-group text-center">'.Html::a(' &nbsp;&nbsp;&nbsp;&nbsp;Pay&nbsp;&nbsp;&nbsp;&nbsp; ',['/station/order-payment','orderid'=>$model->id],['class'=>'btn btn-success btn-lg',]).'</div>';
		}
		?>
	</div>
	

</div>
