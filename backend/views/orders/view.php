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
	
	</div>
	

</div>
