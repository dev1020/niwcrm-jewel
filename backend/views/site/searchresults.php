<?php
use yii\helpers\Html; 
use yii\helpers\Url; 

/* @var $this yii\web\View */
/* @var $model backend\models\CompanyCustomers */
?>
<div class="searchitems">
 <div class="row">
    <?php if(count($customers)>0){?>
			<div class="col-lg-12 col-xs-12">
				<h3> Customers</h3>
				<hr>
			</div>
		<?php foreach($customers as $customer){ ?>
			<div class="col-lg-12 col-xs-12" style="padding:5px">
				<div class="col-lg-4 col-xs-4 text-center">
					<?= ucfirst($customer->cust->name)?>
				</div>
				<div class="col-lg-4 col-xs-4 text-center">
					<?= $customer->customer_number?>
				</div>
				<div class="col-lg-4 col-xs-4 text-center">
					<?= Html::a('<span class="glyphicon glyphicon-eye-open"></span>', Url::toRoute(['/company-customers/customer-stats', 'id'=>$customer->cust_id]),['title'=>'View Stats','role'=>'modal-remote','class'=>'btn btn-primary'])?>
					<?= Html::a('<span class="fa fa-copy"></span>', Url::toRoute(['/orders/search-to-order', 'custid'=>$customer->cust_id]),['title'=>'To Order','role'=>'modal-remote','class'=>'btn btn-success'])?>
				</div>
			
			</div>
		
		<?php } ?>
	
	<?php }if(count($orders)>0){ ?>
			<div class="col-lg-12 col-xs-12">
				<h3> Orders</h3>
				<hr>
			</div>
			<?php foreach($orders as $order){ ?>
			<div class="col-lg-12 col-xs-12" style="padding:5px">
				<div class="col-lg-4 col-xs-4 text-center">
					<?= $order->order_date ?>
				</div>
				<div class="col-lg-4 col-xs-4 text-center">
					<strong><?= $order->session_nos?></strong>
				</div>
				<div class="col-lg-4 col-xs-4 text-center">
					<?= Html::a('<span class="fa fa-inr"></span>', Url::toRoute(['/orders/view', 'id'=>$order->id]),['role'=>'modal-remote','title'=>'View','data-toggle'=>'tooltip' ,'class'=>"btn btn-success"])?>
					
				</div>
			
			</div>
		
		<?php } ?>
	<?php } ?>
	
	

	</div>
</div>
