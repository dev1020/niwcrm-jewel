<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;


$this->title = 'SALTLAKE Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lockscreen">
		<div class="lockscreen-wrapper ">
		  <div class="lockscreen-logo ">
			<?= Html::img('@web/images/logo.png', ['alt'=>'SALTLAKE'])?>
		  </div>
		  <!-- User name -->
		  <div class="lockscreen-name"><?= Yii::$app->user->identity->username ?></div>

		  <!-- START LOCK SCREEN ITEM -->
		  <div class="lockscreen-item">
			<!-- lockscreen image -->
			<div class="lockscreen-image">
			  <?= Html::img('@web/images/user.jpg', ['alt'=>'Saltlake.in'])?>
			</div>
			<!-- /.lockscreen-image -->

			<!-- lockscreen credentials (contains the form) -->
			<form class="lockscreen-credentials">
			  <div class="input-group">
				<input type="password" class="form-control" placeholder="password">

				<div class="input-group-btn">
				  <button type="button" class="btn"><i class="fa fa-arrow-right text-muted"></i></button>
				</div>
			  </div>
			</form>
			<!-- /.lockscreen credentials -->

		  </div>
		  <!-- /.lockscreen-item -->
		  <div class="help-block text-center">
			Enter your password to retrieve your session
		  </div>
		  <div class="text-center">
			<a href="<?= Url::to(['site/logout'])?>">Or sign in as a different user</a>
		  </div>
		  <div class="lockscreen-footer text-center">
			&copy; <?= date('Y') ?> Copyright.<span class="">1.1.0 <a href="https://www.saltlake.in"><span class="label m-l-sm " style="background:#1B88B7">SALTLAKE.IN WEB SERVICES LLP</span></a></span>
				
		  </div>
		</div>
        
</div>


