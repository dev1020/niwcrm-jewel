<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model backend\models\Orders */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="orders-form">
	
	<div class="row">
		
				<hr>
		
		<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
			<div class="col-lg-12">
				
				<?= $form->field($model, 'excel_file',[
												'template' => "{label}<div class='col-md-7 col-xs-12'>{input}</div>{hint}{error}",
												'labelOptions' => ['class' =>'col-md-5 col-xs-12 ']
								])->fileInput(['class'=>'upload','id'=>'excel'])->label('Excel File ( .xls,.xlsx )') ?>
			
			
			</div>
		

		<?php ActiveForm::end(); ?>
		
    </div>
</div>