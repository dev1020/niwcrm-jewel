<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $model backend\models\Customers */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'No Services Attached';
$this->params['breadcrumbs'][] = ['label' => 'Station', 'url' => ['station/index'],'class'=>'btn btn-info'];
$this->params['breadcrumbs'][] = $this->title;
?>
<style>

.modal-body{
	border-radius:2px;
}
.table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
   
    vertical-align: middle;
	padding: 5px;
    
}

</style>
<div class="customers-form" style="background:#ffffff;padding:5px">
	<div class="row">
		<h2 class="text-center" style="margin:0px">
		<?php if($customer){?>
			<?php if($customer->gender == 'female'){
				echo '<img src="'.Url::to('@frontendimage'.'/new-female.png').'" style="max-height:50px;">';
			}else{
				echo '<img src="'.Url::to('@frontendimage'.'/new-male.png').'" style="max-height:50px;">';
			}?>&nbsp;&nbsp;<span class="text-primary"><?= ucfirst($customer->name) ?></span>
		<?php }else{?>
			<span class="text-primary">Table <?= $seat_id ?></span>
		<?php } ?>
		
		</h2>
		
		<hr>
	</div>
	
	<div class="row">
		<div class="col-lg-12">
			<table class="table table-bordered servicestable">
				
				<tbody>
					
					<tr>
						<td class="text-center"> <h4 class="text-danger">No services are Attached . Please Attach Services.</h4></td>
					</tr>
					
				</tbody>
			</table>
			<?php if (!Yii::$app->request->isAjax){ 
				echo '<div class="form-group text-center">'.Html::a('Add Services', ['/station/add-services-to-customer','id'=>isset($customer->id)?$customer->id:'','cust_session'=>$cust_session,'seat_id'=>isset($seat_id)?$seat_id:''], ['class' => 'btn btn-success',]).'</div>';
			 } ?>
		</div>
	</div>
    
    
</div>
<?php Modal::begin([
    "id"=>"ajaxCrudModal",
	 "size"=>"modal-lg",
    "footer"=>"",// always need it for jquery plugin
])?>
<?php Modal::end(); ?>

