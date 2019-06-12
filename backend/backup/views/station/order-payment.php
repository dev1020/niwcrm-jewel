<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\bootstrap\Modal;


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

.margin-right{
	margin-right: 25px !important;
}
@media only screen and (max-width: 600px) {
  .duetotal {
	  font-size:3.5vw;
  }
}
</style>
<div class="order-to-pay" style="background:#ffffff;padding:5px">
	
	
	<div class="row">
	<?php if($orders->due_amount>0){?>
		<div class="col-lg-12 col-xs-12">
			<?php $form = ActiveForm::begin(['options'=>['id'=>'payform'],'encodeErrorSummary' => false]); ?>
			
				<?= $form->errorSummary($paymentReceiptForm); ?>
			<h3> Order- <strong><?= str_pad($orders->id, 10, '0', STR_PAD_LEFT)?></strong> <label class="pull-right btn btn-danger duetotal" style="font-size:1hw">DueNow- &#8377; &nbsp;<span id="due" ><?= $orders->due_amount?></span></label><label class="pull-right btn btn-danger  style="font-size:1hw">TotalDue- &#8377; &nbsp;<span id="due" ><?= (int)$orders->due_amount?></span></label></h3>
			<hr>
			<?= $form->field($paymentReceiptForm, 'orderid')->hiddenInput(['maxlength' => true,'value'=>$orders->id])->label(false) ?>
			<div class="col-lg-12 col-xs-12 no-gutter bg-info" style="border-radius: 5px;border: 2px solid #11b2c5;">
				
				 <span class="pull-left"><input type="checkbox" name="rewardpoint" <?= ($bonus_available>0)?'checked="checked"':'' ?> id="rewardpoint" value="1" ></span><strong class="pull-left"> &nbsp; &nbsp; Reward Point </strong> 
							<span class="pull-right label label-success" for="email">Available Points: <?= $bonus_available?></span>
					
				
				<div class="col-lg-12 col-xs-12 slidepoints" style="display:none">
					<hr>
					<!--<div class="col-lg-6 col-xs-6">
						<div class="form-group">
							<label for="email">Available Points:</label>
							<input type="text" disabled="disabled" class="form-control" style="width:50%" id="avpoint" value="<?= $bonus_available?>">
						</div>
					</div>-->
					<div class="col-lg-12 col-xs-12">
						<div class="form-group">
							<label class="control-label col-lg-1 col-xs-3" for="email">Redeem</label>
							<!--<input type="text" class=" form-control" style="width:50%" name="redeempoint" id="redeempoint" value="<?= $max_redeem_points ?>" max="<?= $max_redeem_points ?>">-->
							<div class="col-lg-11 col-xs-9">
							<?= $form->field($paymentReceiptForm, 'pay[points]')->textInput(['id'=>'redeempoint','class'=>'numeric form-control','type' => 'tel','maxlength' => true,])->label(false) ?>
							</div>
						  </div>
					</div>
				</div>
				
			</div>
			
			<div class="col-lg-12 col-xs-12 no-gutter " style="padding: 2px;border: 2px solid #777;margin-top: 16px;border-radius: 5px;background: #f0f0f0;">
				<h3 class="text-center" style="border-bottom:1px solid #777"><strong> Payment Methods </strong></h3>
				
					
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
			<h3> Payments Done</h3>
			
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
					<td colspan="2" class="text-right"> Order-00000005 Total- </td>
					<td><i class="fa fa-inr"></i>&nbsp; <?= $totalpaymenttillnow ?></td>
					</tr>
				</tfoot>
			</table>
		</div>
	
	</div>
    
</div>
<?php $scripts = <<< JS
$(function(){
	var avlpoint = $bonus_available;
	var orderdue = $orders->due_amount;
	var maxredeempoint = 0;	
	
	redeempoint();
	duenow();
	if($('#rewardpoint').is(":checked")){
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
			$('.box-body').slideUp();
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
	
	$('#rewardpoint').on('click',function(){
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
								paynow: {
                                    text: 'Pay Now',
                                    btnClass: 'btn-green margin-right',
									action: function(){
												$('form#payform').submit();
											}
                                },
                                okay: {
                                    text: 'Okay',
                                    btnClass: 'btn-blue'
                                }
                            }
						});
						$('.paysubmit').focus();
					 }else{
						 $.alert({
							title: 'Excess Entered',
							icon: 'fa fa-inr',
							type: 'blue',
							content: '<hr><h4>Required <span class="pull-right"><i class="fa fa-inr"></i> '+currentamount+'</span></h4>',
							buttons: {
								paynow: {
                                    text: 'Pay Now',
                                    btnClass: 'btn-green margin-right',
									action: function(){
												$('form#payform').submit();
											}
                                },
                                okay: {
                                    text: 'Okay',
                                    btnClass: 'btn-blue'
                                }
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

$this->registerJs($scripts);
?>