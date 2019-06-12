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

<div class="filter">
	
    <?php $form = ActiveForm::begin(['method'=>'GET']); ?>
		<div class="col-lg-6 col-xs-12">
			
			<?= $form->field($model, 'mode')->textInput()->label('Mode');
					?>
		</div>
		
		
		
		<div class="col-lg-12 col-xs-12">
		<hr style="border-color:#777">
		</div>
		<div class="col-lg-12 col-xs-12">
			<div class="form-group text-center">
				<?= Html::submitButton('Filter', ['class' => 'btn btn-primary ']) ?>
			</div>
		</div>
    <?php ActiveForm::end(); ?>

<!-- search -->
</div><!-- search -->
