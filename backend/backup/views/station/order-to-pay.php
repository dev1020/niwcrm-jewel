<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Customers */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Customer Bill';
$this->params['breadcrumbs'][] = ['label' => 'Station', 'url' => ['station/index'],'class'=>'btn btn-info'];
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
.modal-header{
	background:#F05026
	
}
.modal-title{
	text-align:center;
	color:#ffffff;
}
.modal-body{
	border-radius:2px;
}
.table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
   
    vertical-align: middle;
	padding: 5px;
    
}

</style>
<div class="order-to-pay" style="background:#ffffff;padding:5px">
	
	<?php $form = ActiveForm::begin(['options'=>['id'=>'assign-form']]); ?>
	
		<?php if (!Yii::$app->request->isAjax){ 
			if(isset($billalreadygenerated)){
				echo '<div class="alert alert-danger text-center">'.$msg.'</div>';
			}
			if(isset($billgenerated)){
				echo '<div class="alert alert-success text-center">'.$msg.'</div>';
			}
			
		 } ?>
		<div class="col-lg-12">
			<fieldset>
				<legend >Order Details <span class="pull-right"><?= str_pad($orders->id, 10, '0', STR_PAD_LEFT)?></span></legend>
				<table class="table">
					<tr><td><i class="fa fa-user"></i>&nbsp;&nbsp;<?= $orders->cust->name?><br><i class="fa fa-phone"></i>&nbsp;<?= $orders->cust->contact?><br><i class="fa fa-home"></i>&nbsp;<?= $orders->cust->address?></td></tr>
					
				</table>
			</fieldset>
			<table class="table table-bordered servicestable">
				<tr class="bg-primary">
					
					<th class="text-center">Sale No.</th>
					<td><b>Items.</b></td>
					<td style="width:10%;"><b>Quantity</b></td>
					<td style="width:10%;"><b>Price</b></td>
					<td style="width:10%;"><b>Amount</b></td>
					
				</tr>
				<tbody>
				<?php foreach($orderdetails as $details){?>
					<tr>
						<td><?= $details->session_no?></td>
						<td><label class="label bg-purple"><?= ucwords($details->services->category->category_name) ?></label><br><?= $details->services->name?></td>
						<td class="text-right"><?= $details->services_quantity?></td>
						<td class="text-right"><?= $details->services_price?></td>
						<td class="text-right"><?= $details->services_price*$details->services_quantity?></td>
					</tr>
					
				<?php } ?>
				</tbody>
				<tfoot>
					
					<tr style="border-top:2px solid #000">
						<td colspan="4" class="text-right">Total</td><td class="text-right"><?= $orders->total_amount?> </td>
					</tr>
				</tfoot>
			</table>
		</div>
		<?php if (!Yii::$app->request->isAjax){ 
			
			
				echo '<div class="form-group text-center">'.Html::a(' &nbsp;&nbsp;&nbsp;&nbsp;Pay&nbsp;&nbsp;&nbsp;&nbsp; ',['/station/order-payment','orderid'=>$orders->id],['class'=>'btn btn-success btn-lg',]).'</div>';
			
			
		 } ?>
	
    <?php ActiveForm::end(); ?>
    
</div>


