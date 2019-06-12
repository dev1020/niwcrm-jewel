<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\components\SettingsGetter;

$settings_getter = new SettingsGetter();

$this->title = Yii::$app->name;
$this->params['breadcrumbs'][] = $this->title;
?>

	<div class="login-box">
		<div class="login-logo" >
			<?= Html::img(('@web/images/logo.png'), ['alt'=>Yii::$app->name])?>
		</div>
		

		<div class="login-box-body">
		  
			<?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

			   <?= $form->field($model, 'contact_number')->textInput() ?>

				<?= $form->field($model, 'password')->passwordInput() ?>

				<?= $form->field($model, 'rememberMe')->checkbox() ?>

				<div class="form-group">
					<?= Html::submitButton('Login', ['class' => 'btn btn-lg btn-primary btn-block', 'name' => 'login-button']) ?>
				</div>
				
				<div class="text-center "><?= Html::a('<strong>Forgot password?</strong>', ['site/request-password-reset']) ?></div><br>
				

			<?php ActiveForm::end(); ?>
		
			<div class="text-center">
						
				&copy; <?= date('Y') ?> Copyright.<span class="">v-1.2.0 <a href="https://www.saltlake.in"><span class="label m-l-sm " style="background:#1B88B7">SALTLAKE.IN WEB SERVICES LLP</span></a></span>
				
			</div>
		</div>
        
	</div>



