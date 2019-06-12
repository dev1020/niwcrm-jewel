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
        'attribute'=>'company_id',
		'value'=>function ($data) {
             return isset($data->company->company_name)?$data->company->company_name:'N.A';
        },
		'visible'=>Yii::$app->user->can('Admin'),
    ],
	[
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'branch_id',
		'value'=>function ($data) {
             return isset($data->branch->branch_name)?$data->branch->branch_name:'N.A';
        },
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'username',
    ],
	[
        'class'=>'\kartik\grid\DataColumn',
        'label'=>'Role',
		'value'=>function ($data) {
			$userrole = '';
			$roles = json_decode(json_encode(Yii::$app->authManager->getRolesByUser($data->id)), true); 
			array_shift($roles);
			foreach($roles as $role){
				$userrole .= $role['name'].' ';
			}
			return $userrole;
			//return Yii::$app->authManager->getRolesByUser(Yii::$app->user->identity->id);
             //return isset($data->company->company_name)?$data->company->company_name:'N.A';
        },
		'contentOptions' =>['style' => 'text-align:center;font-size:18px'],
		'headerOptions' =>['style' => 'text-align:center;'],
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
        'template' => '{settings}',
        'urlCreator' => function($action, $model, $key, $index) { 
                return Url::to([$action,'id'=>$key]);
        },
		'buttons'=>[
			'settings' => function ($url, $model, $key){
				$url = Url::toRoute(['stats', 'id'=>$model->id]);
				return Html::a('<span class="glyphicon glyphicon-stats"></span>', $url,['title'=>'Change Settings','title'=>'Stats','data-toggle'=>'tooltip','target'=>'_blank','data-pjax'=>'0','class'=>'btn bg-purple ']);
			},
		], 
    ],

];   