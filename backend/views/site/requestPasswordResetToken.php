<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;


$this->title = yii::$app->name.' Reset Password';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login col-lg-4 col-lg-offset-4" style="margin-top:25px">
		<div class="col-lg-12 text-center">	
			<a class="" href="" class="logo">
				<?= Html::img('@web/images/logo.png', ['alt'=>yii::$app->name])?>
			</a>
		</div>
		<div class="col-lg-12 text-center">		
		  <strong><?= Html::encode($this->title) ?></strong>
		</div>

    <div class="col-lg-12">
		<p>Please fill out your email. A link to reset password will be sent there.</p>

    
            <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form']); ?>

                <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>

                <div class="form-group text-center">
                    <?= Html::submitButton('Send', ['class' => 'btn btn-primary']) ?>
                </div>

            <?php ActiveForm::end(); ?>
		<div class="text-center">
						
			<!--<span class="pull-right">1.2.0 <a href="http://www.amityiit.com"><span class="label m-l-sm " style="background:#1B88B7">AmityInfinity Infotech</span></a></span>
			&copy; <?= date('Y') ?> Copyright.-->
		</div>
	</div>
</div>
