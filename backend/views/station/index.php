<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;

use common\components\CustomerDues;

$getCustomerDues = new CustomerDues();
/* @var $this yii\web\View */
/* @var $searchModel backend\models\CategoriesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::$app->name.' '.' Station';
$this->params['breadcrumbs'][] = $this->title;

?>
<style>
.custa{
	color:#08074d;
}
.custa:hover{
	color:#3c8dbc;
}

.customer{
	cursor:pointer;
	border:2px solid #3E83DD;
	background:#fff;
	text-transform:capitalize;
	border-radius:5px;
	margin-top:5px;
	
}
.bill{
	min-height: 60px;
font-size: 1em;
line-height: 46px;
}
@media (max-width: 767px) {
  .bill{
	min-height: 60px;
font-size: 1em;
line-height: 46px;
}
}
sup{
	font-size:100%;
	letter-spacing: 1px;
}


.ribbon {
  position: absolute;
  left: -5px; top: -5px;
  z-index: 1;
  overflow: hidden;
  width: 75px; height: 75px;
  text-align: right;
}
.ribbon span {
    font-size: 10px;
    font-weight: bold;
    color: #FFF;
    text-transform: uppercase;
    text-align: center;
    line-height: 20px;
    transform: rotate(-45deg);
    -webkit-transform: rotate(-45deg);
    width: 80px;
    display: block;
    box-shadow: 0 3px 10px -5px rgba(0, 0, 0, 1);
    position: absolute;
    top: 10px;
	left: -20px;
}
.ribbon span::before {
  content: "";
  position: absolute; left: 0px; top: 100%;
  z-index: -1;
  border-left: 3px solid #1e5799;
  border-right: 3px solid transparent;
  border-bottom: 3px solid transparent;
  border-top: 3px solid #1e5799;
}
.ribbon span::after {
  content: "";
  position: absolute; right: 0px; top: 100%;
  z-index: -1;
  border-left: 3px solid transparent;
  border-right: 3px solid #1e5799;
  border-bottom: 3px solid transparent;
  border-top: 3px solid #1e5799;
}
.ribbon .primary{
	background: #79A70A;
    background: linear-gradient(#2989d8 0%, #1e5799 100%);
}
.ribbon .success{
	background: #79A70A;
	background: linear-gradient(#9BC90D 0%, #79A70A 100%);
}
.due {
    position: absolute;
    bottom: 0;
    left: 0;
}
.amount{
	position: absolute;
    bottom: 0;
    right: 0;
}

</style>
<div class="station-index">
    <div class="row">
		<div class="col-lg-9">
			
			<div class="col-lg-12 no-gutter">
				<div class="col-lg-3" style="margin-bottom:10px">
					<?= Html::a('<i class="glyphicon glyphicon-plus text-success"></i> New Sale', ['add-customer'],['role'=>'modal-remote','title'=> 'Add Customers','class'=>'btn btn-lg btn-default' ,'style'=>'border-radius:25px;border:2px solid #F07851']) ?>
				</div>
				<div class="col-lg-9  customersplace">
			<?php foreach($customers_now as $customer){?>
					
						<div class="customer col-lg-12 col-xs-12 custdet<?= $customer->session_no ?>" style="padding:3px" >
					<?php if(isset($customer->cust->id)){?>
							<a class="custa" href="<?= Url::to(['station/customer-services','id'=>$customer->cust->id,'cust_session'=>$customer->session_no])?>" data-id="<?= $customer->cust->id ?>">
								<div class="col-lg-2 col-xs-2 bg-info" style="min-height:60px;">
									<span class="label bg-navy"><?= $customer->session_no ?></span>
									<span class="label label-danger due">
										<i class="fa fa-inr"></i>
										<?php $dues = $getCustomerDues->getDues($customer->cust->id);
											echo ' '.$dues['due_amount'];?>
									</span>
								</div>
								<div class="col-lg-7 col-xs-7 text-center bg-info" style=" min-height:60px;">
									<h4 class="" ><?= ucwords(isset($customer->cust->name)? $customer->cust->name : '') ?> <br><?= isset($customer->cust->contact)? $customer->cust->contact : '' ?></h4>
									
								</div>
								
							</a>
							<div class="col-lg-3 col-xs-3 text-center" >
								<a  href="<?= Url::to(['station/generate-bill','id'=>$customer->cust->id,'session_no'=>$customer->session_no])?>" class="btn bg-purple btn-block bill"><i class="fa fa-file-text"></i> BILL</a>
							</div>
							<div class="col-lg-12 col-xs-12 text-center" style="margin-top: 2px;background: #e0e0e0;">
								<span class="label pull-left bg-<?= ($customer->type=='table')? 'green':'blue' ?>" style="margin-top: 4px;"><?= $customer->type ?></span>
								<?php $amount = $getCustomerDues->getBillValue($customer->cust->id,$customer->session_no);
									if($amount['status']=='billed'){
										echo '<span class="label pull-right bg-purple amount" ><i class="fa fa-inr"></i> '.$amount['total_amount'].'</span>';
									}else{
										echo '<span class="label pull-right bg-green amount" ><i class="fa fa-inr"></i> '.$amount['total_amount'].'</span>';
									}
								?>
								<span><strong> <?= ucwords(isset($customer->executive->username)? $customer->executive->username : '') ?>
								<?= isset($customer->executive->contact_number)? $customer->executive->contact_number : '' ?>
								</strong></span>
							</div>
							
					<?php }else{ ?>
							
							<a class="custa" href="<?= Url::to(['station/customer-services','id'=>'','cust_session'=>$customer->session_no,'seat_id'=>$customer->seat_id])?>">
								<div class="col-lg-2 col-xs-2 bg-info" style=" min-height:60px;">
									
									<span class="label pull-left bg-<?= ($customer->type=='table')? 'green':'blue' ?>" style="margin-top: 4px;"><?= $customer->type ?></span>
								</div>
								<div class="col-lg-7 col-xs-7 text-center bg-info" style=" min-height:60px;">
									<h4 class="" ><?= $customer->seat->seatlabel ?> <br></h4>
									
								</div>
								
							</a>
							<div class="col-lg-3 col-xs-3 text-center" >
								<a  href="<?= Url::to(['station/generate-bill','session_no'=>$customer->session_no,'seat_id'=>$customer->seat_id])?>" class="btn bg-purple btn-block bill"><i class="fa fa-file-text"></i> BILL</a>
							</div>
							<div class="col-lg-12 col-xs-12 " style="margin-top: 2px;background: #e0e0e0;">
								<span class="label pull-left bg-maroon"><?= $customer->session_no ?></span>
								<?php $amount = $getCustomerDues->getBillValuebyseat($customer->seat_id,$customer->session_no);
									if($amount['status']=='billed'){
										echo '<span class="label pull-right bg-purple amount" ><i class="fa fa-inr"></i> '.$amount['total_amount'].'</span>';
									}else{
										echo '<span class="label pull-right bg-green amount" ><i class="fa fa-inr"></i> '.$amount['total_amount'].'</span>';
									}
								?>
								<span style="margin-left:5px"><strong> <?= ucwords(isset($customer->executive->username)? $customer->executive->username : '') ?>
								<?= isset($customer->executive->contact_number)? $customer->executive->contact_number : '' ?>
								</strong></span>
							</div>
					<?php } ?>
							
							
							
						</div>
			<?php }?>
					
				</div>
			</div>
		</div>
	</div>
</div>

<?php $script = <<< JS
	$(function(){
		
		/*$(document).on('click','.customer > a',function(){
			var custid = $(this).attr('data-id');
			alert(custid);
		});*/
		
		
		
	});
	
JS;
$this->registerJs($script);
?>
<?php Modal::begin([
    "id"=>"ajaxCrudModal",
	 "size"=>"modal-lg",
    "footer"=>"",// always need it for jquery plugin
])?>
<?php Modal::end(); ?>
