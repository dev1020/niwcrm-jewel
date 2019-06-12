<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\CustomersLog */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="customers-log-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'cust_id')->textInput() ?>

    <?= $form->field($model, 'log_date')->textInput() ?>

    <?= $form->field($model, 'start_session_time')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'end_session_time')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'time_spent')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->dropDownList([ 'open' => 'Open', 'closed' => 'Closed', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'session_no')->textInput(['maxlength' => true]) ?>

  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
