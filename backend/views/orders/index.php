<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset; 
use johnitvn\ajaxcrud\BulkButtonWidget;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\OrdersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'All Orders';
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);

?>
<div class="row">
	<div class="col-lg-12 col-xs-12">
		<div class="box box-info ">
				<div class="box-header with-border">
				  <h3 class="box-title">Search Orders</h3>

				  <div class="box-tools pull-right">
					<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
					</button>
					<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
				  </div>
				</div>
				<!-- /.box-header -->
				<div class="box-body">
					<?= $this->render('search', ['model' => $searchModel,'company_id'=>$company_id]) ?>
				  <!-- /.table-responsive -->
				</div>
				
		</div>
	</div>
</div>
<div class="orders-index">
    <div id="ajaxCrudDatatable">
        <?=GridView::widget([
            'id'=>'crud-datatable',
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'pjax'=>true,
            'columns' => require(__DIR__.'/_columns.php'),
            'toolbar'=> [
                ['content'=>
                    Html::a('<i class="glyphicon glyphicon-plus"></i> ADD', ['create'],
                    ['role'=>'modal-remote','title'=> 'Create new Orders','class'=>'btn btn-success']).
					Html::a('<i class="fa fa-upload"></i> Import', ['import-excel'],
                    ['data-pjax'=>1, 'role'=>'modal-remote' ,'class'=>'btn bg-maroon', 'title'=>'Reset Grid']).
					Html::a('<i class="fa fa-download"></i> Sample', ['download-sample-excel'],
                    ['data-pjax'=>0,'class'=>'btn bg-info','target'=>'_blank', 'title'=>'Get sample to Import']).
                    Html::a('<i class="glyphicon glyphicon-repeat"></i>', [''],
                    ['data-pjax'=>1, 'class'=>'btn btn-default', 'title'=>'Reset Grid']).
                    '{toggleData}'.
                    '{export}'
                ],
            ],          
            'striped' => true,
            'condensed' => true,
            'responsive' => true,          
            'responsiveWrap' => false,          
            'panel' => [
                'type' => 'primary', 
                'heading' => '<i class="glyphicon glyphicon-list"></i> Orders listing',
                'before'=>'<em>* Resize table columns just like a spreadsheet by dragging the column edges.</em>',
                'after'=>BulkButtonWidget::widget([
                            'buttons'=>Html::a('<i class="glyphicon glyphicon-trash"></i>&nbsp; Delete All',
                                ["bulk-delete"] ,
                                [
                                    "class"=>"btn btn-danger",
                                    'role'=>'modal-remote-bulk',
                                    'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                                    'data-request-method'=>'post',
                                    'data-confirm-title'=>'Are you sure?',
                                    'data-confirm-message'=>'Are you sure want to delete this item'
                                ]),
                        ]).Html::button('<i class="fa fa-paper-plane"></i>&nbsp; Send/Resend SMS',
                                [
                                    "class"=>"btn btn-success",
									'type'=>'button',
									'id'=>'sendsms'
                                ]).                                            
                        '<div class="clearfix"></div>',
            ],
			'rowOptions' => function ($model, $index, $widget, $grid){
			  if($model->order_approved == 'no'){
				 return ['class'=>'bg-yellow'];
			 }
			 
			 },
        ])?>
    </div>
</div>
<?php 
$send_sms_url = Url::to(['bulk-sms']);
$url = Url::to(['/orders']);
$script = <<< JS
$(function(){
	var userid = [];
	var ids = '';
	
	$(document).on('click','#sendsms',function(){
			userid = [];
			$.each($("tbody input[type='checkbox']:checked"), function(){   
                userid.push($(this).val());
			});
		ids = userid.join(",");
		var modal = $('.modal');
		modal.modal();
		$('.modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button><h4 class="modal-title">Send SMS</h4>');
		if(userid.length>0){
			$('.modal-body').html('Are You Sure You want to send sms ?');
			$('.modal-footer').html('<button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="fa fa-times"></i> Close</button><button type="button" class="btn btn-primary" id="confirmsend"><i class="fa fa-paper-plane"></i> OK</button>');
		}else{
			$('.modal-body').html('<h4 class="text-danger">Please select a customer from the customer list.</h4>');
			$('.modal-footer').html('<button type="button" class="btn btn-danger pull-right" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>');
		}
		
	});
	$(document).on('click','#confirmsend',function(){
		console.log(ids);
		$.post( '$send_sms_url', { ids:ids})
		  .done(function( data ) {
			  if(data.status){
				$('.modal').modal();
				$('.modal-body').html(data.msg); 
				$('.modal-footer').html('<button type="button" class="btn btn-danger pull-right" data-dismiss="modal"><i class="fa fa-times"></i> Done</button>');		
				$.pjax({url: '$url', container:'#crud-datatable-pjax'})
			  }
		  })
		  .fail(function() {
			alert( "error" );
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
