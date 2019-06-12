<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\grid\GridView;

use backend\models\Customers;
use backend\models\CompanyCustomers;

return [
    [
        'class' => 'kartik\grid\CheckboxColumn',
        'width' => '20px',
    ],
    [
        'class' => 'kartik\grid\SerialColumn',
        'width' => '30px',
    ],
        // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'id',
    // ],
   
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'cust_id',
		//'value'=>'cust.name',
		'label'=>'Name',
		'format' => 'raw',
		'value'=>function ($data){
			$today_date = strtotime(date('Y-m-d'));
			$created_date = strtotime($data->created_date);
				if(($today_date - $created_date)/60/60/24 <= 5){
					$label = '<span class="label label-success">New</span>';
				}else{
					$label = '';
				}
			 if(isset($data->cust->name)){
				 return '<span>'.$data->cust->name.'</span>'.$label;
			 }else{
				 return '<span>'.$data->cust->contact.'</span>'.$label;
			 }
        },
		'filterType'=>GridView::FILTER_SELECT2,
		'filter'=>ArrayHelper::map(CompanyCustomers::find()->joinWith('cust')->where(['company_id'=>$company])->all(), 'cust_id', 'cust.name'), 
		'filterWidgetOptions'=>[
			'pluginOptions'=>['allowClear'=>true],
		],
		'filterInputOptions'=>['placeholder'=>'Customers'],
		'group'=>false,  // enable grouping
    ],
    
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'customer_number',
		'label'=>'Number',
    ],
	[
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'created_date',
		'headerOptions' => ['class' => 'hidden-xs'],
        'contentOptions' => ['class' => 'hidden-xs'],
			'filterInputOptions' => [
                'class' => 'form-control hidden-xs', 
            ],
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign'=>'middle',
		'width'=>'100px',
        
		'template' => '{view} {update}',
        'urlCreator' => function($action, $model, $key, $index) { 
                return Url::to([$action,'id'=>$key]);
        },
		'buttons'=>[
			'view' => function ($url, $model, $key) {
				$url = Url::toRoute(['/company-customers/customer-stats', 'id'=>$model->cust_id]);
				return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url,['title'=>'View Stats','role'=>'modal-remote','class'=>'btn btn-primary']);
			},
			'update' => function ($url, $model, $key) {
				$url = Url::toRoute(['/company-customers/update', 'id'=>$model->id]);
				return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url,['title'=>'Edit Customer','role'=>'modal-remote','class'=>'btn btn-success']);
			},
		], 
         
    ],

];   