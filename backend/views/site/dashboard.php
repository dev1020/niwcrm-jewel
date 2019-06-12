<?php
	use yii\helpers\Html;
	use yii\helpers\Url;
	use yii\bootstrap\Modal;
	use yii\widgets\Pjax;
	use miloschuman\highcharts\Highcharts;
/* @var $this yii\web\View */

$this->title = yii::$app->name.' Dashboard.';
$this->params['breadcrumbs'][] = $this->title;
?>
 <style> 
.quicklinks .btn {
	text-transform:Capitalize;
	font-size: 1em;
} 
body *{
  
}
 </style>  
		<div class="row" style="background:#f1ebf5">
				<div class="col-lg-12 col-xs-12" style="padding:10px">
					<div class="col-lg-9 col-xs-9">
						<input type="text" placeholder="Search by customer Number ,customer Name, Invoice Number" class="form-control searchstring" >
					</div>
					<div class="col-lg-3 col-xs-3">
						<input type="button" id="search" class="btn btn-primary" value="Search">
					</div>
				</div>
		</div>
		<div class="row" style="background:#f1ebf5">
				
			
				<div class="col-lg-4 col-xs-12 col-md-12 col-sm-12quicklinks">
					<div class="box box-warning">
						<div class="box-header ">
						  <h3 class="box-title">Quick Links</h3>
						</div>
						<div class="box-body bg-default">
						  <a href="<?= Url::toRoute(['/company-customers/index'])?>" class="btn btn-warning btn-block btn-lg">Manage Customers</a>
						  <a href="<?= Url::toRoute(['/orders/create'])?>" role="modal-remote" class="btn btn-info btn-block btn-lg">ADD/Open Sales</a>
						  <a href="<?= Url::toRoute(['/orders/index'])?>" class="btn btn-success btn-block btn-lg">View Order Details</a>                 
						  
						</div>
					</div>
				</div>
				<?php Pjax::begin(['options'=>['id'=>'crud-datatable-pjax',]]);?>
				
				<div class="col-lg-4 col-xs-6">
				  <!-- small box -->
				  <div class="small-box bg-olive">
					<div class="inner">
					  <h4 ><strong><i class="fa fa-inr"></i> <span class="count"><?= $order_total_amount_today?></span></strong></h4>
					  <p>Today's Sale <i class="fa fa-gift "> <?= $total_loyaltybonus_today ?></i> | <i class="fa fa-link "> <?= $latest_order_referralpoints ?></i></p>
					</div>
					<div class="icon">
					  <i class="fa fa-inr"></i>
					</div>
					<a href="<?=Url::to(['/orders/opened-today'])?>" class="small-box-footer" data-pjax="0">
					  More info <i class="fa fa-arrow-circle-right"></i>
					</a>
				  </div>
				</div>
				<div class="col-lg-4 col-xs-6">
				  <!-- small box -->
				  <div class="small-box bg-maroon">
					<div class="inner">
					  <h4> <strong> <i class="fa fa-inr"></i> <span class="count"><?= $latest_order_amount ?> </span> </strong> </h4>
					  <p>Latest Order  <i class="fa fa-gift "> <?= $latest_order_loyaltypoints ?></i> | <i class="fa fa-link "> <?= $latest_order_referralpoints ?></i></p>
					</div>
					<div class="icon">
					  <i class="fa fa-inr"></i>
					</div>
					<a href="<?=Url::to(['/orders/view','id'=>$latest_order_id])?>" role="modal-remote" class="small-box-footer">
					  View It <i class="fa fa-arrow-circle-right"></i>
					</a>
				  </div>
				</div>
				
				<div class="col-lg-3 col-xs-6">
				  <!-- small box -->
				  <div class="small-box bg-purple">
					<div class="inner">
					  <h4><strong><i class="fa fa-inr"></i> <span class="count"><?= $order_total_amount_this_month?></span></strong></h4>

					  <p> Order This Month </p>
					</div>
					<div class="icon">
					  <i class="fa fa-inr"></i>
					</div>
					<a href="<?=Url::to(['/orders'])?>" class="small-box-footer" data-pjax="0">
					  Visit Orders <i class="fa fa-arrow-circle-right"></i>
					</a>
				  </div>
				</div>
				
				<div class="col-lg-2 col-xs-6">
				  <!-- small box -->
				  <div class="small-box bg-blue-active">
					<div class="inner">
					  <h4 ><strong><?= $customers_this_month ?>/<?= $customers_count?></strong></h4>

					  <p>Customers</p>
					</div>
					<div class="icon">
					  <i class="fa fa-user-plus"></i>
					</div>
					<a href="<?=Url::to(['/company-customers/index'])?>" class="small-box-footer" data-pjax="0">
					  Manage Customers <i class="fa fa-arrow-circle-right"></i>
					</a>
				  </div>
				</div>
				<div class="col-lg-3 col-xs-12">
				  <!-- small box -->
				  <div class="small-box bg-red-active">
					<div class="inner">
					  <h4 ><strong><?= $orders_approval_pending_count ?> Orders</strong></h4>

					  <p>Need Approval</p>
					</div>
					<div class="icon">
					  <i class="fa fa-check-circle"></i>
					</div>
					<a href="<?=Url::to(['/orders/approval-pending'])?>" class="small-box-footer" data-pjax="0">
					  See Pending Orders <i class="fa fa-arrow-circle-right"></i>
					</a>
				  </div>
				</div>
				
				<?php Pjax::end();	?>
		</div>
		<div class="row" style="background:#f1ebf5">	
				<div class="col-lg-6 col-xs-12 col-md-6 col-sm-12">
					<div class="box box-info">
						<div class="box-body bg-purple">
							<?= Highcharts::widget([
								'options' => [
									'credits'=> [ 'enabled'=> false],
									'title' => ['text' => 'Sales Figure'],
									
									'xAxis' => [
										'categories' => ['Jan', 'Feb', 'Mar', 'Apr','May','Jun', 'Jul','Aug','Sep','Oct','Nov','Dec'],
										'crosshair'=> true,
									],
									'yAxis' => [
										'title' => ['text'=>'SALES'],
									],
									
								  
									'series' => [[	
										'type'=> 'column',
										'name' => 'SALES', 
										'data' => $sales_for_chart,
										'tooltip'=> ['valuePrefix'=>'₹' ,'valueDecimals'=> '2'],
										'color'=> '#00A65A',
									]]
								]
							]);
							?>
						</div>
					</div>
				</div>
				<div class="col-lg-6 col-xs-12 col-md-6 col-sm-12">
					<div class="box box-primary">
					<div class="box-body bg-blue">
					<?= Highcharts::widget([
						'options' => [
							'credits'=> [ 'enabled'=> false],
							'title' => ['text' => 'Customers'],
							'xAxis' => [
								'categories' => ['Jan', 'Feb', 'Mar', 'Apr','May','Jun', 'Jul','Aug','Sep','Oct','Nov','Dec'],
								'crosshair'=> true,
							],
							'yAxis' => [
								'title' => ['text'=>'Customers'],
							],
							
						  
							'series' => [
								[	
									'type'=> 'column',
									'name' => 'New Customers', 
									'data' => $newcustomers_for_chart,
									
								],
								[	
									'type'=> 'column',
									'name' => 'Customers Visited', 
									'data' => $visitedcustomers_for_chart,
									'color'=>'#52449A',
								]
							]
						]
					]);
					?>
					</div>
					</div>
				</div>
									
		</div>
<?php 
$url = Url::to(['site/index']);
$searchurl = Url::to(['site/search']);
$scripts = <<< JS
$('.count').each(function () {
    $(this).prop('Counter',0).animate({
        Counter: $(this).text()
    }, {
        duration: 1000,
        easing: 'swing',
        step: function (now) {
            $(this).text(Math.ceil(now));
        }
    });
});
setInterval(function(){ 
	if(!$('#ajaxCrudModal').hasClass('in')){
		$.pjax({url: '$url', container: '#crud-datatable-pjax','timeout': 5000})
	}	
 }, 30000);
 
 $('#search').on('click',function(){
	 $.post( "$searchurl", { searchitems: $('.searchstring').val(),},function( data ) {
		 var modal = $('#ajaxCrudModal').modal();
		modal.find('.modal-dialog').addClass('modal-lg');
		modal.find('.modal-body').html(data); 
		modal.find('.modal-footer').html('<button type="button" class="btn btn-success" data-dismiss="modal">Close</button>'); 
	  
	});
 })
JS;
$this->registerJs($scripts);
?>

<?php Modal::begin([
    "id"=>"ajaxCrudModal",
    "footer"=>"",// always need it for jquery plugin
])?>
<?php Modal::end(); ?>		