<?php
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use backend\models\Companies;
use kartik\select2\Select2;
use kartik\grid\GridView;

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
        'attribute'=>'company_id',
		'value'=>function ($data) {
             return $data->company->company_name;
        },
		'filterType'=>GridView::FILTER_SELECT2,
		'filter'=>ArrayHelper::map(Companies::find()->orderBy(['company_name'=>SORT_ASC])->asArray()->all(), 'id', 'company_name'), 
		'filterWidgetOptions'=>[
			'pluginOptions'=>['allowClear'=>true],
		],
		'filterInputOptions'=>['placeholder'=>'Companies'],
		'group'=>true,  // enable grouping
		'contentOptions' =>['style' => 'text-align:center;font-size:18px'],
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'branch_name',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'branch_location',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'created_by',
        'value'=>function ($data) {
             return $data->createdBy->username;
        },
    ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'created_at',
    // ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign'=>'middle',
        'urlCreator' => function($action, $model, $key, $index) { 
                return Url::to([$action,'id'=>$key]);
        },
        'viewOptions'=>['role'=>'modal-remote','title'=>'View','data-toggle'=>'tooltip'],
        'updateOptions'=>['role'=>'modal-remote','title'=>'Update', 'data-toggle'=>'tooltip'],
        'deleteOptions'=>['role'=>'modal-remote','title'=>'Delete', 
                          'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                          'data-request-method'=>'post',
                          'data-toggle'=>'tooltip',
                          'data-confirm-title'=>'Are you sure?',
                          'data-confirm-message'=>'Are you sure want to delete this item'], 
    ],

];   