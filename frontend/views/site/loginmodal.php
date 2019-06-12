<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;


$this->title = Yii::$app->name.' '.'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
.modal-header{
	display:none;
}
.modal-footer{
	display:none;
}
.modal-content{
	border-radius: 6px;
	/*background:#e0e0e0;*/
	    background: #08266F;
	  /*  background: #F05199;*/
    border: 2px solid #fff;
}
}
</style>
   

   
	<div class="row">
		<div class="col-lg-12 ">
			<div class="col-lg-5 hidden-xs " style="min-height:350px; background:#F05199">
				<div class="col-lg-12" >
					<h1 style="color:#fff" class="text-left">Login</h1>
					<label style="color:#fff;font-size:16px ; margin-top:20px" >"Get Access To Your Dashboard, Registered Business and all."</label>
					
				</div>
				<div style="position:absolute;left:1%;width:98%;bottom:35px;padding-top:8px;background:#fff" class="text-center"><img src="<?= Url::base()?>/images/logo.png" alt="Logo"></div>
			</div>
			<div class="col-lg-7">

				<?php $form = ActiveForm::begin(['id' => 'login-form' ,'enableAjaxValidation' => false,]); ?>

					<?= $form->field($model, 'contact_number')->textInput() ?>

					<?= $form->field($model, 'password')->passwordInput() ?>

					<?= $form->field($model, 'rememberMe')->checkbox() ?>
					
					
					<div class="form-group has-error">
						<p id="responseText"> </p>
					</div>
					<div style="color:#999;margin:1em 0">
						If you forgot your password you can <?= Html::a('reset it', ['site/password-reset-options']) ?>.
					</div>
					
					<div class="form-group">
						<?= Html::submitButton('Login', ['class' => 'btn btn-primary btn-block', 'name' => 'login-button','id'=>'loginsubmit']) ?>
					</div>
					
					<div class="form-group">
						<?=  Html::a('New To SALTLAKE.IN ? Sign Up', ['site/signup'],['title'=> 'Login','class'=> 'btn btn-info btn-block'])?>
					</div>
					

				<?php ActiveForm::end(); ?>
			</div>
		</div>
	</div>
<?php $script = <<< JS
$('form#login-form').on('beforeSubmit',function(e){
	
	$('#loginsubmit').html('<i class="fa fa-circle-o-notch fa-spin" style="font-size:24px"></i> Login').attr("disabled", true);
	$('#responseText').html('');
	//$('.field-loginform-username,.field-loginform-password').removeClass('has-error');
	var form = $(this);
	
	  $.post(
		form.attr("action"),
		form.serialize()
	)
	.done(function(result){
		$('#loginsubmit').html('Login').attr("disabled", false);
		if(result.response == 'invalid'){
			$('.field-loginform-username,.field-loginform-password').removeClass('has-success').addClass('has-error');
			$('#responseText').addClass('help-block help-block-error').html("<strong>Invalid user name or Password</strong>");
			
		}
	});
	
	return false;
});
JS;
$this->registerJs($script);
?>

