<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Customers */
/* @var $form yii\widgets\ActiveForm */
?>
<style>
.modal-header{
	display:none;
}
.box-header{
	cursor:pointer;
}
</style>

<div class="order-to-pay" style="background:#ffffff;padding:5px">
	<?php if (!Yii::$app->request->isAjax){ 
			
				echo '<div class="alert alert-success text-center">PAYMENT IS SUCCESSFULL</div>';
			
		 } ?>
	<div class="row">
	
		<div class="col-lg-12 col-xs-12">
			<h4>Payments Done <span class="pull-right"><i class="fa fa-gift text-success"> - <?= $loyaltybonus ?></i> | <i class="fa fa-link text-danger"> - <?= $referralbonus ?></i></span></h4>
			
			<table class="table table-bordered ">
				<thead>
					<tr class="bg-primary">
					<th>Date</th>
					<th>Type</th>
					<th>Amount</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach($ordersPayments as $payments ){?>
					<tr>
						<td><?= $payments->payment_date?></td>
						<td><?= $payments->payment_type?></td>
						<td><i class="fa fa-inr"></i>&nbsp;<?= $payments->amount?></td>
					</tr>
				<?php } ?>
				</tbody>
				<tfoot>
					<tr>
					<td colspan="2" class="text-right"> Order-<?= str_pad($orders->id, 10, '0', STR_PAD_LEFT)?> Total- </td>
					<td><i class="fa fa-inr"></i>&nbsp; <?= $totalpaymenttillnow ?></td>
					</tr>
				</tfoot>
			</table>
		</div>
	
	</div>
    
    
</div>
<?php $script = <<< JS
$(function(){
	
});
JS;
$this->registerJs($script);
?>