<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;


/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?><div class="row">
		<div class="col-md-2">
			<div class="box box-info">
				<div class="box-header">
					<h3 class="box-title"><strong>Quick Links</strong></h3>
				</div>
				<div class="box-body sidebar">
					<ul class="sidebar-menu">
						<li>
							<?= Html::a('Employees', ['index']) ?>
						</li>
						<li>
							<?= Html::a('Customers', ['customers']) ?>
						</li>
						<li>
							<?= Html::a('Add Employee', ['create']) ?>
						</li>
						<li>
							<?= Html::a('Add Customer by OTP', ['createbyotp']) ?>
						</li>
					</ul>
				</div><!-- /.box-body -->
			</div>
		</div>
		<div class="col-md-10 ">
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
			[
                'attribute'=>'profilepic',
                'value'=>($model->profilepic)?(Url::to('@frontendimage'.'/'.$model->profilepic)):(Url::to('@frontendimage'.'/noimage.png')) ,
				'format' => ['image',['width'=>'100','height'=>'100']]
            ],
            'first_name',
            'last_name',
            'username',
            //'auth_key',
            //'password_hash',
            //'password_reset_token',
            'email:email',
            'contact_number',
			'address',
            //'status',
			[
                'attribute'=>'created_at',
                'value'=>Yii::$app->formatter->asDate($model->created_at, 'dd-MM-yyyy'),
            ],
            //'updated_at',
        ],
    ]) ?>

</div>
</div>
