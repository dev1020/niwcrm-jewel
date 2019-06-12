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
		<?= Html::dropDownList('type', null,['table'=>'table','delivery'=>'delivery'],['class'=>'form-control','id'=>'ordertype']) ?>
	</div>
	<?= $form->field($model, 'other')->widget(\yii\jui\AutoComplete::classname(), [
    
    'clientOptions' => [
		'appendTo'=>'#cust-form',
        'source' => $data, 
		'max'=>10,
        'autoFill'=>true,
         'select' => new JsExpression("function( event, ui ) {
        $('#customers-name').val(ui.item.name);
        $('#customers-gender').val(ui.item.gender);
		if($('#ordertype').val()=='delivery'){
			$.get('$deliveryurl?cust_id='+ui.item.id, function(data, status){
			$('.fetchaddress').html(data);
		  });
		}
		
     }")
	 ],
	 'options' => ['class' =>'form-control'],
     ])->label('Search') ?>
	 <!--//$form->field($model, 'contact')->textInput(['maxlength' => true,'onkeyup'=>'findcustomer(this.value)']) -->
	
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

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

$script = <<< JS
$(function(){
	$('#ordertype').on('change',function(){
		if($('#ordertype').val()=='delivery'){
			$.get('$deliveryurl', function(data, status){
			$('.fetchaddress').html(data);
		  });
		}
		if($('#ordertype').val()=='table'){
			$('.fetchaddress').html('');
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
			customer = customer + '<a href="$servicesurl?id='+result.id+'&cust_session='+result.session_no+'" data-id="'+result.id+'"><div class="col-lg-2 col-xs-2 bg-info" style=" min-height:60px;"><span class="label bg-navy">'+result.session_no+'</span></div>';
			customer = customer + '<div class="col-lg-7 col-xs-7 text-center bg-info" style="min-height:52px"><h4 class="">'+result.name+'<br>'+result.contact+'</h4></div></a>'; 
			customer = customer + '<div class="col-lg-3 col-xs-3 text-center" ><a href="$billsurl?id='+result.id+'&session_no='+result.session_no+'" class="btn btn-block bg-purple bill" ><i class="fa fa-file-text"></i> BILL</a></div>';
			customer = customer + '<div class="col-lg-12 col-xs-12 text-center" style="margin-top: 2px;background: #e0e0e0;"><span class="label pull-left '+labeltype+'">'+result.type+'</span><span class="label pull-right bg-green amount" ><i class="fa fa-inr"></i> 0 </span></div></div>';
			
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
