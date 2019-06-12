<?php
use yii\helpers\Url;

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
        'attribute'=>'bposts_bpost_id',
		'value'=> 'bpostsBpost.bpost_title',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'categories_category_id',
		'value'=> 'categoriesCategory.category_name',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'packages_package_id',
		'value'=> 'packagesPackage.package_name',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'valid_from',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'valid_upto',
    ],
	
    [
        'class' => 'kartik\grid\ActionColumn',
		'visible' => false,
    ],

];   