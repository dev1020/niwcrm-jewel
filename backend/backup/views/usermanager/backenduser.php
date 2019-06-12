<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Suppliers';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
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
		<h3><strong> Users </strong> <?= Html::a('Add Employee', ['create'], ['class' => 'btn btn-success right']) ?><?= Html::a('Add Customer by OTP', ['createbyotp'], ['class' => 'btn btn-primary right']) ?></h3>
			<?= GridView::widget([
				'dataProvider' => $dataProvider,
				'columns' => [
					['class' => 'yii\grid\SerialColumn'],
					
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
