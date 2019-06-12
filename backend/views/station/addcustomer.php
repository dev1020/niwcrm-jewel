<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;
use backend\models\Customers;
use yii\jui\AutoComplete;


/* @var $this yii\web\View */
/* @var $model backend\models\Customers */
/* @var $form yii\widgets\ActiveForm */
?>
<style>
  .ui-autocomplete {
    max-height: 100px;
    overflow-y: auto;
    /* prevent horizontal scrollbar */
    overflow-x: hidden;
  }
  /* IE 6 doesn't support max-height
   * we use height instead, but this forces the menu to always be this tall
   */
  * html .ui-autocomplete {
    height: 100px;
  }
  #customers-other{
	  font-size:20px;
  }
  .addresses{
		padding: 10px;
		border: 2px solid #deb02c;
  }
</style>

<div class="customers-form">
<?php $deliveryurl = Url::to(['station/get-delivery-address']);?>
    <?php $form = ActiveForm::begin(['id' => 'cust-form',]); ?>
		<div class="alert alert-error error" style="display:none">
  
		</div>
	<div class="form-group">
		<label>Type</label>
		<?= Html::activeDropDownList($model, 'sale_type',['table'=>'table','delivery'=>'delivery'],['prompt'=>'Please Select','class'=>'form-control','id'=>'ordertype']) ?>
	</div>
	<?= $form->field($model, 'table_id')->hiddenInput(['value'=>''])->label(false); ?>
	<div class="fetchseats no-gutter">
	
	</div>
	<h4 class="seatsSelected text-center"> </h4>
	
	<?= $form->field($model, 'other')->widget(\yii\jui\AutoComplete::classname(), [
    
    'clientOptions' => [
		'appendTo'=>'#cust-form',
        'source' => $data, 
		'max'=>10,
        'autoFill'=>true,
         'select' => new JsExpression("function( event, ui ) {
        $('#opensaleform-customer_name').val(ui.item.name);
		if($('#ordertype').val()=='delivery'){
			$.get('$deliveryurl?cust_id='+ui.item.id, function(data, status){
			$('.fetchaddress').html(data.output);
			$('.fetchaddress').attr('data-id',ui.item.id);
		  });
		}
		
     }")
	 ],
	 'options' => ['class' =>'form-control'],
     ])->label('Search') ?>
	 <!--//$form->field($model, 'contact')->textInput(['maxlength' => true,'onkeyup'=>'findcustomer(this.value)']) -->
	
    <?= $form->field($model, 'customer_name')->textInput(['maxlength' => true]) ?>

	<div class="fetchaddress">
	
	</div>
	
	<div class="form-group">
		<?= Html::submitButton('Take Order', ['class' => 'btn btn-primary btn-block', 'name' => 'login-button','id'=>'loginsubmit']) ?>
	</div>
	

    <?php ActiveForm::end(); ?>
    
</div>

<?php 
$servicesurl = Url::to(['station/customer-services']);
$billsurl = Url::to(['station/generate-bill']);
$seatssurl = Url::to(['station/get-freeseats']);
$splitseatssurl = Url::to(['station/splitseats']);

