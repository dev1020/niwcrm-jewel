<?php
	use yii\helpers\Html;
	use yii\helpers\Url;
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
			<hr>
				<div class="col-lg-4 col-xs-12 col-md-12 col-sm-12quicklinks">
					<div class="box box-warning">
						<div class="box-header ">
						  <h3 class="box-title">Quick Links</h3>
						</div>
						<div class="box-body bg-default">
						  <a href="<?= Url::toRoute(['/customers/index'])?>" class="btn btn-warning btn-block btn-lg">Manage Customers</a>
						  <a href="<?= Url::toRoute(['/station/index'])?>" class="btn btn-info btn-block btn-lg">ADD/Open Sales</a>
						  <a href="<?= Url::toRoute(['/orders/index'])?>" class="btn btn-success btn-block btn-lg">View Order Details</a>                 
						  <a href="<?= Url::toRoute(['/services/ratecard'])?>" class="btn btn-danger btn-block btn-lg">Rate/Menu Card</a>
						 
						</div>
					</div>
				</div>
				<?php Pjax::begin(['options'=>['id'=>'pjax-container','data-pjax-container'=>'']]);?>
				
				<div class="col-lg-4 col-xs-6">
				  <!-- small box -->
				  <div class="small-box bg-olive">
					<div class="inner">
					  <h4 ><strong><i class="fa fa-inr"></i> <span class="count"><?= $order_total_amount_today?></span></strong></h4>
					  <p>Today's Sale</p>
					</div>
					<div class="icon">
					  <i class="fa fa-inr"></i>
					</div>
					<a href="<?=Url::to(['/orders'])?>" class="small-box-footer">
					  More info <i class="fa fa-arrow-circle-right"></i>
					</a>
				  </div>
				</div>
				
				
				
				
				<div class="col-lg-4 col-xs-6">
				  <!-- small box -->
				  <div class="small-box bg-maroon-active">
					<div class="inner">
					  <h4><strong><i class="fa fa-inr"></i> <span class="count"><?= $order_total_due?></span></strong></h4>

					  <p>Total Due</p>
					</div>
					<div class="icon">
					  <i class="fa fa-user"></i>
					  <i class="fa fa-inr"></i>
					</div>
					<a href="<?=Url::to(['/orders/dues'])?>" class="small-box-footer">
					  Due Summary <i class="fa fa-arrow-circle-right"></i>
					</a>
				  </div>
				</div>
				
				<div class="col-lg-4 col-xs-6">
				  <!-- small box -->
				  <div class="small-box bg-purple">
					<div class="inner">
					  <h4 ><strong><i class="fa fa-inr"></i> <span class="count"><?= $order_total_amount_this_month?></span></strong></h4>

					  <p>Order This Month</p>
					</div>
					<div class="icon">
					  <i class="fa fa-inr"></i>
					</div>
					<a href="<?=Url::to(['/orders'])?>" class="small-box-footer">
					  Visit Orders <i class="fa fa-arrow-circle-right"></i>
					</a>
				  </div>
				</div>
				
				<div class="col-lg-4 col-xs-6">
				  <!-- small box -->
				  <div class="small-box bg-blue-active">
					<div class="inner">
					  <h4 ><strong><?= $customers_this_month ?>/<?= $customers_count?></strong></h4>

					  <p>Customers</p>
					</div>
					<div class="icon">
					  <i class="fa fa-user-plus"></i>
					</div>
					<a href="<?=Url::to(['/customers'])?>" class="small-box-footer">
					  Manage Customers <i class="fa fa-arrow-circle-right"></i>
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

	$.pjax({url: '$url', container: '#pjax-container'})
 }, 60000);
JS;
$this->registerJs($scripts);
?>
			