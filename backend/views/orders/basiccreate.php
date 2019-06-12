<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use backend\models\CompanyBranches;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model backend\models\Orders */
/* @var $form yii\widgets\ActiveForm */

//echo $model->settings->bonus_redemption;
?>
<style>
.modal-header{
	background:#D81B60;
	color:#fff;
}

.redeem-group input[type="checkbox"] {
    display: none;
}

.redeem-group input[type="checkbox"] + .btn-group > label span {
    width: 20px;
}

.redeem-group input[type="checkbox"] + .btn-group > label span:first-child {
    display: none;
}
.redeem-group input[type="checkbox"] + .btn-group > label span:last-child {
    display: inline-block;   
}

.redeem-group input[type="checkbox"]:checked + .btn-group > label span:first-child {
    display: inline-block;
}
.redeem-group input[type="checkbox"]:checked + .btn-group > label span:last-child {
    display: none;   
}
</style>

<div class="row">
<div class="orders-form">
	
	<?php if (!Yii::$app->request->isAjax){ ?>
	<div class="container" style="background:#ffffff">
		<h2> Add New Sale </h2>
		<hr>
	<?php } ?>
		<?php $form = ActiveForm::begin(); ?>
		<div class="col-lg-12">
			<div class="<?= (yii::$app->user->can('manager') || yii::$app->user->can('Admin'))?'col-lg-6':'col-lg-12'?> ">
			<?= $form->field($model, 'order_date')->widget(DatePicker::classname(), [
			'options' => ['placeholder' => 'Enter Order date ...'],
			'readonly'=>true,
			'pluginOptions' => [
				'autoclose'=>true,
				'format' => 'yyyy-mm-dd',
				
				]
			]);?>
			</div>
			<?php if(yii::$app->user->can('manager') || yii::$app->user->can('Admin')){?>
				<div class="col-lg-6">
				<?= $form->field($model, 'branch_id')->widget(Select2::classname(), [
								'data' => ArrayHelper::map(CompanyBranches::find()->where(['company_id'=>$model->company_id])->orderBy(['branch_name' => SORT_ASC])->all(),'id','branch_name'),
								'language' => 'en',
								'options' => ['multiple' =>false, 'placeholder' => 'Select branch'],
								'pluginOptions' => [
									'allowClear' => true,
									'tabindex' => '-1',
								],
							])->label('Change Branch.');
						?>
				</div>

			<?php } ?>
		</div>
		<div class="col-lg-12">
			<div class="col-lg-6">
				<?= $form->field($model, 'customer_contact')->textInput(['type'=>'tel','id'=>'customercontact','class'=>'numeric form-control','maxlength'=>10])->label('Customer Contact without +91') ?>
			</div>
			<div class="col-lg-6">
				<?= $form->field($model, 'customer_name')->textInput(['id'=>'customername']) ?>
			</div>
		</div>
		
		<div class="col-lg-12">
			<div class="col-lg-6">
				<?= $form->field($model, 'total_amount')->textInput(['maxlength' => true,'type'=>'tel','class'=>'numeric form-control']) ?>
			</div>
			<div class="col-lg-6">
				<?= $form->field($model, 'weight')->textInput(['type'=>'tel','class'=>'numeric form-control','onclick'=>'this.select()'])  ?>
			</div>				
		</div>
		<?php if($model->settings->bonus_redemption =='yes'){?>
			<div class="col-lg-12">
				<div class="col-lg-6 no-gutter">
					<h4 class="avlpoints text-success" style="display:none">Available Points:<span class="pull-right badge bg-navy"><i class="fa fa-gift"></i>&nbsp;<strong id="bonus"></strong></span></h4>
					<div style="clear:both"></div>
					<div class="col-lg-8 redeem-group" style="display:none;margin-bottom:15px">
						<input type="checkbox" name="fancy-checkbox-primary" id="fancy-checkbox-primary" autocomplete="off" />
						<div class="[ btn-group ]">
							<label for="fancy-checkbox-primary" class="[ btn btn-primary ]">
								<span class="[ glyphicon glyphicon-ok ]"></span>
								<span> </span>
							</label>
							<label for="fancy-checkbox-primary" class="[ btn bg-purple ]">
								Redeem Points
							</label>
						</div>
					</div>
					<div class="col-lg-4">
						<?= $form->field($model, 'points')->textInput(['type'=>'tel','id'=>'redeem','onclick'=>'this.select()','class'=>'numeric form-control','style'=>'display:none'])->label(false) ?>
					</div>
					
				</div>
			</div>
		<?php } ?>
		<div class="col-lg-12">			
			<div class="col-lg-12">
				<?= $form->field($model, 'order_note')->textarea(['rows' => '2'])  ?>
			</div>
		</div>
	  
		<?php if (!Yii::$app->request->isAjax){ ?>
			<div class="form-group">
				<?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
			</div>
		<?php } ?>

		<?php ActiveForm::end(); ?>
    <?php if (!Yii::$app->request->isAjax){ ?>
	</div>
	<?php } ?>
</div>
</div>
<?php 
$getusername = Url::to(['orders/get-customername']);
$scripts = <<< JS

$(function(){
	var maxpoints = 0;
	$(".numeric").numeric();
	
	$(document).on('click','#fancy-checkbox-primary',function(){
		if($(this).is(":checked")){
			$('#redeem').val(maxpoints);
			$('#redeem').show();
		}else{
			
			$('#redeem').hide();
			$('#redeem').val('');
		}
		
	});
	$('#customercontact').on('input',function(){
		var contact = $(this).val();
		if(contact.length ==10){
			$.post( '$getusername', { number:contact })
		  .done(function( data ) {
			  $('#customername').val(data.name);
			  if(data.bonus>0){
				maxpoints = data.bonus;
				$('.avlpoints,.redeem-group').slideDown();
				$('#bonus').html(data.bonus);
			  }else{
				$('.avlpoints,.redeem-group').slideUp();
				$('#redeem').val('');
			  }
			  
		  })
		  .fail(function() {
			alert( "error" );
		  });
		  
		}
	});
})
JS;
$this->registerJs($scripts);
?>