<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\CustomersBonuses */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="customers-bonuses-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'cust_id')->textInput() ?>

    <?= $form->field($model, 'type')->dropDownList([ 'loyalty' => 'Loyalty', 'referral' => 'Referral', 'redeem' => 'Redeem', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'order_id')->textInput() ?>

    <?= $form->field($model, 'created_date')->textInput() ?>

    <?= $form->field($model, 'valid_upto')->textInput() ?>

    <?= $form->field($model, 'bonus_amount')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cancelled')->dropDownList([ 'yes' => 'Yes', 'no' => 'No', ], ['prompt' => '']) ?>

  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
