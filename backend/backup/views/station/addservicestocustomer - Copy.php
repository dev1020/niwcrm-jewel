<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use backend\models\Categories;
use yii\web\JsExpression;


use kartik\depdrop\DepDrop;

/* @var $this yii\web\View */
/* @var $model backend\models\Customers */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Add Services To Customer';
$this->params['breadcrumbs'][] = ['label' => 'Station', 'url' => ['station/index']];
$this->params['breadcrumbs'][] = ['label' => 'Customer Services', 'url' => ['station/customer-services','id'=>$customer->id,'cust_session'=>$cust_session]];
$this->params['breadcrumbs'][] = $this->title;

?>
<style>
.select2-dropdown {
   
    bottom: 50;
}
.modal-header{
	display:none;
}
.bg-primary {
    color: #fff;
    background-color: #337ab7 !important;
}
.table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
   
    vertical-align: middle;
	cursor:pointer;
    
}
.catbutton{
	width: 80px;
height: 80px;
background-size:100% 100%;
background-repeat: no-repeat;
border:1px solid #51C3F0;
border-radius:10px;
margin:5px;
}

.subcategory .catimage{
	text-align:center;
}
.subcategory .catimage img{
	max-width:100%;
}
.subcategory .cattext{
	height:60px;
	font-weight:500;
	 text-transform: capitalize;
	 font-size:20px;
	 line-height:60px;
}
.subcategory1{
	background: #F07851;
padding: 5px;
border-radius: 5px;
}
.subcategory {
    background: #B8CBD7;
    padding: 5px;
    border-radius: 5px;
    border: 2px solid #e4631e;
}
@media (max-width: 767px) {
  .select2-container--krajee .select2-results > .select2-results__options {
    max-height: 350px;
    overflow-y: auto;
}
}
.catbutton.active {
    border: 2px solid #00A65A;
}


</style>
<?php
// Templating example of formatting each list element
$url = Yii::getAlias('@frontendimage').'/categorypic/';
$formatJs = <<< JS

var formatRepo = function (repo) {
    if (repo.loading) {
        return repo.text;
    }
	var image = repo.text.toLowerCase().replace(" ","-");
    var markup =
'<div class="col-lg-12 col-xs-12 subcategory no-gutter">' + 
    '<div class="col-lg-12 col-xs-12 catimage">' +
        '<img src="$url'+image+ '.png" class="img-rounded" alt="'+repo.text+'" style="" />' +
	'</div>'+
	
'</div>';
    
    return '<div style="overflow:hidden;">' + markup + '</div>';
};
var formatRepoSelection = function (repo) {
    return repo.full_name || repo.text;
}
JS;
$this->registerJs($formatJs,\yii\web\View::POS_HEAD);
?>
<div class="customers-form" style="background:#ffffff;padding:5px">
<?php $categories = Categories::find()->where(['category_root'=>0])->orderBy(['category_displayorder'=>SORT_ASC])->all();?>


    <div class="row">
	<?php $form = ActiveForm::begin(['options'=>['id'=>'assign-form']]); ?>
		<div class="col-lg-6  no-gutter">
			
			<div class="col-lg-12">
					<?= $form->field($model, 'customer_id')->hiddenInput(['value'=> $customer->id])->label(false);?>
					<?= $form->field($model, 'cust_session')->hiddenInput(['value'=> $cust_session])->label(false);?>
					
					<?= $form->field($model, 'category')->hiddenInput(['value'=> '','id'=>'catid','class'=>'form-control'])->label(false);?>
					<div class="categories text-center" style="margin-bottom:20px">
						<h4>Categories</h4>
					<?php foreach($categories as $category){?>
						
						<span class="btn btn-sm catbutton " style="background-image:Url(<?= Yii::getAlias('@frontendimage').'/categorypic/'. $category->category_pic?>)" data-catid="<?= $category->category_id ?>" title="<?= ucwords($category->category_name) ?>" data-toggle="tooltip" data-catname="<?= ucwords($category->category_name) ?>" ></span>
						
					<?php }?>
					</div>
					<div class="col-lg-12">
					<?= $form->field($model, 'subCategory')->widget(DepDrop::classname(), [
						//'data'=> [6=>'Bank'],
						'options' => ['id'=>'subcatid','placeholder' => 'Select ...','onchange' => '$.post( "'. Url::toRoute('/station/services') .'", { catid:$("#catid").val() , subcatid: $(this).val(),cust_session:$("#servicestocustomerselectionform-cust_session").val(),custid:$("#servicestocustomerselectionform-customer_id").val()})
						.done(function(res){$("#servicesdata").html(res.output);});'],
						'type' => DepDrop::TYPE_SELECT2,
						'select2Options'=>[
							'pluginOptions'=>[
								'allowClear'=>true,
								'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
								'templateResult' => new JsExpression('formatRepo'),
								'templateSelection' => new JsExpression('formatRepoSelection'),
							],
							'hideSearch'=>true,
							
						],
						'pluginOptions'=>[
							'depends'=>['catid'],
							'placeholder'=>'Select...',
							'url'=>Url::to(['/station/subcat']),
							'dataType' => 'json',
						]
					]);?>
					</div>
					
					
							
			</div>
			
			
		</div>
		<div class="col-lg-6  no-gutter">
			<div class="col-lg-12">
				<div id="servicesdata">
				</div>
			</div>
		</div>
		<?php ActiveForm::end(); ?>
		
	</div>
	
</div>
<?php 
$addserviceurl = Url::to(['station/add-services']);
$subcaturl = Url::to(['station/subcat']);
$script = <<< JS
$(function(){
	$('.catbutton').on('click',function(){
		var id = $(this).attr("data-catid");
		$(this).addClass('active');
		$(".catbutton").not($(this)).removeClass('active');
		
		$('#catid').val(id);
		$("#catid").trigger("change");
	});
	
	$(document).on('click','tbody tr',function(){
		var ele = $(this);
		var servicesid = ele.attr("data-id");
		
		if(ele.attr("class")=='bg-success'){
			if(ele.attr("data-service")=="done"){
				return false;
			}else{
				ele.removeClass("bg-success");
				$("#services"+servicesid).val('');
			}
		}else{
			ele.addClass("bg-success");
			$("#services"+servicesid).val(servicesid);
			/*var form = $('form#assign-form');
			  $.post(
				"$addserviceurl", 
				form.serialize()
			)
			.done(function(result){
				for(var i = 0; i < result.length; i++) {
					var obj = result[i];
					$("#servicetr"+obj.id).attr("data-service","done");
					$("#imgtd"+obj.id).append('<i class="fa fa-check pull-left text-success" style="margin-top: 10px;"></i>');
					$("#services"+obj.id).remove();
				}
			});*/
		}
		
	})

$('form#assign-form').on('beforeSubmit',function(e){
	var form = $(this);
	  $.post(
		"$addserviceurl", 
		form.serialize()
	)
	.done(function(result){
		for(var i = 0; i < result.length; i++) {
			var obj = result[i];
			$("#servicetr"+obj.id).attr("data-service","done");
			$("#imgtd"+obj.id).append('<i class="fa fa-check pull-left text-success" style="margin-top: 10px;"></i>');
			$("#services"+obj.id).remove();
		}
		//alert(result.status);
		if(result.status==false){
			$('.error').html(result.msg);
		}
	});
	return false;
});
});

JS;
$this->registerJs($script);
?>
