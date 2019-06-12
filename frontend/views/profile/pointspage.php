<?php

use yii\widgets\DetailView;
use yii\helpers\Url;
use yii\helpers\Html;
use miloschuman\highcharts\Highcharts;

use yii\bootstrap\Modal;
use johnitvn\ajaxcrud\CrudAsset;
use yii\widgets\Pjax;



CrudAsset::register($this);
/* @var $this yii\web\View */
/* @var $model backend\models\Customers */

$this->title = 'Points Details';
$this->params['breadcrumbs'][] = ['label' => 'Profile', 'url' => ['/profile']];
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
.head span{
	display:block;
}
.dropdown-toggle span{
	display:inline-block;
}
.dropdown-toggle{
	padding: 0px 6px;
	font-size:10px;
}
</style>
<h3 class="box-header"> Your Points Details <span class="btn btn-success pull-right"><?= $availablebonus ?></span> </h3>
<hr>
<?php Pjax::begin(['options'=>['id'=>'orders','data-pjax-container'=>'orders']]);?>


	<div class="orders" style="background:#ffffff;padding:5px">
		
			<?php foreach($bonuses as $bonus){?>
			
				<div class="box <?= ($bonus->type=='redeem')? 'box-danger':'box-success'?>">
					<div class="box-header with-border ">
						<div class="col-lg-4 text-left head">
							<span>Date</span>
							<span><?= $bonus->created_date ?></span>
						</div>
						<div class="col-lg-4 text-center head">
							<span><label class="label <?= ($bonus->type=='redeem')? 'label-danger' :'' ?><?= ($bonus->type=='referral')? 'label-primary' :'' ?><?= ($bonus->type=='loyalty')? 'label-success' :'' ?>"><?= $bonus->type ?></label></span>
							<span>
								
								<?= (int)$bonus->bonus_amount?>
							</span>
						</div>
						<div class="col-lg-4 text-right head">
							<span>Order # <?= str_pad($bonus->order->id, 10, '0', STR_PAD_LEFT)?></span>
							<?php {
								if($bonus->type=='referral'){
									echo "<span>From: <strong>".ucwords($bonus->order->cust->name)."</strong></span>";
								}
							}?>
							
						</div>
					</div>
				</div>
			
			<?php } ?>
		
	</div>		
	<?php 
	Pjax::end();
	?>
	


<?php Modal::begin([
    "id"=>"ajaxCrudModal",
    "footer"=>"",// always need it for jquery plugin
])?>
<?php Modal::end(); ?>