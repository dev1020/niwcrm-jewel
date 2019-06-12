<?php

use yii\widgets\DetailView;
use yii\helpers\Url;
use yii\helpers\Html;
use miloschuman\highcharts\Highcharts;
use kartik\tabs\TabsX;
/* @var $this yii\web\View */
/* @var $model backend\models\Customers */
?>

<div class="row">
	<?php if(count($servicesdata)>0){?>
		<div class="col-lg-6 col-xs-12" id="servicewise">
			<?= Highcharts::widget([
				'options' => [
					'credits'=> [ 'enabled'=> false],
					'title' => ['text' => 'Expenditure Pattern Servicewise'],
					'chart' => [
						'renderTo' => 'servicewise',
						'displayErrors'=>true,
						'options3d'=> [
							'enabled'=> true,
							'alpha'=> 45,
							'beta'=> 0
						]
					], // Id of container to render.
					'plotOptions' => [
						'pie' => [
							'cursor' => 'pointer',
							'dataLabels'=> [
								'enabled'=> true,
							],
							'showInLegend'=> true,
							'depth'=> 35,
							'allowPointSelect'=> true,
						],
					],
					'series' => [
						[ // new opening bracket
							'type' => 'pie',
							'name' => 'No of Times',
							'data' => $servicesdata,
						] // new closing bracket
					],
				],
			]);?>
		</div>
		
		<div class="col-lg-6 col-xs-12" id="categorywise">
			<?= Highcharts::widget([
				'options' => [
					'credits'=> [ 'enabled'=> false],
					'title' => ['text' => 'Expenditure Pattern Categorywise'],
					'chart' => [
						'renderTo' => 'categorywise',
						'displayErrors'=>true,
						'options3d'=> [
							'enabled'=> true,
							'alpha'=> 45,
							'beta'=> 0
						]
					], // Id of container to render.
					'plotOptions' => [
						'pie' => [
							'cursor' => 'pointer',
							'dataLabels'=> [
								'enabled'=> true,
							],
							'showInLegend'=> true,
							'depth'=> 35,
							'allowPointSelect'=> true,
						],
					],
					'series' => [
						[ // new opening bracket
							'type' => 'pie',
							'name' => 'No of Times',
							'data' => $categorywisesum,
						] // new closing bracket
					],
				],
			]);?>
			
			
		</div>
	<?php } ?>
</div>


