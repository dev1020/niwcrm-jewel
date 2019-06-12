<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\bootstrap\Modal;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model backend\models\Customers */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Customer Services';
$this->params['breadcrumbs'][] = ['label' => 'Station', 'url' => ['station/index'],'class'=>'btn btn-info'];
$this->params['breadcrumbs'][] = $this->title;

?>
<style>
.modal-header{
	background:#E08E0B;
	color:#fff;
}
.modal-body{
	border-radius:2px;
}
.table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
   
    vertical-align: middle;
	padding: 5px;
    
}
.tooltip{
	z-index:1
}

</style>

<div class="customers-form" style="background:#ffffff;padding:5px">
	
		<h2 class="text-center" style="margin:0px"><?php if($customer->gender == 'female'){
			echo '<img src="'.Url::to('@frontendimage'.'/new-female.png').'" style="max-height:50px;">';
		}else{
			echo '<img src="'.Url::to('@frontendimage'.'/new-male.png').'" style="max-height:50px;">';
		}?>&nbsp;&nbsp;<span class="text-primary"><?= ucfirst($customer->name) ?></span>
		<?php if (!Yii::$app->request->isAjax){ 
				if($billgenerated){
					echo Html::a('<i class="fa fa-trash"></i> Delete Sale', ['/station/close-session','id'=>$customer->id,'cust_session'=>$cust_session,'mode'=>'delete'], ['class' => 'btn btn-danger pull-right','style'=>'margin-left:20px','role'=>'modal-remote','title'=>'Delete', 
                          'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                          'data-request-method'=>'post',
                          'data-toggle'=>'tooltip',
                          'data-confirm-title'=>'Are you sure?',
                          'data-confirm-message'=>'Are you sure want to Delete this session',]);
				}else{
					echo Html::a('<i class="fa fa-times"></i> Close Sale', ['/station/close-session','id'=>$customer->id,'cust_session'=>$cust_session,'mode'=>'close'], ['class' => 'btn btn-danger pull-right','style'=>'margin-left:20px','role'=>'modal-remote','title'=>'Close', 
                          'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                          'data-request-method'=>'post',
                          'data-toggle'=>'tooltip',
                          'data-confirm-title'=>'Are you sure?',
                          'data-confirm-message'=>'Are you sure want to close this session',]);
				}
				
			 } ?>
		
		
		</h2>
		
		<hr>
	
	
	<div class="row">
		<div class="col-lg-12">
			<?php \yii\widgets\Pjax::begin(['options'=>['id'=>'customers1234','data-pjax-container'=>'customers1234'],'timeout' => 0]);?>
			<table class="table table-bordered servicestable">
				<tr>
					<td colspan="3"> <h3>OrderBoy - <span class="text-success"><strong><?= ucwords($assigned_executive) ?></strong></span></h3></td>
					
				</tr>
				<tr class="bg-primary">
					<th class="text-center">Service Name</th>
					<th class="text-center">Quantity</th>
					<th class="text-center">Act</th>
				</tr>
				<tbody>
					<?php 
					$total_amount = 0;
					if(!count($customerservices)>0){
						echo '<tr><td colspan="4" class="text-center"><h3>No services found. Please Add Services </h3></td></tr>';
					}else{
						
						foreach($customerservices as $services){
							$total_amount = $total_amount + $services->services_price*$services->services_quantity;
					?>
					
					<tr>
						<td class="text-center"><label class="label label-primary"><?= ucwords($services->service->category->category_name) ?></label><br><?= $services->service->name ?></td>
						<td class="text-center "><?= $services->services_quantity ?></td>
						
						<td class="text-center">
							
							<?= Html::a('<i class="fa fa-times"></i>',['station/delete-services','id'=>$services->id,'cust_id'=>$customer->id,'cust_session'=>$cust_session],['class'=>'btn btn-danger','data-id'=>$services->id,'role'=>'modal-remote','title'=>'Delete', 
                          'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                          'data-request-method'=>'post',
                          'data-toggle'=>'tooltip',
                          'data-confirm-title'=>'Are you sure?',
                          'data-confirm-message'=>'Are you sure want to delete this item',])?>
						   </td>
					</tr>
					
					<?php } } ?>
				</tbody>
				<tfoot>
					<tr class="bg-purple">
						<td class="text-right">Total Amount &nbsp;</td>
						<td colspan="2">&nbsp;<i class="fa fa-inr"></i> <?= $total_amount ?></td>
					</tr>
				</tfoot>
			</table>
			<?php Pjax::end();?>
			
			
			
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12">
			<?php 
			if($billgenerated){
				echo '<div class="form-group text-center">'.\yii\helpers\Html::a('<i class="fa fa-file-text"></i> BILL', ['station/generate-bill','id'=>$customer->id,'session_no'=>$cust_session], ['class' => 'btn bg-purple ','style'=>'margin-right:10px']).\yii\helpers\Html::a('Asign Executive', ['/station/assign-executive-to-customer','id'=>$customer->id,'cust_session'=>$cust_session], ['class' => 'btn btn-info','style'=>'margin-right:10px','role'=>'modal-remote',]).\yii\helpers\Html::a('Add Services', ['/station/add-services-to-customer','id'=>$customer->id,'cust_session'=>$cust_session], ['class' => 'btn btn-success','data-pjax'=>0,]).'</div>';
			}else{
				echo '<div class="form-group text-center">'.Html::button('Bill already Generated', ['class' => 'btn btn-danger','disabled'=>'disabled']).Html::a(' &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-inr"></i> Pay&nbsp;&nbsp;&nbsp;&nbsp; ',['/station/order-payment','orderid'=>$orders->id],['class'=>'btn btn-success','style'=>'margin-left:20px']).'</div>';
			}
			
		 ?>
		</div>
	</div>
    
    
</div>

<?php Modal::begin([
    "id"=>"ajaxCrudModal",
	 "size"=>"modal-lg",
    "footer"=>"",// always need it for jquery plugin
])?>
<?php Modal::end(); ?>

