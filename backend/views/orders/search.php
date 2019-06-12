<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use backend\models\CompanyBranches;

/* @var $this yii\web\View */
/* @var $model backend\models\Orders */
/* @var $form ActiveForm */
?>

<div class="search">
	
    <?php $form = ActiveForm::begin(['method'=>'GET']); ?>
		<div class="col-lg-6 col-xs-12">
        <?= $form->field($model, 'branch_id')->widget(Select2::classname(), [
							'data' => ArrayHelper::map(CompanyBranches::find()->where(['company_id'=>$company_id])->orderBy(['id' => SORT_ASC])->all(),'id','branch_name'),
							'language' => 'en',
							'options' => ['placeholder' => 'Select Branch'],
							'pluginOptions' => [
								'allowClear' => true,
							]
						])->label('Filter By Branches');
					?>
		</div>
		
		<div class="col-lg-6 col-xs-12">
        <?= $form->field($model, 'session_nos')->textInput()->label('Filter By Invoice');
					?>
		</div>
		
		<div class="col-lg-12 col-xs-12">
		<hr style="border-color:#777">
		</div>
		<div class="col-lg-12 col-xs-12">
			<div class="form-group text-center">
				<?= Html::submitButton('Search', ['class' => 'btn btn-primary ']) ?>
			</div>
		</div>
    <?php ActiveForm::end(); ?>

<!-- search -->
</div><!-- search -->
