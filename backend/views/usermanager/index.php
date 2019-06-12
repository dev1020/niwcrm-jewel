<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use backend\models\Companies;
use kartik\select2\Select2;

/* @var $this yii\web\View  amityInfinity*/
/* @var $dataProvider yii\data\ActiveDataProvider amityInfinity*/

$this->title = 'Employees';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row" >
	
	<div class="col-md-12 " style="background:#fff">
		<h3><strong> Employees List </strong> <?= Html::a('Add User', ['create'], ['class' => 'btn btn-success right']) ?></h3>
			<?= GridView::widget([
				'dataProvider' => $dataProvider,
				'filterModel' => $searchModel,
				'columns' => [
					['class' => 'yii\grid\SerialColumn'],
					[
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'company_id',
		'value'=>function ($data) {
             return isset($data->company->company_name)?$data->company->company_name:'N.A';
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
					'branch_id',
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
					'first_name',
					'last_name',
					'username',
					/*'auth_key',
					[
						'label' => 'Password',
						'attribute' => 'password_hash',
					],*/
					
					// 'password_reset_token',
					 'email:email',
					 'contact_number',
					 
					 
					// 'status',
					// 'created_at',
					// 'updated_at',

					['class' => 'yii\grid\ActionColumn'],
				],
			]); ?>
	</div>
</div>
