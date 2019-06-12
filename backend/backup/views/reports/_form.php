<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use backend\models\Categories;
use common\models\User;
use common\components\Multilevel;
use kartik\time\TimePicker;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model backend\models\Bposts */
/* @var $form yii\widgets\ActiveForm */
?>
<style>
.select2-selection__choice{
	color: #ffffff !important;
	background: #296706 !important;
}
.select2-selection__choice__remove{
    color: #ffffff !important;	
}
.image img{max-height:150px;border:2px solid #FF5722;border-radius:5px}
.image{height:150px}
</style>
<div class="bposts-form">
	
	
    <?php $form = ActiveForm::begin(['options'=>['enctype'=>'multipart/form-data','onsubmit'=>'bpostform();','id'=>'bpost-form']]); ?>
		
		<fieldset>
			<legend><div class="col-lg-3">Customer Details:</div> <?php if(!$model->bpost_created_by){?><div class="col-lg-3"><?php echo Html::button('New Customer', [ 'class' => 'btn btn-primary btn-block', 'id'=>'new','onclick' => 'showdiv(this.id);' ]);?> </div><div class="col-lg-3"><?php echo Html::button('Existing Customer', [ 'class' => 'btn btn-success btn-block', 'id'=>'existing', 'onclick' => 'showdiv(this.id);' ]);?></div><?php } ?></legend>
				<?php if($model->bpost_created_by){?>
					<div class="col-lg-12">
						<div class="col-lg-3"> Customer Name:</div>
						<div class="col-lg-3"><strong><?= $model->bpostCreatedBy->first_name .' '. $model->bpostCreatedBy->last_name ?></strong></div>
						<div class="col-lg-3"> Contact:</div>
						<div class="col-lg-3"><strong><?= $model->bpostCreatedBy->contact_number ?></strong></div>						
						
					</div>
				<?php } else { ?>
				
				
				<div class="col-lg-12 new">
					<div class="col-lg-2">
						<label>Customer Name</label>
					</div>
					<div class="col-lg-4">
						<div class="form-group">
							<?= Html::textInput( 'customer_firstname','',['class' => 'form-control','placeholder'=>'First Name of customer','id'=>'customerfirstname']) ?>
						</div>
						<div class="form-group">
							<?= Html::textInput( 'customer_lastname','',['class' => 'form-control','placeholder'=>'Last Name of customer','id'=>'customerlastname']) ?>
						</div>
					</div>
					<div class="col-lg-2">
						<label>Contact Number</label>
					</div>
					<div class="col-lg-4">
						<div class="form-group">
							<?= Html::textInput( 'customer_number','',['class' => 'form-control','placeholder'=>'Contact of Customer','id'=>'customernumber']) ?>
						</div>
						<div class="" id="msg"></div>
					</div>
				</div>
				
				<div class="col-lg-12 existing">
					<div class="col-lg-4">
					<?= $form->field($model, 'bpost_user')->widget(Select2::classname(), [
							'data' => ArrayHelper::map(User::find()->orderBy(['id' => SORT_ASC])->all(),'id','contact_number'),
							'language' => 'en',
							'options' => ['multiple' =>false, 'placeholder' => 'Select user by phone number ...','onchange' => '$.get("' . Url::toRoute('/usermanager/viewuserdetailbycontact?') . 'id='.'"+$(this).val(), 
			function(data){
				
				$("#name").html(data.first_name+" "+data.last_name);
			});'],
							'pluginOptions' => [
								'allowClear' => true,
								'tabindex' => '-1',
							],
						])->label(false);
					?>
					</div>	
					<div class="col-lg-6 text-center">
						<h4 id="name"></h4>
					</div>
				</div>	
				<?php } ?>
				
		</fieldset>
		
		<fieldset>
			<legend>Details:</legend>
			<div class="col-lg-6">
				<div class="col-lg-12">
					<?= $form->field($model, 'bpost_title')->textInput(['maxlength' => true]) ?>
				</div>
				<div class="col-lg-12">
					<?= $form->field($model, 'bpost_place_id')->textInput(['maxlength' => true]) ?>
				</div>
				
			</div>
			 <div class="col-lg-6">
				<div class="col-lg-6 image" >
					<img id="blah" src="<?= ($model->bpost_image)?(Url::to('@frontendimage'.'/bpost/'.$model->bpost_image)):(Url::to('@frontendimage'.'/noimage.png'))?>" alt="your image" />
					
				</div>
				
				<div class="col-lg-6" >
					<div class="col-lg-12 clearfix">
					<?= $form->field($model, 'bpost_image',[
											//'template' => "{label}<div class='col-md-7 col-xs-9'>{input}</div>{hint}{error}",
											//'labelOptions' => ['class' =>'col-md-5 col-xs-3 text-right']
							])->fileInput(['id'=>'imgupload']) ?>
					</div>
					<div class="col-lg-12">
						 
						 <?= $form->field($model, 'bpost_is_featured')->checkbox(array(
											'labelOptions'=>array('style'=>'padding-top:20px;'),
											'value'=>'Y',
											'uncheck'=>'N'
											)); ?>
					</div>
				</div>
			</div>
			<div class="col-lg-12">
				<div class="col-lg-12">
					<?= $form->field($model, 'bpost_description')->textarea(['rows' => 6,'placeholder'=>'Write something brief about your bussiness']) ?>
				</div>
				
			</div>
		   

			

			<!--<?= $form->field($model, 'bpost_rating')->textInput() ?>
			<?= $form->field($model, 'bpost_created_by')->textInput() ?>
			<?= $form->field($model, 'bpost_created_at')->textInput() ?>
			<?= $form->field($model, 'bpost_updated_at')->textInput() ?>
			<?= $form->field($model, 'bpost_hitcounter')->textInput() ?>-->
			
			<div class="col-lg-12">
				<div class="col-lg-4">
					<?= $form->field($model, 'bpost_smsnumber')->textInput(['maxlength' => true]) ?>
				</div>
				
				<div class="col-lg-4">
					<?= $form->field($model, 'bpost_whatsapp')->textInput(['maxlength' => true]) ?>
				</div>
				
				<div class="col-lg-4">
					<?= $form->field($model, 'bpost_phone')->textInput(['maxlength' => true]) ?>
				</div>
				
			</div>
			<div class="col-lg-12">
				<div class="col-lg-6">
					<?= $form->field($model, 'bpost_email')->input('email',['placeholder' => "e.g name@yourdomain.com"]) ?>
				</div>
				<div class="col-lg-6">
					<?= $form->field($model, 'bpost_website')->textInput(['placeholder' => "e.g https://www.yourdomain.com",'maxlength' => true]) ?>
				</div>
				
			</div>
		</fieldset>
		
		<fieldset>
			<legend>Payments:</legend>
			<div class="col-lg-12">
				
					
					<div class="col-lg-2">
						
											
						<?= $form->field($model, 'bpost_iscash')->checkbox(array(
											'labelOptions'=>array('style'=>'padding:0px;'),
											'value'=>'Y',
											'uncheck'=>'N'
											)); ?>
					</div>
					
					<div class="col-lg-2">
						<?= $form->field($model, 'bpost_iscreditcard')->checkbox(array(
											'labelOptions'=>array('style'=>'padding:0px;'),
											'value'=>'Y',
											'uncheck'=>'N'
											)); ?>
					</div>
					<div class="col-lg-2">
						<?= $form->field($model, 'bpost_isdebitcard')->checkbox(array(
											'labelOptions'=>array('style'=>'padding:0px;'),
											'value'=>'Y',
											'uncheck'=>'N'
											)); ?>
					</div>
					<div class="col-lg-2">
						<?= $form->field($model, 'bpost_isewallet')->checkbox(array(
											'labelOptions'=>array('style'=>'padding:0px;'),
											'value'=>'Y',
											'uncheck'=>'N'
											)); ?>
					</div>
					<div class="col-lg-2">
						<?= $form->field($model, 'bpost_ispaytm')->checkbox(array(
											'labelOptions'=>array('style'=>'padding:0px;'),
											'value'=>'Y',
											'uncheck'=>'N'
											)); ?>
					</div>
			</div>
		</fieldset>
		<fieldset>
			<legend>Features:</legend>

			<div class="col-lg-12">
					<div class="col-lg-2">
						<?= $form->field($model, 'bpost_homedelivery')->checkbox(array(
											'labelOptions'=>array('style'=>'padding:0px;'),
											'value'=>'Y',
											'uncheck'=>'N'
											
											)); ?>
					</div>
					<div class="col-lg-4">
						<?= $form->field($model, 'bpost_homedeliverycharge',[
											'template' => "{label}<div class='col-md-9'>{input}</div>{hint}{error}",
											'labelOptions' => [ 'class' => 'col-md-3 ' ]
							])->dropDownList([ 'Free' => 'Free', 'Paid' => 'Paid', ]) ?>
					</div>
					<div class="col-lg-4">
						
						<?= $form->field($model, 'bpost_homedeliverydistance',[
											'template' => "{label}<div class='col-md-9'>{input}</div>{hint}{error}",
											'labelOptions' => [ 'class' => 'col-md-3 ' ]
							])->textInput(['maxlength' => true,'placeholder'=>"Distance in km"]) ?>
					</div>
			</div>
			<div class="col-lg-12">
					<div class="col-lg-2">
						<?= $form->field($model, 'bpost_open24hour')->checkbox(array(
											'labelOptions'=>array('style'=>'padding:0px;'),
											'value'=>'Y',
											'uncheck'=>'N'
											)); ?>
					</div>
			</div>
		</fieldset>
		<fieldset>
			<legend>Timings:</legend>
				<div class="col-lg-12" style="padding-bottom:15px;border-bottom:2px solid #000">
					
					<div class="col-lg-2"><?php if(!$model->bpost_alldayopen){
						$model->bpost_alldayopen = 'Y';
					};?>
						<?= $form->field($model, 'bpost_alldayopen')->checkbox(array(
											'labelOptions'=>array('style'=>'padding-top:10px;'),
											'value'=>'Y',
											'uncheck'=>'N',
											'onchange'=>'checkall(this.checked);'
											)); ?>
					</div>
					<div class="col-lg-5">
						<div class="col-lg-12">
						<label class="col-lg-12 text-center"> First Half</label>
						</div>
						<div class="col-lg-12 ">
							<div class="col-lg-6">
							
								<?= $form->field($model, 'bpost_openfrom1')->textInput(['maxlength' => true,'class'=>'timepickers form-control abcd'])->label(false) ?>
							</div>
							<div class="col-lg-6">
								
								<?= $form->field($model, 'bpost_openfrom2')->textInput(['maxlength' => true,'class'=>'timepickers form-control abcd'])->label(false) ?>
							</div>
						</div>
					</div>
					<div class="col-lg-5">
						<div class="col-lg-12">
						<label class="col-lg-12 text-center"> Second Half</label>
						</div>
						<div class="col-lg-12">
							<div class="col-lg-6">
								
								<?= $form->field($model, 'bpost_opento1')->textInput(['maxlength' => true,'class'=>'timepickers form-control abcd'])->label(false) ?>
							</div>
							<div class="col-lg-6">
								
								<?= $form->field($model, 'bpost_opento2')->textInput(['maxlength' => true,'class'=>'timepickers form-control abcd'])->label(false) ?>
							</div>
						</div>
					</div>
					
				</div>
				<div class="unfold" style="<?php echo ($model->bpost_alldayopen == 'N')?'display:block':'display:none';?>">
					<div class="col-lg-12">
						<div class="col-lg-2 ">
							<?= $form->field($model, 'bpost_ismonday')->checkbox(array(
												'labelOptions'=>array('style'=>'padding-top:10px;'),
												'value'=>'Y',
												'uncheck'=>'N',
												'class'=>'check',
												'data-day'=>'1'												
												)); ?>
						</div>
						<div class="col-lg-5" style="padding-top:10px">						
							<div class="col-lg-12 ">
								<div class="col-lg-6">
									<?= $form->field($model, 'bpost_monfrom1')->textInput(['maxlength' => true,'class'=>'aaaa1 timepickers form-control'])->label(false) ?>
									
								</div>
								<div class="col-lg-6">
									<?= $form->field($model, 'bpost_monfrom2')->textInput(['maxlength' => true,'class'=>'bbbb1 timepickers form-control'])->label(false) ?>
									
								</div>						
							</div>
						</div>
						<div class="col-lg-5" style="padding-top:10px">
							<div class=" form-group col-lg-12 ">
								<div class="col-lg-6">
									<?= $form->field($model, 'bpost_monto1')->textInput(['maxlength' => true,'class'=>'cccc1 timepickers form-control'])->label(false) ?>
									
								</div>
								<div class="col-lg-6">
									<?= $form->field($model, 'bpost_monto2')->textInput(['maxlength' => true,'class'=>'dddd1 timepickers form-control'])->label(false) ?>
									
								</div>
							</div>
						</div>
						
					</div>
					<div class="col-lg-12 ">
						<div class="col-lg-2">
							<?= $form->field($model, 'bpost_istuesday')->checkbox(array(
												'labelOptions'=>array('style'=>'padding-top:10px;'),
												'value'=>'Y',
												'uncheck'=>'N',
												'class'=>'check',
												'data-day'=>'2'													
												)); ?>
						</div>
						<div class="col-lg-5" style="padding-top:10px">						
							<div class="col-lg-12 ">
								<div class="col-lg-6">
									<?= $form->field($model, 'bpost_tuefrom1')->textInput(['maxlength' => true,'class'=>'aaaa2 timepickers form-control'])->label(false) ?>
									
								</div>
								<div class="col-lg-6">
									<?= $form->field($model, 'bpost_tuefrom2')->textInput(['maxlength' => true,'class'=>'bbbb2 timepickers form-control'])->label(false) ?>
									
								</div>						
							</div>
						</div>
						<div class="col-lg-5" style="padding-top:10px">						
							<div class="col-lg-12">
								<div class="col-lg-6">
									<?= $form->field($model, 'bpost_tueto1')->textInput(['maxlength' => true,'class'=>'cccc2 timepickers form-control'])->label(false) ?>
									
								</div>
								<div class="col-lg-6">
									<?= $form->field($model, 'bpost_tueto2')->textInput(['maxlength' => true,'class'=>'dddd2 timepickers form-control'])->label(false) ?>
									
								</div>						
							</div>
						</div>
					</div>
					<div class="col-lg-12">
						<div class="col-lg-2">
							<?= $form->field($model, 'bpost_iswednesday')->checkbox(array(
												'labelOptions'=>array('style'=>'padding-top:10px;'),
												'value'=>'Y',
												'uncheck'=>'N',
												'class'=>'check',
												'data-day'=>'3'													
												)); ?>
						</div>
						<div class="col-lg-5" style="padding-top:10px">						
							<div class="col-lg-12 ">
								<div class="col-lg-6">
									<?= $form->field($model, 'bpost_wedfrom1')->textInput(['maxlength' => true,'class'=>'aaaa3 timepickers form-control'])->label(false) ?>
									
								</div>
								<div class="col-lg-6">
									<?= $form->field($model, 'bpost_wedfrom2')->textInput(['maxlength' => true,'class'=>'bbbb3 timepickers form-control'])->label(false) ?>
									
								</div>						
							</div>
						</div>
						<div class="col-lg-5" style="padding-top:10px">						
							<div class="col-lg-12">
								<div class="col-lg-6">
									<?= $form->field($model, 'bpost_wedto1')->textInput(['maxlength' => true,'class'=>'cccc3 timepickers form-control'])->label(false) ?>
									
								</div>
								<div class="col-lg-6">
									<?= $form->field($model, 'bpost_wedto2')->textInput(['maxlength' => true,'class'=>'dddd3 timepickers form-control'])->label(false) ?>
									
								</div>						
							</div>
						</div>
						
					</div>
					<div class="col-lg-12">
						<div class="col-lg-2">
							<?= $form->field($model, 'bpost_isthursday')->checkbox(array(
												'labelOptions'=>array('style'=>'padding-top:10px;'),
												'value'=>'Y',
												'uncheck'=>'N',
												'class'=>'check',
												'data-day'=>'4'												
												)); ?>
						</div>
						<div class="col-lg-5" style="padding-top:10px">						
							<div class="col-lg-12 ">
								<div class="col-lg-6">
									<?= $form->field($model, 'bpost_thufrom1')->textInput(['maxlength' => true,'class'=>'aaaa4 timepickers form-control'])->label(false) ?>
									
								</div>
								<div class="col-lg-6">
									<?= $form->field($model, 'bpost_thufrom2')->textInput(['maxlength' => true,'class'=>'bbbb4 timepickers form-control'])->label(false) ?>
									
								</div>						
							</div>
						</div>
						<div class="col-lg-5" style="padding-top:10px">						
							<div class="col-lg-12">
								<div class="col-lg-6">
									<?= $form->field($model, 'bpost_thuto1')->textInput(['maxlength' => true,'class'=>'cccc4 timepickers form-control'])->label(false) ?>
									
								</div>
								<div class="col-lg-6">
									<?= $form->field($model, 'bpost_thuto2')->textInput(['maxlength' => true,'class'=>'dddd4 timepickers form-control'])->label(false) ?>
									
								</div>						
							</div>
						</div>
						
					</div>
					<div class="col-lg-12">
						<div class="col-lg-2">
							<?= $form->field($model, 'bpost_isfriday')->checkbox(array(
												'labelOptions'=>array('style'=>'padding-top:10px;'),
												'value'=>'Y',
												'uncheck'=>'N',
												'class'=>'check',
												'data-day'=>'5'												
												)); ?>
						</div>
						<div class="col-lg-5" style="padding-top:10px">						
							<div class="col-lg-12 ">
								<div class="col-lg-6">
									<?= $form->field($model, 'bpost_frifrom1')->textInput(['maxlength' => true,'class'=>'aaaa5 timepickers form-control'])->label(false) ?>
									
								</div>
								<div class="col-lg-6">
									<?= $form->field($model, 'bpost_frifrom2')->textInput(['maxlength' => true,'class'=>'bbbb5 timepickers form-control'])->label(false) ?>
									
								</div>						
							</div>
						</div>
						<div class="col-lg-5" style="padding-top:10px">						
							<div class="col-lg-12">
								<div class="col-lg-6">
									<?= $form->field($model, 'bpost_frito1')->textInput(['maxlength' => true,'class'=>'cccc5 timepickers form-control'])->label(false) ?>
									
								</div>
								<div class="col-lg-6">
									<?= $form->field($model, 'bpost_frito2')->textInput(['maxlength' => true,'class'=>'dddd5 timepickers form-control'])->label(false) ?>
									
								</div>						
							</div>
						</div>
						
					</div>
					<div class="col-lg-12">
						<div class="col-lg-2">
							<?= $form->field($model, 'bpost_issaturday')->checkbox(array(
												'labelOptions'=>array('style'=>'padding-top:10px;'),
												'value'=>'Y',
												'uncheck'=>'N',
												'class'=>'check',
												'data-day'=>'6',
												)); ?>
						</div>
						<div class="col-lg-5" style="padding-top:10px">						
							<div class="col-lg-12 ">
								<div class="col-lg-6 ">
									<?= $form->field($model, 'bpost_satfrom1')->textInput(['maxlength' => true,'class'=>'aaaa6 timepickers form-control'])->label(false) ?>
									
								</div>
								<div class="col-lg-6 ">
									<?= $form->field($model, 'bpost_satfrom2')->textInput(['maxlength' => true,'class'=>'bbbb6 timepickers form-control'])->label(false) ?>
									
								</div>						
							</div>
						</div>
						<div class="col-lg-5" style="padding-top:10px">						
							<div class="col-lg-12 ">
								<div class="col-lg-6 ">
									<?= $form->field($model, 'bpost_satto1')->textInput(['maxlength' => true,'class'=>'cccc6 timepickers form-control'])->label(false) ?>
									
								</div>
								<div class="col-lg-6">
									<?= $form->field($model, 'bpost_satto2')->textInput(['maxlength' => true,'class'=>'dddd6 timepickers form-control'])->label(false) ?>
									
								</div>						
							</div>
						</div>
						
					</div>
					<div class="col-lg-12">
						<div class="col-lg-2">
							<?= $form->field($model, 'bpost_issunday')->checkbox(array(
												'labelOptions'=>array('style'=>'padding-top:10px;'),
												'value'=>'Y',
												'uncheck'=>'N',
												'class'=>'check',
												'data-day'=>'7',
												)); ?>
						</div>
						<div class="col-lg-5" style="padding-top:10px">						
							<div class="col-lg-12 ">
								<div class="col-lg-6">
									<?= $form->field($model, 'bpost_sunfrom1')->textInput(['maxlength' => true,'class'=>'aaaa7 timepickers form-control'])->label(false) ?>
									
								</div>
								<div class="col-lg-6">
									<?= $form->field($model, 'bpost_sunfrom2')->textInput(['maxlength' => true,'class'=>'bbbb7 timepickers form-control'])->label(false) ?>
									
								</div>						
							</div>
						</div>
						<div class="col-lg-5" style="padding-top:10px">						
							<div class="col-lg-12 ">
								<div class="col-lg-6">
									<?= $form->field($model, 'bpost_sunto1')->textInput(['maxlength' => true,'class'=>'cccc7 timepickers form-control'])->label(false) ?>
									
								</div>
								<div class="col-lg-6 ">
									<?= $form->field($model, 'bpost_sunto2')->textInput(['maxlength' => true,'class'=>'dddd7 timepickers form-control'])->label(false) ?>
									
								</div>						
							</div>
						</div>
						
					</div>
				</div>
		</fieldset>	
		<!--<fieldset>
			<legend>Receipt Details:</legend>
				<div class="col-lg-2">
					<label>Receipt No.</label>
				</div>
				<div class="col-lg-4">
					<div class="form-group">
						<?= Html::textInput( 'receipt_number','',['class' => 'form-control','placeholder'=>'Receipt Number']) ?>
					</div>
				</div>
				<div class="col-lg-2">
					<label>Amount</label>
				</div>
				<div class="col-lg-4">
					<div class="form-group">
						<?= Html::textInput( 'receipt_amount','',['class' => 'form-control','placeholder'=>'Amount in Rs.']) ?>
					</div>
				</div>
			
		</fieldset>-->
		

	  

   

  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
</div>
<?php $url = Url::toRoute('/usermanager/checkuniqueuser');?>

<?php $script = <<< JS

	$('.new,.existing').hide();
	$('.check').on('change',function(){
		if(!$(this).is(':checked')){
			count = $(this).data("day");
			$('.aaaa'+count).val('');
			$('.bbbb'+count).val('');
			$('.cccc'+count).val('');
			$('.dddd'+count).val('');
		}		
	});
	$('.aaaa1').on('keyup change blur',function(){
		var value = $(this).val();
		$('[class*=aaaa]').val(value);		
	});
	$('.bbbb1').on('keyup change blur',function(){
		var value = $(this).val();
		$('[class*=bbbb]').val(value);		
	});
	$('.cccc1').on('keyup change blur',function(){
		var value = $(this).val();
		$('[class*=cccc]').val(value);		
	});
	$('.dddd1').on('keyup change blur',function(){
		var value = $(this).val();
		$('[class*=dddd]').val(value);		
	});	
$("#imgupload").change(function(){
    readURL(this);
});

$('.timepickers').timepicker({ 'timeFormat': 'H:i' ,});
$('form#bpost-form').on('beforeSubmit',function(e){
	var name = $('#customerfirstname').val();
	var contact = $('#customernumber').val();
	var exist = $('#bposts-bpost_user').val();
	//alert(name);
		//alert(contact);
		//alert(exist);
		 
	if((name == '' && contact == '')&&(exist == '')){
		
		alert('Please enter customer details.');
		$('#new').css('border-color','red');
		$('#existing').css('border-color','red');
		$('#new').focus();
		return false;
	}else{
		return confirm('Do you really want to submit the form?');
	}	
});

$('#customernumber').on('change',function(){
	var number = $(this).val();
	$.get("$url"+'?contact='+$(this).val(), 
			function(data){
				if(data.msg != "success"){
					$('#msg').html('<div class="alert alert-danger"><strong> '+number+' </strong>'+data.msg+' </div>');
					$('#customernumber').val('');
				}
				
			});
})
JS;
$this->registerJs($script);
?>
<script>

function checkall(click){
	//alert(click);	
	if(click){
		$('.check').prop('checked','');
		$('.unfold').slideUp("slow");
		
		//alert(click);
	}else{
		$('.check').prop('checked','checked');
		$('.unfold').show();
		$('.abcd').val('');
		 var windowheight = $(window).scrollTop();
		 height = $('.unfold')[0].scrollHeight;
		 //alert(height);
		$("html, body").animate({ scrollTop: windowheight+height }, 1500);
		$("#ajaxCrudModal").animate({ scrollTop: $('#ajaxCrudModal')[0].scrollHeight }, 1500);
		
	}
};
function showdiv(id){
		$('#new').css('border-color','');
		$('#existing').css('border-color','');
	if(id=="new"){
		alert('Please enter new customer details');
		$('.new').slideDown();
		$('.existing').hide();
		$('#customerfirstname').focus();
		$('#select2-bposts-bpost_user-container').html('<span class="select2-selection__placeholder">Select user by phone number ...</span>');
		$('#bposts-bpost_user').val('');
		
	}else{
		alert('Please select existing customer by searching their contact number');
		$('.existing').slideDown();
		$('.new').hide();
	}
}
function readURL(input) {

    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#blah').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}

</script>