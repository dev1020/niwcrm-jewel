<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ResetPasswordForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Reset password';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-reset-password col-lg-4 col-lg-offset-4" style="margin-top:25px">
		<div class="col-lg-12 text-center">	
			<a class="" href="" class="logo">
				<?= Html::img('@web/images/logo.png', ['alt'=>yii::$app->name])?>
			</a>
		</div>
		<div class="col-lg-12 text-center">		
		  <strong><?= Html::encode($this->title) ?></strong>
		</div>

    <div class="col-lg-12">
		<p>Please choose your new password:</p>

    
            <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>

                <?= $form->field($model, 'password')->passwordInput(['autofocus' => true]) ?>

                <div class="form-group">
                    <?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
                </div>

            <?php ActiveForm::end(); ?>
		<div class="text-center">
						
			<!--<span class="pull-right">1.2.0 <a href="http://www.amityiit.com"><span class="label m-l-sm " style="background:#1B88B7">AmityInfinity Infotech</span></a></span>
			&copy; <?= date('Y') ?> Copyright.-->
		</div>
	</div>
</div>