<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $model backend\models\Customers */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Payment Page';
$this->params['breadcrumbs'][] = ['label' => 'Station', 'url' => ['station/index'],'class'=>'btn btn-info'];
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
.modal-header{
	display:none;
}
.box-header{
	cursor:pointer;
}
.duetotal{
	position:fixed;
	right:10px;
	z-index:100;
	font-weight:500
}
.modal-body {
    max-height: calc(100vh - 100px);
    overflow-y: auto;
}
.margin-right{
	margin-right: 25px !important;
}
@media only screen and (max-width: 600px) {
  .duetotal {
	  font-size:15px;
  }
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
<div class="order-to-pay" style="background:#ffffff;padding:5px">
	
	
	<div class="row">
	<?php if($orders->due_amount>0){?>
		<div class="col-lg-12 col-xs-12">
			<?php $form = ActiveForm::begin(['options'=>['id'=>'payform'],'encodeErrorSummary' => false]); ?>
			
				<?= $form->errorSummary($paymentReceiptForm); ?>
			<h3> Order- <strong><?= str_pad($orders->id, 10, '0', STR_PAD_LEFT)?></strong> <label class="pull-right btn btn-danger duetotal" style="font-size:15px;">DueNow- &#8377; &nbsp;<span id="due" ><?= $orders->due_amount?></span></label><label class="pull-right btn btn-danger"  style="font-size:15px;">TotalDue- &#8377; &nbsp;<span id="due" ><?= (int)$orders->due_amount?></span></label></h3>
			<hr>
			<?= $form->field($paymentReceiptForm, 'orderid')->hiddenInput(['maxlength' => true,'value'=>$orders->id])->label(false) ?>
			<?= $form->field($paymentReceiptForm, 'orderdue')->hiddenInput(['value'=>(int)$orders->due_amount])->label(false) ?>
			<?php if($orders->settings['bonus_redemption']=='yes'){?>
				
				<div class="col-lg-8 no-gutter">
					<h4 class="avlpoints text-success" >Available Points:<span class="pull-right badge bg-navy" id="bonus"><?= $bonus_available?$bonus_available:0 ?></span></h4>
					<div style="clear:both"></div>
					<div class="col-lg-8 col-xs-8 redeem-group" style="margin-bottom:15px">
						<input type="checkbox" <?= ($bonus_available>0)?'checked="checked"':'' ?> name="fancy-checkbox-primary" id="fancy-checkbox-primary" autocomplete="off" />
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
					<div class="col-lg-4 col-xs-4">
						<?= $form->field($paymentReceiptForm, 'pay[points]')->textInput(['type'=>'tel','id'=>'redeempoint','onclick'=>'this.select()','class'=>'numeric form-control','style'=>''])->label(false) ?>
					</div>
					
				
				</div>
			<?php }else{ 
				echo $form->field($paymentReceiptForm, 'pay[points]')->hiddenInput(['id'=>'redeempoint'])->label(false);
			 } ?>
			
			<div class="col-lg-12 col-xs-12 paygroup no-gutter " style="padding: 2px;border: 2px solid #777;margin-top: 16px;border-radius: 5px;background: #f0f0f0;">
				<h3 class="text-center" style="border-bottom:1px solid #777"><strong> Payment Methods </strong></h3>
				
				<?php if($mutiple_payments_type=='yes'){?>
				<div class="col-lg-12 col-xs-12 cash">
					<div class="box box-warning box-solid">
						<div class="box-header with-border" id="cash" >
						  <h3 class="box-title"><i class="fa fa-inr"></i> &nbsp;Cash</h3>

						  
						  <!-- /.box-tools -->
						</div>
						<!-- /.box-header -->
						<div class="box-body" id="cashbox">
							<div class="col-lg-12 col-xs-12">
								<div class="form-group">
									<label class="col-xs-4 col-lg-4 control-label text-center">Amount</label>

									<div class="col-xs-8 col-lg-8">
										<!--<input type="text" class="form-control" id="cashgiven" name="cashgiven" placeholder="cash">-->
										<?= $form->field($paymentReceiptForm, 'pay[cash]')->textInput(['type' => 'tel','class'=>'pay numeric form-control','maxlength' => true,'data-pay'=>'cash'])->label(false) ?>
									</div>
								</div>
							</div>
							
						</div>
						<!-- /.box-body -->
					  </div>
				</div>
				<div class="col-lg-12 col-xs-12 card">
					<div class="box box-success box-solid">
						<div class="box-header with-border" id="card" >
						  <h3 class="box-title"><i class="fa fa-credit-card"></i> &nbsp;Card</h3>

						  <!--<div class="box-tools pull-right">
							<button type="button" id="cardtab" class="btn btn-box-tool" data-toggle="collapse" data-target="#toggle-pane-87"><i class="fa fa-minus"></i></button>
						  </div>-->
						  <!-- /.box-tools -->
						</div>
						<!-- /.box-header -->
						<div class="box-body " id="cardbox">
							<!--<input type="text" class="form-control" name="card" id="card" placeholder="card" >-->
							<label class="col-xs-4 col-lg-4 control-label text-center">Amount</label>
							<div class="col-xs-8 col-lg-8">
								<?= $form->field($paymentReceiptForm, 'pay[card]')->textInput(['type' => 'tel','class'=>'pay numeric form-control','maxlength' => true,])->label(false) ?>
							</div>
						</div>
						<!-- /.box-body -->
					  </div>
				</div>
				<div class="col-lg-12 col-xs-12 paytm">
					<div class="box box-primary box-solid">
						<div class="box-header with-border" id="wallet" >
						  <h3 class="box-title"><i class="fa fa-google-wallet "></i> &nbsp;Wallet</h3>

						  
						  <!-- /.box-tools -->
						</div>
						<!-- /.box-header -->
						<div class="box-body " id="walletbox" style="">
							<!--<input type="text" class="form-control" name="wallet" id="wallet" placeholder="wallet" >-->
							<label class="col-xs-4 col-lg-4 control-label text-center">Amount</label>
							<div class="col-xs-8 col-lg-8">
								<?= $form->field($paymentReceiptForm, 'pay[wallet]')->textInput(['type' => 'tel','class'=>'pay numeric form-control','maxlength' => true,])->label(false) ?>
							</div>
						</div>
						<!-- /.box-body -->
					  </div>
				</div>
				<?php }else{?>
					<div class="col-lg-12 col-xs-12 cash">
					<div class="box box-warning box-solid">
						<div class="box-header with-border" id="cash" >
						  <h3 class="box-title"><input type="radio" name="paytype" value="cash">&nbsp;&nbsp;<i class="fa fa-inr"></i> &nbsp;Cash</h3>

						  
						  <!-- /.box-tools -->
						</div>
						<!-- /.box-header -->
						<div class="box-body collapse" id="cashbox">
							<div class="col-lg-12 col-xs-12">
								<div class="form-group">
									<label class="col-xs-4 col-lg-4 control-label text-center">Amount</label>

									<div class="col-xs-8 col-lg-8">
										<!--<input type="text" class="form-control" id="cashgiven" name="cashgiven" placeholder="cash">-->
										<?= $form->field($paymentReceiptForm, 'pay[cash]')->textInput(['type' => 'tel','class'=>'pay numeric form-control','maxlength' => true,'data-pay'=>'cash'])->label(false) ?>
									</div>
								</div>
							</div>
							
						</div>
						<!-- /.box-body -->
					  </div>
				</div>
				<div class="col-lg-12 col-xs-12 card">
					<div class="box box-success box-solid">
						<div class="box-header with-border" id="card" >
						  <h3 class="box-title"><input type="radio" name="paytype" value="card">&nbsp;&nbsp;<i class="fa fa-credit-card"></i> &nbsp;Card</h3>

						  <!--<div class="box-tools pull-right">
							<button type="button" id="cardtab" class="btn btn-box-tool" data-toggle="collapse" data-target="#toggle-pane-87"><i class="fa fa-minus"></i></button>
						  </div>-->
						  <!-- /.box-tools -->
						</div>
						<!-- /.box-header -->
						<div class="box-body collapse" id="cardbox">
							<!--<input type="text" class="form-control" name="card" id="card" placeholder="card" >-->
							<label class="col-xs-4 col-lg-4 control-label text-center">Amount</label>
							<div class="col-xs-8 col-lg-8">
								<?= $form->field($paymentReceiptForm, 'pay[card]')->textInput(['type' => 'tel','class'=>'pay numeric form-control','maxlength' => true,])->label(false) ?>
							</div>
						</div>
						<!-- /.box-body -->
					  </div>
				</div>
				<div class="col-lg-12 col-xs-12 paytm">
					<div class="box box-primary box-solid">
						<div class="box-header with-border" id="wallet" >
						  <h3 class="box-title"><input type="radio" name="paytype" value="wallet">&nbsp;&nbsp;<i class="fa fa-google-wallet "></i> &nbsp;Wallet</h3>

						  
						  <!-- /.box-tools -->
						</div>
						<!-- /.box-header -->
						<div class="box-body collapse" id="walletbox" style="">
							<!--<input type="text" class="form-control" name="wallet" id="wallet" placeholder="wallet" >-->
							<label class="col-xs-4 col-lg-4 control-label text-center">Amount</label>
							<div class="col-xs-8 col-lg-8">
								<?= $form->field($paymentReceiptForm, 'pay[wallet]')->textInput(['type' => 'tel','class'=>'pay numeric form-control','maxlength' => true,])->label(false) ?>
							</div>
						</div>
						<!-- /.box-body -->
					  </div>
				</div>
				<?php } ?>
				
			</div>
			<?php if (!Yii::$app->request->isAjax){ ?>
				<div class="form-group text-center">
					<?= Html::submitButton(' &nbsp;&nbsp;&nbsp;&nbsp;Pay&nbsp;&nbsp;&nbsp;&nbsp; ' , ['class' => 'btn btn-success btn-lg paysubmit','style'=>'margin-top:25px']) ?>
				</div>
			<?php } ?>		
			
			<?php ActiveForm::end(); ?>
			<div class="col-lg-12 col-xs-12">
			<hr style="border:2px solid #000">
			</div>
		</div>
	<?php }else{ ?>
		<div class="col-lg-12 col-xs-12 ">
			
			<div class="alert alert-success text-center"><h4>No Amount is due</h4></div>
		</div>
	<?php } ?>
		<div class="col-lg-12 col-xs-12">
			<h3> Payments Details</h3>
			
			<table class="table table-bordered ">
				<thead>
					<tr class="bg-primary">
					<th>Date</th>
					<th>Type</th>
					<th>Amount</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach($ordersPayments as $payments ){?>
					<tr>
						<td><?= $payments->payment_date?></td>
						<td><?= $payments->payment_type?></td>
						<td><i class="fa fa-inr"></i>&nbsp;<?= $payments->amount?></td>
					</tr>
				<?php } ?>
				</tbody>
				<tfoot>
					<tr>
					<td colspan="2" class="text-right"> Order-<?= str_pad($orders->id, 10, '0', STR_PAD_LEFT)?> Total- </td>
					<td><i class="fa fa-inr"></i>&nbsp; <?= $totalpaymenttillnow ?></td>
					</tr>
				</tfoot>
			</table>
		</div>
	
	</div>
    
</div>
<?php $formutiple_payments_type = <<< JS
$(function(){
	var avlpoint = $bonus_available;
	var orderdue = $orders->due_amount;
	var maxredeempoint = 0;	
	
	redeempoint();
	duenow();
	if($('#fancy-checkbox-primary').is(":checked")){
			$('.slidepoints').slideDown();
			$('#redeempoint').val(maxredeempoint);
		}else{
			$('.slidepoints').slideUp();
			$('#redeempoint').val('');
		}
	
	function redeempoint(){
		if(orderdue >= avlpoint){
		maxredeempoint = avlpoint;
		}else{
			maxredeempoint = orderdue;
		}
		$('#redeempoint').val(maxredeempoint);
	}
	$('#redeempoint').on('input',function(){
		var redeempoint = $(this).val();
		if(redeempoint > maxredeempoint){
			$.alert({
					title: 'Error',
					icon: 'fa fa-warning',
					type: 'red',
					content: 'You Don\'t have sufficient points.',
				});
			$(this).val(maxredeempoint);
		}
		duenow();
	});
	
	$("input[type='radio']").on('click',function(){
		var element = $(this);
		radioclick(element);
	});
	function radioclick(e){
		if(e.prop("checked", true)){
			var name = e.attr('value');
			$('.paygroup .box-body').slideUp();
			$('.pay').val('');
			$('#'+name+'box').slideDown();
			duenow();	
		}
	}
	$( ".box-header" ).on( "click", function(){
		value = $( this ).attr('id');
		var element = $("input[type='radio'][value=" + value + "]");
	  radioclick(element);
	});
	
	$('#fancy-checkbox-primary').on('click',function(){
		var element = $(this);
		if(element.is(":checked")){
			$('.slidepoints').slideDown();
			$('#redeempoint').val(maxredeempoint);
		}else{
			$('.slidepoints').slideUp();
			$('#redeempoint').val('');
		}
		duenow();	
		
	})
	
	$('.pay').on('input',function(){
		duenow();	
		
	});
	
	function duenow(){
		var pay = 0;
		var returnamount = 0;
		$('input[type=tel]').each(function(){
			if ($(this).val().length != 0){
				pay = parseInt(pay)+parseInt($(this).val());
			}
			 
		});
		if(orderdue>=pay){
			$('#due').html(orderdue-pay);
		}else{
			$.alert({
				title: 'Payment Excess',
				icon: 'fa fa-inr',
				type: 'red',
				content: '<hr><h3>Excess Payment of <span class="pull-right"><i class="fa fa-inr"></i> '+(pay-orderdue)+'</span></h3>',
				buttons: {
					
					okay: {
						text: 'Cancel Payment',
						btnClass: 'btn-red margin-right'
					},
					
				}
			});
			$('#due').html('0');
			
		}
	}
	
});
JS;

$forsingle_payments_type = <<< JS
$(function(){
	var avlpoint = $bonus_available;
	var orderdue = $orders->due_amount;
	var maxredeempoint = 0;	
	
	redeempoint();
	duenow();
	if($('#fancy-checkbox-primary').is(":checked")){
			$('.slidepoints').slideDown();
			$('#redeempoint').val(maxredeempoint);
		}else{
			$('.slidepoints').slideUp();
			$('#redeempoint').val('');
		}
	
	function redeempoint(){
		if(orderdue >= avlpoint){
		maxredeempoint = avlpoint;
		}else{
			maxredeempoint = orderdue;
		}
		$('#redeempoint').val(maxredeempoint);
	}
	$('#redeempoint').on('input',function(){
		var redeempoint = $(this).val();
		if(redeempoint > maxredeempoint){
			$.alert({
					title: 'Error',
					icon: 'fa fa-warning',
					type: 'red',
					content: 'You Don\'t have sufficient points.',
				});
			$(this).val(maxredeempoint);
		}
		duenow();
	});
	
	$("input[type='radio']").on('click',function(){
		var element = $(this);
		radioclick(element);
	});
	function radioclick(e){
		if(e.prop("checked", true)){
			var name = e.attr('value');
			$('.paygroup .box-body').slideUp();
			$('.pay').val('');
			$('#'+name+'box').slideDown();
			duenow();	
		}
	}
	$( ".box-header" ).on( "click", function(){
		value = $( this ).attr('id');
		var element = $("input[type='radio'][value=" + value + "]");
	  radioclick(element);
	});
	
	$('#fancy-checkbox-primary').on('click',function(){
		var element = $(this);
		if(element.is(":checked")){
			$('.slidepoints').slideDown();
			$('#redeempoint').val(maxredeempoint);
		}else{
			$('.slidepoints').slideUp();
			$('#redeempoint').val('');
		}
		duenow();	
		
	})
	
	$('.pay').on('input',function(){
		duenow();	
		
	});
	
	function duenow(){
		var pay = 0;
		var returnamount = 0;
		$('input[type=tel]').each(function(){
			if ($(this).val().length != 0){
				pay = parseInt(pay)+parseInt($(this).val());
			}
			 
		});
		if(orderdue>=pay){
			$('#due').html(orderdue-pay);
		}else{
			
			$('.pay').each(function(){
				var index = $( ".pay" ).index($(this));
				if ($(this).val().length != 0){
					 returnamount = pay-orderdue;
					 var currentamount = $(this).val();
					 currentamount = currentamount-returnamount;
					 $(this).val(currentamount);
					 
					 if($(this).attr('data-pay')=='cash'){
						 $.alert({
							title: 'Cash return',
							icon: 'fa fa-inr',
							type: 'green',
							content: '<hr><h3>Please return <span class="pull-right"><i class="fa fa-inr"></i> '+returnamount+'</span></h3>',
							buttons: {
								
                                okay: {
                                    text: 'Cancel Payment',
                                    btnClass: 'btn-red margin-right'
                                },
								paynow: {
                                    text: 'Ok',
                                    btnClass: 'btn-green ',
									action: function(){
												$('form#payform').submit();
											}
                                },
                            }
						});
						$('.paysubmit').focus();
					 }else{
						 $.alert({
							title: 'Excess Payment Alert',
							icon: 'fa fa-inr',
							type: 'red',
							content: '<hr><h4>Required <span class="pull-right"><i class="fa fa-inr"></i> '+currentamount+'</span></h4>',
							buttons: {
                                okay: {
                                    text: 'Cancel Payment',
                                    btnClass: 'btn-red margin-right'
                                },
								paynow: {
                                    text: 'OK',
                                    btnClass: 'btn-green ',
									action: function(){
												$('form#payform').submit();
											}
                                },
                            }
						});
					 }
					 
				}
				 
			});
			$('#due').html('0');
			
		}
	}
	
});
JS;
if($mutiple_payments_type=='yes'){
	$this->registerJs($formutiple_payments_type);
}else{
	$this->registerJs($forsingle_payments_type);
}

?>