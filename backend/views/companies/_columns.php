<?php
use yii\helpers\Url;
use yii\helpers\Html;

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
        'attribute'=>'company_name',
		'width'=>'200px'
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'company_address',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'company_contact',
		'width'=>'200px'
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'created_by',
		'value'=>'createdBy.username',
		'width'=>'200px'
    ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'created_at',
    // ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
		'width'=>'200px',
        'vAlign'=>'middle',
        'template' => '{settings} {view} {update} {delete}',
        'urlCreator' => function($action, $model, $key, $index) { 
                return Url::to([$action,'id'=>$key]);
        },
		'buttons'=>[
			'settings' => function ($url, $model, $key) {
				$url = Url::toRoute(['/company-settings/update', 'id'=>$model->id]);
				return Html::a('<span class="fa fa-cog fa-spin"></span>', $url,['title'=>'Change Settings','title'=>'Settings','data-toggle'=>'tooltip','role'=>'modal-remote','class'=>'btn btn-warning ']);
			},
		], 
        'viewOptions'=>['role'=>'modal-remote','title'=>'View','data-toggle'=>'tooltip', 'class'=>'btn btn-info'],
        'updateOptions'=>['role'=>'modal-remote','title'=>'Update', 'data-toggle'=>'tooltip','class'=>'btn btn-success '],
        'deleteOptions'=>['role'=>'modal-remote','title'=>'Delete','class'=>'btn btn-danger ', 
                          'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                          'data-request-method'=>'post',
                          'data-toggle'=>'tooltip',
                          'data-confirm-title'=>'Are you sure?',
                          'data-confirm-message'=>'Are you sure want to delete this item'], 
    ],

];   