<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use backend\models\Companies;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model backend\models\CompanyBranches */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="company-branches-form">

    <?php $form = ActiveForm::begin(); ?>

<?= $form->field($model, 'company_id')->widget(Select2::classname(), [
				'data' => ArrayHelper::map(Companies::find()->orderBy(['company_name'=>SORT_ASC])->asArray()->all(), 'id', 'company_name'),
	            'options' => ['multiple' => false, 'placeholder' => 'Select Company'],
				'pluginOptions' => [
					'allowClear' => true,
					'tabindex' => 'off',
				],
			]) ?>

    <?= $form->field($model, 'branch_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'branch_location')->textarea(['rows' => 6]) ?>

  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
