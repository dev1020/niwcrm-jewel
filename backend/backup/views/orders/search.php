<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use common\models\User;

/* @var $this yii\web\View */
/* @var $model backend\models\Orders */
/* @var $form ActiveForm */
?>

<div class="search">
	
    <?php $form = ActiveForm::begin(['method'=>'GET']); ?>
		<div class="col-lg-6 col-xs-6">
        <?= $form->field($model, 'order_boy_id')->widget(Select2::classname(), [
							'data' => ArrayHelper::map(User::find()->where(['usertype'=>'backenduser'])->orderBy(['id' => SORT_ASC])->all(),'id','username'),
							'language' => 'en',
							'options' => ['placeholder' => 'Select a Waiter/DelvBoy'],
							'pluginOptions' => [
								'allowClear' => true,
							]
						])->label('Waiter/DelvBoy Id');
					?>
		</div>
		<div class="col-lg-6 col-xs-6">
		<label class="control-label" for="orderssearch-order_boy_id">Waiter/DelvBoy Id</label>
		<?= Html::activeDropDownList($model, 'order_type', ['table'=>'table','delivery'=>'delivery'],['class'=>'form-control']) ?>
		
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
