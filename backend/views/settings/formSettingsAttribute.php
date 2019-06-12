<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Coupons */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="coupons-form">

    <?php $form = ActiveForm::begin(); ?>
	<div class="col-lg-12">
		<div class="col-lg-12">
			<?= $form->field($model, 'settings_attribute_label',[
												'template' => "{label}<div class='col-md-6'>{input}</div>{hint}{error}",
												'labelOptions' => [ 'class' => 'col-md-6 ' ]
											])->textInput(['maxlength' => true]) ?>
		</div>
		<div class="col-lg-12">
			<?= $form->field($model, 'settings_attribute_type',[
												'template' => "{label}<div class='col-md-5'>{input}</div>{hint}{error}",
												'labelOptions' => [ 'class' => 'col-md-7 ' ]
								])->dropDownList([ 'textInput' => 'Text Input', 'fileInput' => 'File', ]) ?>
		<div>
	<div>
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
