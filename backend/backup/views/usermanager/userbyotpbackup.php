<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use backend\models\Categories;
use kartik\select2\Select2;

$this->title = 'Add user by OTP';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
	<div class="col-md-2 col-xs-push-12 col-xs-push-0 ">
		<div class="box box-info">
			<div class="box-header">
				<h3 class="box-title"><strong>Quick Links</strong></h3>
			</div>
			<div class="box-body sidebar">
				<ul class="sidebar-menu">
					<li>
						<?= Html::a('Employees', ['index']) ?>
					</li>
					<li>
						<?= Html::a('Customers', ['customers']) ?>
					</li>
					<li>
						<?= Html::a('Add Employee', ['create']) ?>
					</li>
					<li>
						<?= Html::a('Add Customer by OTP', ['createbyotp']) ?>
					</li>
				</ul>
			</div><!-- /.box-body -->
		</div>
	</div>
		
			<?php $form = ActiveForm::begin(['id' => 'form-signup','enableAjaxValidation' => false,]); ?>
				<div class="col-lg-10 col-xs-pull-12 col-xs-pull-0 ">
						
						<div class="col-lg-7 ">
							
							
							
							
							<?= $form->field($model, 'usertype')->hiddenInput(['value'=> 'user'])->label(false) ?>
							<div class="form-group has-error">
								<p id="responseText"> </p>
							</div>
							
								
								
									
									<fieldset>
										<legend>Business Details:</legend>
										<div class="form-group">
											<?= Html::textInput( 'bpost_title','',['class' => 'form-control','placeholder'=>'Name of business']) ?>
										</div>
										
										<div class="form-group">
													<?= Select2::widget([
															'name' => 'category',
															'language' => 'en',
															'data' => ArrayHelper::map(Categories::find()->where(['category_root' => '0'])->andWhere(['<>','category_id', '1'])->all(),'category_id','category_name'),
							
															'options' => ['multiple' =>false, 'placeholder' => 'Select categories ...'],
															'pluginOptions' => [
																'allowClear' => true,
																'tabindex' => 'off',
															],
														]); ?>
										</div>
										
										
			
											<div class="col-lg-12">
												<h4><strong> Add Address </strong></h4>
												<?= Html::button('<i class="fa fa-edit"></i> Add Address Manually',['class'=>'btn btn-success','id'=>'addressbutton'])?>
												<?= Html::button('<i class="fa fa-bullseye"></i> Add Via Current Location',['class'=>'btn btn-success','id'=>'currentlocation', 'onclick'=>'getlocation()'])?>
																	
												<hr style="border-color:#444">
											</div>
											<div class="col-lg-8">
													<div id="addressmanualdiv" >
														<div class="form-group">
																<?= Html::textInput( 'address1','',['class' => 'form-control','placeholder'=>'Address Line 1','id'=>'address1']) ?>
														</div>
														<div class="form-group">
																<?= Html::textInput( 'address2','',['class' => 'form-control','placeholder'=>'Address Line 2','id'=>'address2']) ?>
														</div>
														<div class="form-group">
																<?= Html::textInput( 'city','',['class' => 'form-control','placeholder'=>'City','id'=>'city']) ?>
														</div>
														<div class="form-group">
																<?= Html::textInput( 'pin','',['class' => 'form-control','placeholder'=>'Pin','id'=>'pin']) ?>
														</div>
														
													</div>
											</div>
											<?= Html::hiddenInput('place_id', '',['id' => 'place_id'])?>
											
										
									</fieldset>
									<fieldset>
										<legend>Contact Details:</legend>
										<?= $form->field($model, 'first_name')->textInput(['placeholder'=>'First Name',])->label(false) ?>
										<?= $form->field($model, 'last_name')->textInput(['placeholder'=>'Last Name',])->label(false) ?>
										<?= $form->field($model, 'password')->hiddenInput(['id'=>'password'])->label(false) ?>
									</fieldset>
									<?= $form->field($model, 'contact_number')->textInput(['placeholder'=>'Enter mobile number',])->label(false) ?>
									<div class="form-group">
									<?= Html::button('Continue >>', ['class' => 'btn btn-warning btn-block', 'id' => 'continue']) ?>
									</div> 
									<div class="otp">
										<div class="col-lg-8 ">
											<div class="form-group">
												<?= Html::textInput( 'otp','',['class' => 'form-control', 'id' => 'otp-val','placeholder'=>'Enter the OTP']) ?>
											</div>
										</div>
										<div class="col-lg-4">
											<div class="form-group">
											<?= Html::button('Resend OTP >>', ['class' => 'btn btn-success btn-block', 'id' => 'resend']) ?>
											</div>
										</div>
										<div class="form-group">
											<?= Html::submitButton('Add', ['class' => 'btn btn-primary btn-block', 'name' => 'signup-button']) ?>
										</div>
									
									</div>
							
						</div>
						
						<div class="col-lg-5 ">
							<?= Html::a('Create Bposts', ['bposts/create'], ['class' => 'btn btn-success text-center']) ?>
							<hr>
							<div class=" col-sm-8 col-lg-offset-3 alert alert-response" id="response" >  </div>
								
									<div class="alert alert-success col-sm-8 col-lg-offset-3 " id="success">
									</div>
						</div>
						
				</div>
				
				
			<?php ActiveForm::end(); ?>
