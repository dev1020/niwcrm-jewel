<?php
	use yii\helpers\Html;
	use yii\helpers\Url;
	use yii\widgets\Pjax;
	use miloschuman\highcharts\Highcharts;
/* @var $this yii\web\View */

$this->title = 'Rate Chart';
//$this->params['breadcrumbs'][] = ['label' => 'Station', 'url' => ['station/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
 <style> 
.quicklinks .btn {
	text-transform:Capitalize;
	font-size: 1em;
} 
body *{
  
}

.menuchart li{
    padding: 5px;
	border-bottom: 1px solid #e0e0e0;
}
.box-header{
    cursor:pointer;
}
 </style>  
			<div class="row">
				<div class="container" style="background:#e0e0e0">
				<hr>
				<div class="col-lg-12 ">
					<?php foreach($categories as $category){?>
					<div class="box box-success collapsed-box">
						<div class="box-header with-border bg-info" data-widget="collapse">
						  <h3 class="box-title"><?= $category->category_name ?></h3>

						  <div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
							</button>
							
						  </div>
						</div>
						<!-- /.box-header -->
						<div class="box-body no-padding" style="display: none;">
						  <div class="row">
							<div class="col-md-9 col-sm-8">
							  <div class="pad">
								<ol class="menuchart">
									<?php foreach($category->services as $service){?>
										<li> <span class="pull-left"><strong><?= ucwords($service->name)?></strong></span>
											<span class="pull-right text-primary"><?php 
											if($service->price_max > $service->price){
												echo '<i class="fa fa-inr"></i>&nbsp;'.$service->price.' - '.$service->price_max;
											}else{
												echo '<i class="fa fa-inr"></i>&nbsp;'.$service->price;
											}?></span></li>
									<?php } ?>
								</ol>
							  </div>
							</div>
							
							<!-- /.col -->
						  </div>
						  <!-- /.row -->
						</div>
						<!-- /.box-body -->
					</div>
					<?php } ?>
				</div>
				
				</div>
			</div>
			