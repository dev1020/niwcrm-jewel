<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset; 
use johnitvn\ajaxcrud\BulkButtonWidget;
use miloschuman\highcharts\Highcharts;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\CompaniesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = ucwords(strtolower($user->first_name.' '.$user->last_name)).' Stats';
$this->params['breadcrumbs'][] = $this->title;
CrudAsset::register($this);
?>
<style>
.nav-tabs { border-bottom: 2px solid #DDD; }
    .nav-tabs > li.active > a, .nav-tabs > li.active > a:focus, .nav-tabs > li.active > a:hover { border-width: 0; }
    .nav-tabs > li > a { border: none; color: #ffffff;background: #5a4080; }
        .nav-tabs > li.active > a, .nav-tabs > li > a:hover { border: none;  color: #5a4080 !important; background: #fff; }
        .nav-tabs > li > a::after { content: ""; background: #5a4080; height: 2px; position: absolute; width: 100%; left: 0px; bottom: -1px; transition: all 250ms ease 0s; transform: scale(0); }
    .nav-tabs > li.active > a::after, .nav-tabs > li:hover > a::after { transform: scale(1); }
.tab-nav > li > a::after { background: ##5a4080 none repeat scroll 0% 0%; color: #fff; }
.tab-pane { padding: 15px 0; }
.tab-content{padding:20px}
.nav-tabs > li  {width:20%; text-align:center;}
.card {background: #FFF none repeat scroll 0% 0%; box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.3); margin-bottom: 30px; }

@media all and (max-width:724px){
.nav-tabs > li > a > span {display:none;}	
.nav-tabs > li > a {padding: 5px 5px;}
}
</style>
<div class="companies-index">
	<div class="container">
		<div class="row">
			<div class="col-lg-3 col-xs-6 pad-5">
				<div class="panel bg-purple">
					<div class="panel-heading">
						<div class="row">
							<div class="col-xs-4">
								<i class="fa fa-inr fa-3x"></i>
							</div>
							<div class="col-xs-8 text-right">
								<p class="announcement-heading"><?= $order_total_amount_today ?></p>
								<p class="announcement-text">Order Value</p>
							</div>
						</div>
					</div>
					<a href="#">
						<div class="panel-footer announcement-bottom">
							<div class="row">
								<div class="col-xs-6">
									View users
								</div>
								<div class="col-xs-6 text-right">
									<i class="fa fa-arrow-circle-right"></i>
								</div>
							</div>
						</div>
					</a>
				</div>
			</div>
			<div class="col-lg-3 col-xs-6 pad-5">
				<div class="panel bg-navy">
					<div class="panel-heading">
						<div class="row">
							<div class="col-xs-4">
								<i class="fa fa-mobile fa-3x"></i>
							</div>
							<div class="col-xs-8 text-right">
								<p class="announcement-heading"><?= $orders_today ?></p>
								<p class="announcement-text">Total Orders</p>
							</div>
						</div>
					</div>
					<a href="#">
						<div class="panel-footer announcement-bottom">
							<div class="row">
								<div class="col-xs-6">
									View users
								</div>
								<div class="col-xs-6 text-right">
									<i class="fa fa-arrow-circle-right"></i>
								</div>
							</div>
						</div>
					</a>
				</div>
			</div>
			<div class="col-lg-3 col-xs-6 pad-5">
				<div class="panel bg-orange">
					<div class="panel-heading">
						<div class="row">
							<div class="col-xs-4">
								<i class="fa fa-clock-o fa-3x"></i>
							</div>
							<div class="col-xs-8 text-right">
								<p class="announcement-heading"><?= $orders_pending_today ?></p>
								<p class="announcement-text">Pending Orders</p>
							</div>
						</div>
					</div>
					<a href="#">
						<div class="panel-footer announcement-bottom">
							<div class="row">
								<div class="col-xs-6">
									View users
								</div>
								<div class="col-xs-6 text-right">
									<i class="fa fa-arrow-circle-right"></i>
								</div>
							</div>
						</div>
					</a>
				</div>
			</div>
			<div class="col-lg-3 col-xs-6 pad-5">
				<div class="panel bg-red">
					<div class="panel-heading">
						<div class="row">
							<div class="col-xs-4">
								<i class="fa fa-trash fa-3x"></i>
							</div>
							<div class="col-xs-8 text-right">
								<p class="announcement-heading"><?= $orders_cancelled_today ?> </p>
								<p class="announcement-text">Cancelled Orders</p>
							</div>
						</div>
					</div>
					<a href="#">
						<div class="panel-footer announcement-bottom">
							<div class="row">
								<div class="col-xs-6">
									View users
								</div>
								<div class="col-xs-6 text-right">
									<i class="fa fa-arrow-circle-right"></i>
								</div>
							</div>
						</div>
					</a>
				</div>
			</div>
		</div>
	
		<div class="row">
			<div class="col-md-12 pad-5"> 
			  <!-- Nav tabs -->
			  <div class="card">
				<ul class="nav nav-tabs" role="tablist">
				  <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab"><i class="fa fa-bar-chart-o"></i>  <span>Today</span></a></li>
				  <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab"><i class="fa fa-bar-chart-o"></i>  <span>This Month</span></a></li>
				  <li role="presentation"><a href="#messages" aria-controls="messages" role="tab" data-toggle="tab"><i class="fa fa-bar-chart-o"></i>  <span>Previous Month</span></a></li>
				  <li role="presentation"><a href="#settings" aria-controls="settings" role="tab" data-toggle="tab"><i class="fa fa-bar-chart-o"></i>  <span>All</span></a></li>				  
				</ul>
				
				<!-- Tab panes -->
				<div class="tab-content">
					<div role="tabpanel" class="tab-pane active" id="today">
						<div class="col-lg-6">
							<?= Highcharts::widget([
								'options' => [
									'credits'=> [ 'enabled'=> false],
									'title' => ['text' => 'Stats'],
									'chart' => ['renderTo' => 'today'],
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
					<div role="tabpanel" class="tab-pane" id="thismonth">
						<?= Highcharts::widget([
							   'options' => [
								  'chart' => ['renderTo' => 'thismonth',],
								  'credits'=> [ 'enabled'=> false],
								  'title' => ['text' => 'Fruit Consumption'],
								  'xAxis' => [
									 'categories' => ['Apples', 'Bananas', 'Oranges']
								  ],
								  'yAxis' => [
									 'title' => ['text' => 'Fruit eaten']
								  ],
								  'series' => [
									 ['name' => 'Jane', 'data' => [1, 0, 4]],
									 ['name' => 'John', 'data' => [5, 7, 3]]
								  ]
							   ]
							]);
						?>
					</div>
				  <div role="tabpanel" class="tab-pane" id="messages">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</div>
				  <div role="tabpanel" class="tab-pane" id="settings">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passage..</div>
				  <div role="tabpanel" class="tab-pane" id="extra">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passage..</div>
				</div>
			  </div>
			</div>
		</div>
	</div>
</div>
<?php Modal::begin([
    "id"=>"ajaxCrudModal",
    "footer"=>"",// always need it for jquery plugin
])?>
<?php Modal::end(); ?>
