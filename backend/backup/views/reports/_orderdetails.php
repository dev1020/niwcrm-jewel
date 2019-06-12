<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset; 
use johnitvn\ajaxcrud\BulkButtonWidget;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\OrderDetailsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */



CrudAsset::register($this);

?>
<div class="order-details-index">
	<div class="row">
		<div class="col-lg-12 text-center">
		<h2> Order Details Categories</h2>
		</div>
		<div class="col-lg-6 col-lg-offset-3">
			<div id="ajaxCrudDatatable">
				<?=GridView::widget([
					//'id'=>'crud-datatable',
					'dataProvider' => $dataProvider,
					//'filterModel' => $searchModel,
					//'pjax'=>true,
					'columns' => [
							[
								'class'=>'\kartik\grid\DataColumn',
								'attribute'=>'category_id',
								'value'=>'category.category_name'
							],
							[
								'class'=>'\kartik\grid\DataColumn',
								'attribute'=>'package_id',
								'value'=>'package.package_name',
							],
							
							
							

						],
					'striped' => true,
					'condensed' => true,
					'responsive' => true,          
					'panel' => [
						'type' => 'success', 
						'heading' => false,
						'before'=>false,
						'after'=>false,
						'footer'=>false,
					],
					'tableOptions' =>['class' => 'text-center'],
							
							
					
				])?>
			</div>
		</div>
	</div>
</div>
<?php Modal::begin([
    "id"=>"ajaxCrudModal",
    "footer"=>"",// always need it for jquery plugin
])?>
<?php Modal::end(); ?>
