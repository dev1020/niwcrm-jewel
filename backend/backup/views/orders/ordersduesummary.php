<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap\Modal;
use backend\models\Customers;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset; 
use johnitvn\ajaxcrud\BulkButtonWidget;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\OrdersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Dues';
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);
?>


<div class="orders-index">
    <div id="ajaxCrudDatatable">
        <?=GridView::widget([
            'id'=>'crud-datatable',
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'pjax'=>true,
            'columns' => [
					
					[
						'class'=>'\kartik\grid\DataColumn',
						'attribute'=>'due_amount',
						'pageSummary'=>true,
						//'format' => 'decimal',
						'hAlign' => 'left',
						'format' => ['decimal', 2],
						'pageSummary' => true
					],
					
					[
						'class'=>'\kartik\grid\DataColumn',
						'attribute'=>'total_amount',
						'pageSummary'=>true,
						//'format' => 'decimal',
						'hAlign' => 'left',
						'format' => ['decimal', 2],
						'pageSummary' => true
						
					],
					[
						'attribute'=>'cust_id', 
						'width'=>'310px',
						'format' => 'raw',
						'value'=>function ($model, $key, $index, $widget) { 
							//return ucwords($model->cust->name);
							return Html::a(ucwords($model->cust->name), ['view', 'id' => $model->id],['role'=>'modal-remote','data-toggle'=>'tooltip','title'=>'Order details']);
					   
						},
						'filterType'=>GridView::FILTER_SELECT2,
						'filter'=>ArrayHelper::map(Customers::find()->asArray()->all(), 'id', 'name'), 
						'filterWidgetOptions'=>[
							'pluginOptions'=>['allowClear'=>true],
						],
						'filterInputOptions'=>['placeholder'=>'Customers'],
						'group'=>false,  // enable grouping
						'contentOptions' =>['style' => 'text-align:center;font-size:18px'],
					],
					[
						'class'=>'\kartik\grid\DataColumn',
						'attribute'=>'order_date',
					],
					
				],
            'toolbar'=> [
                ['content'=>
                    Html::a('<i class="glyphicon glyphicon-repeat"></i>', [''],
                    ['data-pjax'=>1, 'class'=>'btn btn-default', 'title'=>'Reset Grid']).
                    '{toggleData}'.
                    '{export}'
                ],
            ],
			'showPageSummary' => true,		
            'striped' => true,
            'condensed' => true,
            'responsive' => true, 
			'responsiveWrap'=>false,			
            'panel' => [
                'type' => 'primary', 
                'heading' => '<i class="glyphicon glyphicon-list"></i> Dues Details',
                'before'=>'<em>* Resize table columns just like a spreadsheet by dragging the column edges.</em>',
                'after'=>false,
            ],
			
        ])?>
    </div>
</div>

<?php Modal::begin([
    "id"=>"ajaxCrudModal",
    "footer"=>"",// always need it for jquery plugin
])?>
<?php Modal::end(); ?>
