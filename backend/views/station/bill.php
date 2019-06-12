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
	display:none;
}
.modal-body{
	border-radius:2px;
}
.table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
   
    vertical-align: middle;
	padding: 5px;
    
}

</style>
<div class="customers-form" style="background:#ffffff;padding:5px">
		<?php if (!Yii::$app->request->isAjax){ 
			if(isset($generatebill)){
				echo '<div class="alert bg-aqua text-center">'.$msg.'</div>';
			}
			
		 } ?>
	
		<h2 class="text-center" style="margin:0px">
			<?php if($customer){?>
				<span class="text-primary"><?= ucfirst($customer->name) ?></span>
			<?php }else{?>
				<span class="text-primary">Table <?= $seat_id ?></span>
			<?php } ?>
		
		</h2>
		
		<hr>
	
	<?php $form = ActiveForm::begin(['options'=>['id'=>'bill-form']]); ?>
	
		<div class="col-lg-12">
			<table class="table table-bordered servicestable">
				<tr class="bg-primary">
					
					<th class="text-center">Service Name</th>
					
					<th class="text-center" width="30%" style="font-size:10px">Quantity * Price/item </th>
					<th class="text-center">Amount <i class="fa fa-inr"></i></th>
					
				</tr>
				<tbody>
					<?php if(!count($cust_sessionsdata)>0){
						
						echo '<tr><td colspan="3" class="text-center"><h3>No services found. Please Add Services </h3></td></tr>';
					}else{
						$amount =0 ;
						foreach($cust_sessionsdata as $key=>$servicesall){
							echo '<tr ><td class="bg-success" colspan="3"><strong>SALE NO -'.$key.'</strong></td></tr>';
							foreach($servicesall as $services){
								$amount = $amount + $services->services_quantity*$services->service->price;
					?>
					<!--<tr>
						<td class="tg-0pky"></td>
						<td class="tg-0pky"></td>
						<td class="tg-0pky" rowspan="2"><br>ssss<br>s<br>s<br>s<br></td>
					  </tr>-->
					  
					<tr>
						<td class="text-center"><?= $services->service->name ?></td>
						
						<!--<td class="text-center"></td>-->
						<td class="text-center"><span><?= $services->services_quantity.' <strong>X</strong> '.(int)$services->service->price ?><input type="hidden" value ="<?= $services->services_quantity ?>" name="<?= $key?>[<?= $services->service->id ?>][quantity]"></span></td>
						<td class="text-right"><span><?= (int)$services->services_quantity*$services->service->price ?></span><input type="hidden" value ="<?= (int)$services->service->price ?>" name="<?= $key?>[<?= $services->service->id ?>][price]"></td>
						
						
					</tr>
					<?php } } }?>
				</tbody>
				<tfoot>
				
					<tr class="bg-info">
						<td colspan="2" class="text-center" style="font-size:18px"> Total Amount</td>
						<td class="text-right"><i class="fa fa-inr"></i> <?= $amount ?></td>
					</tr>
				</tfoot>
			</table>
		</div>
		<?php if (!Yii::$app->request->isAjax){ ?>
			<div class="form-group text-center">
				<?= Html::submitButton('GENERATE BILL', ['class' => 'btn btn-success']) ?>
			</div>
		<?php } ?>
	
    <?php ActiveForm::end(); ?>
    
</div>
<?php $scripts = <<< JS

JS;

$this->registerJs($scripts);
?>