</div>

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	<script>
		//alert(1);
		  $('.otp,#success').hide();
			$('#continue').on('click',function(e){
				//alert();
				var number = $('#signupform-contact_number').val();
				
				$.ajax({
					datatype:"json",
				   type: "GET",
				   url: "<?php echo Url::to(['/sms/checkbeforeotp'])?>",
				   data: {number: number},
				   success: function(data) {
					  if(data.response == "Success"){
						$.ajax({
							datatype:"json",
							type: "GET",
						    url: "<?php echo Url::to(['/sms/sendotp'])?>",
						    data: {number: number},
							beforeSend:function() {
								// setting a timeout
								$('#password').val(number);
								 $('#continue').slideUp();
								   $('.otp').slideDown();
							},
						    success: function(data){
								if(data[0].ErrorCode == '000'){
								   
							   }
						   }
						});
					  }else{
						  $(".alert-response").html(data.response).fadeTo(4000, 500).slideUp(1000, function(){
								$(".alert-response").slideUp(2000);
							});
						  
					  }
				   }
				});
				
			});
			
			$('#resend').on('click',function(e){
				var number = $('#signupform-contact_number').val();
				$.ajax({
					datatype:"json",
					type: "GET",
					url: "<?php echo Url::to(['/sms/sendotp'])?>",
					data: {number: number},
					success: function(data){
						if(data[0].ErrorCode == '000'){
						   
					   }
				   }
				});
			});
			$("#addressbutton").on('click',function(){
				$("#place_id,#address1,#address2,#city,#pin").attr('disabled',false);
				$("#place_id").val('');
			});
			
			function getlocation(){
				alert(1);
				if (navigator.geolocation) {
					navigator.geolocation.getCurrentPosition(showLocation);
				} else { 
					//$('#location').html('Geolocation is not supported by this browser.');
				}
			}
			function showLocation(position) {
				var latitude = position.coords.latitude;
				var longitude = position.coords.longitude;
				//alert(latitude);
				//alert(longitude);
				$.ajax({
					type:'POST',
					url:'<?= Url::to(['site/getuserlocation'])?>',
					data:'latitude='+latitude+'&longitude='+longitude,
					success:function(msg){
						if(msg.status=='true'){
							alert('Address Selected Please Check');
							$("#address1").val(msg.address1);
							$("#address2").val(msg.address2);
							$("#city").val(msg.city);
							$("#pin").val(msg.pin);
							$("#place_id").val(msg.place_id);
							$("#place_id,#address1,#address2,#city,#pin").attr('disabled','disabled');
							
						}else{
							alert('Please Add Address Manually');
						}
					}
				});
			}
			
			
	</script>
	

	<?php $script = <<< JS
	
$('form#form-signup').on('beforeSubmit',function(e){
	$('#responseText').html('');
	//$('.field-loginform-username,.field-loginform-password').removeClass('has-error');
	var form = $(this);
	//alert(form.attr("action"));
	$.post(
		form.attr("action"),
		form.serialize()
	)
	.done(function(result){
		if(result.response == 'Success'){
			$('#success').show().html(result.msg);
			form[0].reset();
			$('.otp').hide();
			$('#continue').slideDown();
		}
	});
	
	return false;
});
JS;
$this->registerJs($script);
?>
