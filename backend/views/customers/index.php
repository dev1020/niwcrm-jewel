<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset; 
use johnitvn\ajaxcrud\BulkButtonWidget;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\CustomersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Customers';
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);
?>
<style>
@media (max-width: 768px) {
     .customertable  tbody > tr:nth-of-type(2n+1) {
		background-color: #e1e1e1 !important;
	}
	.customertable  tbody tr td{
		height: 60px !important;
		vertical-align: middle !important;
		border:none !important;
	}
	
	.customertable  tbody tr td a{
		display:block;
	}
	.custlink a{
		height:60px;
		width:100%;
		line-height: 60px;
	}
}
.custlink a{
		width:100%;
		display:block;
	}
.table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
    vertical-align: middle;
}
.kv-panel-pager {
    text-align: center;
}

</style>

	<div class="customers-index">
		<div id="ajaxCrudDatatable">
			<?=GridView::widget([
				'id'=>'crud-datatable',
				'dataProvider' => $dataProvider,
				'filterModel' => $searchModel,
				'pjax'=>false,
				'columns' => require(__DIR__.'/_columns.php'),
				'toolbar'=> [
					['content'=>
						Html::a('<i class="glyphicon glyphicon-plus"></i> NEW', ['create'],
						['role'=>'modal-remote','title'=> 'Create new Customers','class'=>'btn btn-success','data-pjax'=>0]).
						Html::a('<i class="glyphicon glyphicon-repeat"></i>', [''],
						['data-pjax'=>1, 'class'=>'btn btn-default', 'title'=>'Reset Grid']).
						'{toggleData}'.
						'{export}'
					],
				],
				'rowOptions'   => function ($model, $key, $index, $grid) {
						return ['data-id' => $model->id];
					},
				'tableOptions'=> [
					'class'=>'customertable'
				],
				'striped' => true,
				'condensed' => true,
				'responsive' => true,  
				'responsiveWrap'=>false,
				'panel' => [
					'type' => 'primary', 
					'heading' => '<i class="glyphicon glyphicon-list"></i> Customers listing',
					'before'=>'<em>* Resize table columns just like a spreadsheet by dragging the column edges.</em>',
					'after'=>false,
				]
			])?>
		</div>
	</div>

<?php $script = <<< JS

JS;
$this->registerJs($script);
?>
<?php Modal::begin([
    "id"=>"ajaxCrudModal",
    "footer"=>"",// always need it for jquery plugin
])?>
<?php Modal::end(); ?>
