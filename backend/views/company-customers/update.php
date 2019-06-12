<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use backend\models\CompanyCustomers;
use backend\models\Customers;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model backend\models\CompanyCustomers */
?>
<div class="company-customers-form">

    <?php $form = ActiveForm::begin(); ?>

    <h3><span class="label label-info"><?= 'C'.$model->cust->id ?></span> <?= $model->cust->name ?> </h3>

    <?= $form->field($model, 'customer_number')->textInput() ?>
	
	<?php $url = \yii\helpers\Url::to(['companycustomerslist']);
 
	// Get the initial city description
	$custDesc = empty($model->introducer_id) ? '' : Customers::findOne($model->introducer_id)->name;
	 
	echo $form->field($model, 'introducer_id')->widget(Select2::classname(), [
		'initValueText' => $custDesc, // set the initial display text
		'options' => ['placeholder' => 'Search for a Customer ...'],
	'pluginOptions' => [
		'allowClear' => true,
		'minimumInputLength' => 2,
		'language' => [
			'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
		],
		'ajax' => [
			'url' => $url,
			'dataType' => 'json',
			'data' => new JsExpression('function(params) { return {q:params.term}; }')
		],
		
	],
	]);
		
	?>
	
  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($customermodel->isNewRecord ? 'Create' : 'Update', ['class' => $customermodel->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
