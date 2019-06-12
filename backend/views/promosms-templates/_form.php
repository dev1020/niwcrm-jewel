<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\PromosmsTemplates */
/* @var $form yii\widgets\ActiveForm */
?>
<style>
.counter{
	position:absolute;
	right:10px;
}
</style>
<div class="promosms-templates-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'sms_title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sms_body')->textarea(['rows' => 6,'id'=>'templatebody'])->label('SMS body <span class="pull-right text-danger counter"></span>') ?>
	<?= $form->field($model, 'smscount')->textInput(['type' => 'tel','id'=>'smscount','readonly'=>true]) ?>
  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
<?php $script = <<< JS
$(function(){
	
	countsms($('#templatebody'));
	
	$('#templatebody').on('input',function(){
		var element = $(this);
		countsms(element);
	});
	
	function countsms(e)
	{
		var chars = e.val().length,
            messages = Math.ceil(chars / 160),
            remaining = messages * 160 - (chars % (messages * 160) || messages * 160);
		
		$('.counter').html(remaining+' / '+messages);
		$('#smscount').val(messages);
	}
});

JS;

$this->registerJs($script);
?>