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

$this->title = 'Orders';
$this->params['breadcrumbs'][] = ['label' => 'Profile', 'url' => ['/profile']];
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
.head span{
	display:block;
}
.dropdown-toggle span{
	display:inline-block;
}
.dropdown-toggle{
	padding: 0px 6px;
	font-size:10px;
}
</style>
<h3 class="box-header"> Your Orders </h3>
<hr>
<?php Pjax::begin(['options'=>['id'=>'orders','data-pjax-container'=>'orders']]);?>


	<div class="orders" style="background:#ffffff;padding:5px">
		
			<?php foreach($orders as $order){?>
			
				<div class="box <?= ($order->due_amount>0)? 'box-danger':'box-success'?>">
					<div class="box-header with-border bg-info">
						<div class="col-lg-4 text-left head">
							<span>Order Placed</span>
							<span><?= $order->order_date ?></span>
						</div>
						<div class="col-lg-4 text-center head">
							<span>Order Total</span>
							<span>
								<i class="fa fa-inr"></i><?= $order->total_amount?>
								<label class="label label-danger"><?= ($order->due_amount>0)? $order->due_amount :'' ?></label></span>
						</div>
						<div class="col-lg-4 text-right head">
							<span>Order # <?= str_pad($order->id, 10, '0', STR_PAD_LEFT)?></span>
							
								<div class="btn-group">
								<a class="btn dropdown-toggle btn-info" data-toggle="dropdown" href="#">
									Actions 
									<span class="icon-cog icon-white"></span><span class="caret"></span>
								</a>
								<ul class="dropdown-menu">
									<li><a class="" href="/profile/orders-view?id=<?=$order->id ?>" data-pjax="0" title="Edit"><i class="fa fa-file-invoice"></i> Invoice</a></li>
									<li><a class="" href="/profile/orders-price-breakup?id=<?= $order->id?>" data-pjax="0" title="Payments"><i class="fa fa-inr"></i> Payments</a></li>
									
								</ul>
								</div>
								
							
						</div>
					</div>
					<div class="box-body ">
						<?php foreach($order->ordersDetails as $orderdetails){?>
						<div class="col-lg-12 no-gutter">
							<div class="col-lg-4">
							<label class="label label-success"><?= $orderdetails->session_no ?></label>
							</div>
							<div class="col-lg-4 text-center">
								<?= ucwords($orderdetails->services->category->category_name) ?> | <?= ucwords($orderdetails->services->name) ?>
							</div>
							<div class="col-lg-4 text-right">
								<i class="fa fa-inr"></i>&nbsp;<?= ucwords($orderdetails->services_price) ?>
							</div>
						</div>
						<?php } ?>
					</div>
				</div>
			
			<?php } ?>
		
	</div>		
	<?php 
	Pjax::end();
	?>
	


<?php Modal::begin([
    "id"=>"ajaxCrudModal",
    "footer"=>"",// always need it for jquery plugin
])?>
<?php Modal::end(); ?>