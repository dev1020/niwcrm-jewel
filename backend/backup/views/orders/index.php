<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset; 
use johnitvn\ajaxcrud\BulkButtonWidget;
use kartik\export\ExportMenu;


/* @var $this yii\web\View */
/* @var $searchModel backend\models\OrdersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Sale Details';
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);

?>

<style>
@media (max-width: 768px) {
    
	.orders-index  tbody tr td{
		height: 45px !important;
		vertical-align: middle !important;
		font-size:0.9em;
	}
	.orders-index  thead tr td,.orders-index  thead tr th,.orders-index  thead tr a{
		font-size:0.9em;
	}
	
	.order-index .select2-container{
		width:100px !important;
	}
	
	
    
}
.bg-success {
		background-color: #dff0d8 !important;
	}
.bg-warning {
    background-color: #fcf8e3 !important;
}
.orders-index tr td a {
    display: block;
    height: 100%;        
    width: 100%;
	text-decoration:underline;
	font-weight:600;
}

</style>
<div class="row">
	<div class="col-lg-12 col-xs-12">
		<div class="box box-info ">
				<div class="box-header with-border">
				  <h3 class="box-title">Search Orders</h3>

				  <div class="box-tools pull-right">
					<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
					</button>
					<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
				  </div>
				</div>
				<!-- /.box-header -->
				<div class="box-body">
					<?= $this->render('search', ['model' => $searchModel]) ?>
				  <!-- /.table-responsive -->
				</div>
				
		</div>
	</div>
</div>

	<div class="orders-index">
		
		<div id="ajaxCrudDatatable">
			<?=GridView::widget([
				'id'=>'crud-datatable',
				'dataProvider' => $dataProvider,
				'filterModel' => $searchModel,
				'pjax'=>true,
				'columns' => require(__DIR__.'/_columns.php'),
				'toolbar'=> [
					['content'=>
						ExportMenu::widget([
							'dataProvider' => $dataProvider,
							'columns' => [
								['class' => 'yii\grid\SerialColumn'],
								'order_date',
								'total_amount',
								'due_amount',
								'session_nos',
								'status',
								['class' => 'yii\grid\ActionColumn'],
							],
							'columnSelectorOptions'=>[
								'label' => 'Cols...',
								'class' => 'btn btn-warning'
							],
							'dropdownOptions' => [
								'label' => 'Export All',
								'class' => 'btn bg-purple'
							]
						]).
						Html::a('<i class="glyphicon glyphicon-repeat"></i>', [''],
						['data-pjax'=>1, 'class'=>'btn btn-default', 'title'=>'Reset Grid']).
						'{toggleData}'
						
					],
				],
				
				'striped' => true,
				'condensed' => true,
				'responsive' => true, 
				'responsiveWrap'=>false,			
				'panel' => [
					'type' => 'primary', 
					'heading' => '<i class="glyphicon glyphicon-list"></i> Sales listing',
					'before'=>'<em>* Resize table columns just like a spreadsheet by dragging the column edges.</em>',
					'after'=>false,
				],
				'rowOptions' => function ($model, $index, $widget, $grid){
				  if($model->status == 'isdue'){
					 return ['class'=>'bg-warning'];
				 }
				 if($model->status == 'completed'){
					 return ['class'=>'bg-success'];
				 }
				 
				 },
			])?>
		</div>
	</div>


<?php Modal::begin([
    "id"=>"ajaxCrudModal",
    "footer"=>"",// always need it for jquery plugin
])?>
<?php Modal::end(); ?>
