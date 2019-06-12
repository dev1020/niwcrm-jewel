<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use backend\models\Categories;

/* @var $this yii\web\View */
/* @var $model backend\models\Orders */
$this->title = 'Sales Categorywise';
$this->params['breadcrumbs'][] = $this->title;
?>

<style>
.modal-header{
	display:none;
}
.orders-view .page-header{
	font-size:18px;
}
.orders-view thead tr{background-color: #F03389;color: #fff;}

@media only screen and (max-width: 600px) {
  .orders-view .orderdetails{font-size:0.7em !important}
}
</style>

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
						'attribute'=>'order_date',
					],
					[
						'class'=>'\kartik\grid\DataColumn',
						'attribute'=>'category_id',
						'value'=>function ($model, $key, $index, $widget) { 
						    if(Categories::find()->where(['category_id'=>$model->category_id])->exists()){
						        return Categories::findOne($model->category_id)->category_name;
						    }else{
						        return 'N.A';
						    }
							
						},
						
						'filterType'=>GridView::FILTER_SELECT2,
						'filter'=>ArrayHelper::map(Categories::find()->asArray()->all(), 'category_id', 'category_name'), 
						'filterWidgetOptions'=>[
							'pluginOptions'=>['allowClear'=>true],
						],
						'filterInputOptions'=>['placeholder'=>'Categories..'],
						'group'=>false, 
						'contentOptions' => ['class' => 'text-center'],
						'headerOptions' => ['class' => 'text-center'],					
					],
					[
						'class'=>'\kartik\grid\DataColumn',
						'attribute'=>'total_price',
						'pageSummary'=>true,
						//'format' => 'decimal',
						'hAlign' => 'right',
						'format' => ['decimal', 2],
						'pageSummary' => true
						
					],
					[
						'class'=>'\kartik\grid\DataColumn',
						'attribute'=>'catcount',
						'contentOptions' => ['class' => 'text-center'],
						'headerOptions' => ['class' => 'text-center']
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
                'heading' => '<i class="glyphicon glyphicon-list"></i> Sales By Category',
                'before'=>'<em>* Resize table columns just like a spreadsheet by dragging the column edges.</em>',
                'after'=>false,
            ],
			
        ])?>
    </div>
</div>
