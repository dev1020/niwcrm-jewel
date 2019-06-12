<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use backend\models\Customers;
use yii\web\JsExpression;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model backend\models\Customers */
/* @var $form yii\widgets\ActiveForm */
?>
<style>
.image img{
	max-width:100%;
}
</style>
<div class="customers-form">

    <?php $form = ActiveForm::begin(); ?>

	<?= $form->errorSummary($model); ?>
	
	<div class="row">
		<div class="col-lg-6 image" >
					<img id="imagepreview" src="<?= ($model->customer_pic)?(Url::to('@frontendimage'.'/customers/'.$model->customer_pic)):(Url::to('@frontendimage'.'/noimage.png'))?>" alt="your image" />
					
		</div>
		<div class="col-lg-6" >
			<?= $form->field($model, 'customer_pic')->fileInput(['class'=>'imgupload','id'=>'customerpic','accept'=>'image/*',])?>		
		</div>
	</div>
	
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'contact')->textInput(['maxlength' => true]) ?>
	
	<?php $url = \yii\helpers\Url::to(['customerslist']);
 
// Get the initial city description
$custDesc = empty($model->introducer_customer_id) ? '' : Customers::findOne($model->introducer_customer_id)->name;
 
echo $form->field($model, 'introducer_customer_id')->widget(Select2::classname(), [
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
	
	<?= $form->field($model, 'gender')->dropDownList([ 'female' => 'Female', 'male' => 'Male', ]) ?>
	
		<?php
		    if($model->isNewRecord){
				
				echo $form->field($model, 'user_id')->dropDownList([  'yes' => 'Create User', 'no' =>'Not Needed' , ])->label('<h5 class="text-success"><strong>Do you Want to create User ? </strong></h5>') ;
				
				echo '<label class="text-info pull-right"><input type="checkbox" name="sendsms" value="1" checked>&nbsp; Want to Wish the Customer ? </label>';
			}
			if(!$model->isNewRecord){
				if($model->user_id==NULL){
					echo $form->field($model, 'user_id')->dropDownList([  'yes' => 'Create User', 'no' =>'Not Needed' , ])->label('<h5 class="text-success"><strong>Do you Want to create User ? </strong></h5>') ;
				}else{
					echo "<h3>Already a User.</h3>";
				}
				
			}
		?>
	
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>

<?php $script = <<< JS
$(function(){
	$(".imgupload").change(function(){
    readURL(this);
	});
	function readURL(input){

		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function (e) {
				if(input.id=="customerpic"){
					$('#imagepreview').attr('src', e.target.result);
				}          
			}
			reader.readAsDataURL(input.files[0]);
		}
	}
});
JS;
$this->registerJs($script);
?>