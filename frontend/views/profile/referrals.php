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

$this->title = 'Referrals';
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
<h3 class="box-header"> Your Referrals  </h3>
<hr>
	<?php foreach($referrals as $referral){?>
	
		<div class="box box-info">
			<div class="box-header with-border ">
				<div class="col-lg-6 col-xs-6 text-left head">
					<span><img class="img-circle" src="/admin/images/face1.jpg" alt="Saltlake.in" style="width:36px"></span>
					<span><?= ucwords($referral['name'])?></span>
				</div>
				
				<div class="col-lg-6 col-xs-6 text-right head">
					<span><?= $referral['contact']?></span>
					<span><?= $referral['bonus']?></span>
					
					
				</div>
			</div>
		</div>
	
	<?php } ?>

<?php Modal::begin([
    "id"=>"ajaxCrudModal",
    "footer"=>"",// always need it for jquery plugin
])?>
<?php Modal::end(); ?>