<?php

use yii\widgets\DetailView;
use yii\helpers\Url;
use yii\helpers\Html;
use miloschuman\highcharts\Highcharts;

use yii\bootstrap\Modal;
use johnitvn\ajaxcrud\CrudAsset;
use yii\widgets\Pjax;



CrudAsset::register($this);
/* @var $this yii\web\View */
/* @var $model backend\models\Customers */
?>
<style>
.modal-header{
	display:none;
}
.customers-stats {
  margin-bottom:5px
}

.customers-stats td, .customers-stats th {
  border: 1px solid #ddd;
  padding: 8px;
  cursor:pointer;
}

.customers-stats tr:nth-child(even){background-color: #f2f2f2;}

.customers-stats tr:hover {background-color: #ddd !important;}
.customers-stats .panel-body{
	padding:5px;
}
.customers-stats tr td a {
    display: block;
    height: 100%;        
    width: 100%;
}
.customers-stats .panel-heading{
	padding:5px 15px;
	font-size:20px;
}
.customers-stats .page-header{
	font-size:18px;
}
</style>
<?php Pjax::begin(['options'=>['id'=>'customers-stats','data-pjax-container'=>'customers-stats']]);?>


<div class="customers-stats" style="background:#ffffff;padding:5px">

    <div class="row">
		<div class="col-lg-12 col-xs-12 no-gutter page-header">
			<div class="col-lg-2 col-xs-2">
				<img class="img-circle" src="/admin/images/face1.jpg" alt="<?= yii::$app->name?>" style="width:36px">
			</div>
			<div class="col-lg-6 col-xs-6">
				<label class="text-success"><?= $model->name?></label>
			</div>
			<div class="col-lg-4 col-xs-4">
				<label class="text-success text-right"><?= $model->contact?></label>
				
				<?= Html::a('<i class="fa fa-calendar"> Add Dates</i>',['customers-important-dates/create','cust_id'=>$model->id],['title'=>'Add Dates', 'data-toggle'=>'tooltip','class'=>'btn btn-info pull-right','role'=>'modal-remote'])?>
			</div>
		</div>
		<div class="col-lg-12 col-xs-12 no-gutter">
			<div class="col-lg-6 col-xs-6">
				<label class="text-danger">D.O.B:&nbsp;</label><label class="text-success"><?= date('y-m-d') ?></label>
			</div>
			<div class="col-lg-6 col-xs-6 text-right">
				<label class="text-danger">D.O.A:&nbsp;</label><label class="text-success"><?= date('y-m-d') ?></label>
			</div>
		</div>
		<div class="col-lg-12 col-xs-12 no-gutter">
			<div class="col-lg-6 col-xs-6">
				<label class="text-danger">Points Available:&nbsp;</label><label class="text-success"><?= $customerbonusavailable ?></label>
				
			</div>
			<div class="col-lg-6 col-xs-6 text-right">
				<label class="text-danger">Order value:&nbsp;</label><label class="text-success"><i class="fa fa-inr"></i>&nbsp; <?=$totalOrderValue?></label><br>
				
			</div>
			
		</div>
		<hr>
	</div>
	
	<div class="row">
		<div class="col-lg-12 col-xs-12 no-gutter">
		<?php foreach($importantdates as $important){?>
			<div class="info-box">
			  <!-- Apply any bg-* class to to the icon to color it -->
			  <span class="info-box-icon bg-green"><i class="fa fa-gift"></i></span>
			  <div class="info-box-content">
				<span class="info-box-text"><strong>UPCOMING DATE</strong></span>
				<span class="info-box-text"><?= $important['title']?></span>
				<span class="info-box-number"><?=  date("jS M", strtotime($important['imp_date']))?></span>
			  </div>
			  <!-- /.info-box-content -->
			</div>
							
							<?php } ?>
		</div>
	</div>
	<div class="row">
		<?php if(count($orders)>0){?>
			<div class="col-lg-12 col-xs-12 no-gutter">
				<div class="panel panel-primary">
					<div class="panel-heading">Last Visit</div>
					<div class="panel-body">
						<table class="table  table-bordered table-striped table-condensed">
							<?php foreach($orders as $order){?>
							<tr>
							
							<td><?= Html::a($order->order_date,['orders/view','id'=>$order->id],['title'=>'Details of Order '.str_pad($order->id, 10, '0', STR_PAD_LEFT), 'data-toggle'=>'tooltip','data-pjax'=>0])?></td>
							
							<td><?= $order->session_nos ?></td>
							<td><?= $order->total_amount?></td>
							</tr>
							<?php } ?>
						</table>
					</div>
				</div>
			</div>
		<?php } ?>
	</div>
	
	
	
	
	<?php 
	Pjax::end();
	?>
	

</div>
<?php Modal::begin([
    "id"=>"ajaxCrudModal",
    "footer"=>"",// always need it for jquery plugin
])?>
<?php Modal::end(); ?>