$script = <<< JS
$(function(){
	$(document).on('click','.tableseat',function(){
		var ele = $(this);
		var id = ele.attr("data-id");
		$('.seatsSelected').html('<div class="col-lg-12 col-xs-12">Selected Table is '+id+'</div>');
		$('#opensaleform-table_id').val(id);
	})
	
	/*$(document).on('click','.split',function(){
		var ele = $(this);
		var tablename = $(this).val();
		$.alert({
			title: 'Split Table ? ',
			icon: 'fa fa-plus',
			type: 'green',
			content: '<hr><h3>Would You Like To Split ? </h3>',
			buttons: {
				paynow: {
					text: 'Yes',
					btnClass: 'btn-green margin-right',
					action: function(){
								ele.attr("disabled","disabled");
							}
				},
				okay: {
					text: 'Cancel',
					btnClass: 'btn-blue'
				}
			}
		});
	})*/
	
	$('#ordertype').on('change',function(){
		if($('#ordertype').val()=='delivery'){
			$('.fetchseats').html('');
			var id = $('.fetchaddress').attr("data-id");
			if(typeof(id) != "undefined" && id !== null){
				
				$.get('$deliveryurl?cust_id='+id, function(data, status){
				$('.fetchaddress').html(data.output);
				});
			}else{
				$.get('$deliveryurl', function(data, status){
				$('.fetchaddress').html(data.output);
				});
			}	  
		}
		if($('#ordertype').val()=='table'){
			$('.fetchaddress').html('');
			$.get('$seatssurl', function(data, status){
			$('.fetchseats').html(data);
		  })
		}
	});
	
	$('#opensaleform-other').on('input',function(){
		if($(this).val().length>9 && $('#ordertype').val()=='delivery'){
			$.get('$deliveryurl?contact='+$(this).val(), function(data, status){
			$('.fetchaddress').html(data.output);
			$('.fetchaddress').attr('data-id',data.id);
			$('#opensaleform-customer_name').val(data.name);
		  });
		}
	});
});
$('form#cust-form').on('beforeSubmit',function(e){
	$('.error').hide();
	var form = $(this);
	$.post(
		form.attr("action"),
		form.serialize()
	)
	.done(function(result){
		if(result.entrystatus){
			var labeltype='';
			if(result.type=='table'){
				labeltype = 'bg-green';
			}else{
				labeltype = 'bg-primary';
			}
			$('#ajaxCrudModal').modal('hide');
			var customer = '<div class="customer col-lg-12 col-xs-12 custdet'+result.id+'" style="padding:3px">';
			
			if (result.id){
			customer = customer + '<a href="$servicesurl?id='+result.id+'&cust_session='+result.session_no+'&seat_id=" data-id="'+result.id+'"><div class="col-lg-2 col-xs-2 bg-info" style=" min-height:60px;"><span class="label bg-navy">'+result.session_no+'</span></div>';
			customer = customer + '<div class="col-lg-7 col-xs-7 text-center bg-info" style="min-height:60px"><h4 class="">'+result.name+'<br>'+result.contact+'</h4></div></a>'; 
			customer = customer + '<div class="col-lg-3 col-xs-3 text-center" ><a href="$billsurl?id='+result.id+'&session_no='+result.session_no+'&seat_id=" class="btn btn-block bg-purple bill" ><i class="fa fa-file-text"></i> BILL</a></div>';
			customer = customer + '<div class="col-lg-12 col-xs-12 text-center" style="margin-top: 2px;background: #e0e0e0;"><span class="label pull-left '+labeltype+'">'+result.type+'</span><span class="label pull-right bg-green amount" ><i class="fa fa-inr"></i> 0 </span></div>';
			}else{
				customer = customer + '<a href="$servicesurl?id=&cust_session='+result.session_no+'&seat_id='+result.seatid+'" data-id="'+result.id+'"><div class="col-lg-2 col-xs-2 bg-info" style=" min-height:60px;"><span class="label bg-navy">'+result.session_no+'</span></div>';
			customer = customer + '<div class="col-lg-7 col-xs-7 text-center bg-info" style="min-height:60px"><h4 class=""> '+result.seatlabel+'</h4></div></a>'; 
			customer = customer + '<div class="col-lg-3 col-xs-3 text-center" ><a href="$billsurl?id=&session_no='+result.session_no+'&seat_id='+result.seatid+'" class="btn btn-block bg-purple bill" ><i class="fa fa-file-text"></i> BILL</a></div>';
			customer = customer + '<div class="col-lg-12 col-xs-12 text-center" style="margin-top: 2px;background: #e0e0e0;"><span class="label pull-left '+labeltype+'">'+result.type+'</span><span class="label pull-right bg-green amount" ><i class="fa fa-inr"></i> 0 </span></div>';
			}
			customer = customer + '</div>'
			
			newcustomer = $(customer).hide();
			$('.customersplace').append(newcustomer);
			$('.footer').focus();
			newcustomer.show("bounce", { times: 3 }, "slow");
		}
		if(result.error){
			$('.error').show();
			$('.error').html(result.error);
		}
	});
	
	return false;
});
JS;
$this->registerJs($script);
?>
