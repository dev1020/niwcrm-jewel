<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\CustomersServices */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="customers-services-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'cust_id')->textInput() ?>

    <?= $form->field($model, 'service_id')->textInput() ?>

    <?= $form->field($model, 'service_status')->dropDownList([ 'queue' => 'Queue', 'inprocess' => 'Inprocess', 'done' => 'Done', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'service_start_time')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'service_end_time')->textInput(['maxlength' => true]) ?>

  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
