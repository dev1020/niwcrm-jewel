<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = 'Add Employee';
$this->params['breadcrumbs'][] = ['label' => 'Employees', 'url' => ['index']];
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
			<div class="box box-info">
				<div class="box-header">
					<h3><strong> Employee </strong> <?= Html::a('Add Employee', ['create'], ['class' => 'btn btn-success right']) ?><?= Html::a('Add Customer by OTP', ['createbyotp'], ['class' => 'btn btn-primary right']) ?></h3>
				</div>
				<!--<h3><strong>Add User </strong> <?= Html::a('Users', ['index'], ['class' => 'btn btn-success right']) ?></h3>-->
				
				<div class="box-body ">
					<?= $this->render('_form', [
						'signupmodel' => $signupmodel,
						'usermodel' => $usermodel,
					]) ?>
				</div>
			</div>
		</div>
</div>
