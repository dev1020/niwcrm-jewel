<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use kartik\select2\Select2;
use backend\models\PromosmsTemplates;
use johnitvn\ajaxcrud\CrudAsset; 
use johnitvn\ajaxcrud\BulkButtonWidget;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\CustomersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Promotional Campaign';
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);
?>
<style>
@media (max-width: 768px) {
     .customertable  tbody > tr:nth-of-type(2n+1) {
		background-color: #e1e1e1 !important;
	}
	.customertable  tbody tr td{
		height: 60px !important;
		vertical-align: middle !important;
		border:none !important;
	}
	
	.customertable  tbody tr td a{
		display:block;
	}
	.custlink a{
		height:60px;
		width:100%;
		line-height: 60px;
	}
}
.custlink a{
		width:100%;
		display:block;
	}
.table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
    vertical-align: middle;
}
.kv-panel-pager {
    text-align: center;
}

</style>

	<div class="customers-index">
		<div class="row">
			<div class="col-lg-6 col-xs-12">
				<div id="ajaxCrudDatatable">
					<?=GridView::widget([
						'id'=>'crud-datatable',
						'dataProvider' => $dataProvider,
						'filterModel' => $searchModel,
						'pjax'=>true,
						'columns' => require(__DIR__.'/_columns.php'),
						'toolbar'=> '{toggleData}',
						'rowOptions'   => function ($model, $key, $index, $grid) {
								return ['data-id' => $model->id];
							},
						'tableOptions'=> [
							'class'=>'customertable'
						],
						'striped' => true,
						'condensed' => true,
						'responsive' => true,  
						'responsiveWrap'=>false,
						'panel' => [
							'type' => 'primary', 
							'heading' => '<i class="fa fa-paper-plane"></i> &nbsp;Send Promotional SMS to Your Customers',
							'before'=>'',
							'after'=>false,
						]
					])?>
				</div>
			</div>
			<div class="col-lg-6 col-xs-12" style="background:#fff;padding:15px">
				<div class="form-group ">
					<label class="control-label col-lg-2">New Sms</label><input type="radio" name="selectsms" class="control-label col-lg-1" value="new">
					<label class="control-label col-lg-3" >Sms from template</label><input type="radio" name="selectsms" value="previous" class="control-label col-lg-1">
					
				</div>
				<div style="clear:both"></div>
				<div class="col-lg-12 previous" style="margin-top:10px">
						<label class="control-label ">Select from the previous templates</label>
						<?= Select2::widget([
						'name' => 'smsprvious',
					'data' => ArrayHelper::map(PromosmsTemplates::find()->all(),'id','sms_title'),
					'language' => 'en',
					'options' => ['placeholder' => 'Select a Business ...','onchange' => '$.get("' . Url::toRoute('/promotional/gettemplates?') . 'id='.'"+$(this).val(), 
					function(data){
						$("#smssend").val(data.content);
					});'],
					'pluginOptions' => [
						'allowClear' => true,
						'tabindex' => 'off',
					],
				]) ?>
				</div>
				<div style="clear:both"></div>
				<div class="form-group " style="padding:15px">
					<label class="control-label " for="smssend">Type SMS </label>
					<textarea name="smssend" id="smssend" class="form-control"></textarea>

					<div class="help-block"></div>
				</div>
				
				<?= Html::button('<i class="fa fa-paper-plane"></i> Send SMS',['class'=>'btn btn-success','id'=>'sendsms'])
				?>
			</div>
		</div>
	</div>

<?php 
$send_sms_url = Url::to(['promotional/bulk-sms']);
$script = <<< JS
$(function(){
	var userid = [];
	var ids = '';
	$('.previous').hide();
	$("input[type='radio']").on('click',function(){
		var element = $(this);
		if(element.val()=='new'){
			$('.previous').slideUp();
		}else{
			$('.previous').slideDown();
		}
	});
	
	
	$('#sendsms').on('click',function(){
			userid = [];
			$.each($("input[type='checkbox']:checked"), function(){   
                userid.push($(this).val());
			});
		ids = userid.join(",");
		var modal = $('.modal');
		modal.modal();
		if(userid.length>0){
			$('.modal-body').html('Are You Sure You want to send sms ?');
			$('.modal-footer').html('<button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="fa fa-times"></i> Close</button><button type="button" class="btn btn-primary" id="confirmsend"><i class="fa fa-paper-plane"></i> OK</button>');
		}else{
			$('.modal-body').html('<h4 class="text-danger">Please select a customer from the customer list.</h4>');
			$('.modal-footer').html('<button type="button" class="btn btn-danger pull-right" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>');
		}
		
	});
	$(document).on('click','#confirmsend',function(){
		$.post( '$send_sms_url', { ids: ids, smstext: $('#smssend').val() })
		  .done(function( data ) {
			  if(data.status){
				$('.modal').modal();
				$('.modal-body').html(data.msg); 
			  }
		  })
		  .fail(function() {
			alert( "error" );
		  })
		  .always(function() {
			$('.modal').modal('hide');
		  });
	});
	
})
JS;
$this->registerJs($script);
?>
<?php Modal::begin([
    "id"=>"ajaxCrudModal",
    "footer"=>"",// always need it for jquery plugin
])?>
<?php Modal::end(); ?>
