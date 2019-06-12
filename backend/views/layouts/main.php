<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\widgets\Menu;

use yii\widgets\Breadcrumbs;
use common\widgets\Alert;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use common\models\User;
use backend\models\Companies;
use backend\models\CompanyBranches;
use kartik\select2\Select2;
use common\components\SettingsGetter;

AppAsset::register($this);

$session = Yii::$app->session;
$settings_getter = new SettingsGetter();
$company = $session['company.company_id'];	
$branch = $session['company.branch_id'];

//$session->destroy();


?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
   <meta name='viewport' content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' />
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="hold-transition skin-purple fixed sidebar-mini">
<?php $this->beginBody() ?>
<?php
if(Yii::$app->user->can('executive')){
$style= <<< CSS
@media only screen and (max-width: 767px) {
	  .content-wrapper {
		padding-top:100px !important;
	  }
	};
CSS;
 $this->registerCss($style);
}?>
	<header class="main-header">
    <!-- Logo -->
	<?php 
	
		
		
	$referrer = Yii::$app->request->referrer ? Yii::$app->request->referrer : Yii::$app->homeUrl;
	if( Url::to(Yii::$app->request->url)!= '/admin/site/index'){?>
		<button class="visible-xs btn btn-sm bg-purple" id="backprevious" style="float:left;width:15%;height:50px;border-radius:0;line-height: 50px;"> <i class="glyphicon glyphicon-arrow-left" style="font-size: 25px;"></i></button>
	<?php }else{?>
		<a class="visible-xs btn btn-sm bg-purple" href ="<?= $referrer ?>" style="float:left;width:15%;height:50px;border-radius:0;line-height: 50px;"> <i class="glyphicon glyphicon-arrow-right" style="font-size: 25px;"></i></button>
	<?php }	?>
	
	<a class="visible-xs btn btn-sm bg-green buttonright" href="<?= Url::toRoute(['/site/index'])?>"> <i class="fa fa-home" style="font-size: 25px;"></i></button>
    <a href="<?= Url::toRoute(['/site/index'])?>" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>CRM</b>SPA</span>
      <!-- logo for regular state and mobile devices -->
     <!-- <span class="logo-lg"><?= Html::img('@web/images/logo.png', ['alt'=>Yii::$app->name])?></span>-->
	 
      <span class="logo-lg"><?= Html::img((($settings_getter->get_attribute_value('site_logo',$session['company.company_id']) != NULL)? '@web/uploads/companies/'.$settings_getter->get_attribute_value('site_logo',$session['company.company_id']) : '@web/images/logo.png'), ['alt'=>Yii::$app->name])?></span>
    </a>
	
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" >
        
        <i class="fa fa-bars"></i>
      </a>
		
      <div class="navbar-custom-menu">
		
        <ul class="nav navbar-nav">
          <!-- Messages: style can be found in dropdown.less-->
		  
          <li class="dropdown "><a id="reduce" class=" bg-blue " style="cursor:pointer;font-size:15px">A</a></li>
		  
          <li class="dropdown "><a id="increase" class=" bg-green " style="cursor:pointer;font-size:25px">A</a></li>
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            
              <img src="<?= (Yii::$app->user->identity->profilepic)?(Url::to('@frontendimage'.'/profilepic/'.Yii::$app->user->identity->profilepic)):(Url::to('@frontendimage'.'/noimage.png'))?>" alt="NIWCRM" style="width:22px" class="img-circle"/>
              <span class="hidden-xs"><?= Yii::$app->user->identity->username ?></span>
            </a>
            <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
              <!-- User image -->
              <li class="user-header">
                
				<img src="<?= (Yii::$app->user->identity->profilepic)?(Url::to('@frontendimage'.'/profilepic/'.Yii::$app->user->identity->profilepic)):(Url::to('@frontendimage'.'/noimage.png'))?>" alt="NIWCRM"  class="img-circle"/>
                <p>
                  <?= Yii::$app->user->identity->username ?> - 
                  
                </p>
				<p><?= $settings_getter->get_attribute_value('brand_name',$session['company.company_id']) ? $settings_getter->get_attribute_value('brand_name',$session['company.company_id']) : Yii::$app->name ;?></p>
              </li>
              <!-- Menu Body -->
              <li class="user-body">
                <div class="row">
                  <div class="col-xs-4 text-center">
                    <a href="#">Followers</a>
                  </div>
                  <div class="col-xs-4 text-center">
                    <a href="#">Sales</a>
                  </div>
                  <div class="col-xs-4 text-center">
                    <a href="#">Friends</a>
                  </div>
                </div>
                <!-- /.row -->
              </li>
              <!-- Menu Footer-->
              <li class="user-footer ">
                <div class="pull-left">
					<?= Html::a('Profile', ['site/profile'], ['class' => 'btn btn-success']) ?>
					
				</div>
				<div class="pull-right">
				<?= Html::beginForm(['/site/logout'], 'post');?>
				<?= Html::submitButton(
					'<span class="glyphicon glyphicon-log-in"></span> Logout ',
					['class' => 'btn btn-danger logout']
					);?>
				<?= Html::endForm();?>
				</div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->
          
        </ul>
      </div>
	  <div class="col-lg-7 col-xs-12 no-gutter" style="padding: 4px;">
		<?php if(Yii::$app->user->can('Admin')){
			$setcompanyUrl = Url::to(['/site/set-company']);
			
			?>
			<div class="col-lg-6 col-xs-6" style="padding-top: 8px;"><?= Select2::widget([
				'name' => 'company_id',
				'value'=>$session['company.company_id'],
				'data' => ArrayHelper::map(Companies::find()->orderBy(['company_name'=>SORT_ASC])->asArray()->all(), 'id', 'company_name'),
				'options' => ['multiple' => false, 'placeholder' => 'Select Company'],
				'pluginOptions' => [
					'allowClear' => true
				],
				'pluginEvents' => [
					"change" => "function(){ 
						$.post( '$setcompanyUrl', { company_id: this.value, } ) 
					}",
				]
				]);?>
			</div>
		<?php } ?>
	  
		<?php if(Yii::$app->user->can('manager') || Yii::$app->user->can('Admin')) {
			$setbranchrl = Url::to(['/site/set-branch']);
			?>
			<div class="col-lg-6 col-xs-6" style="padding-top: 8px;"><?= Select2::widget([
				'name' => 'branch_id',
				'value'=>$session['company.branch_id'],
				'data' => ArrayHelper::map(CompanyBranches::find()->where(['company_id'=>$session['company.company_id']])->orderBy(['branch_name'=>SORT_ASC])->asArray()->all(), 'id', 'branch_name'),
				'options' => ['multiple' => false, 'placeholder' => 'Select Branch'],
				'pluginOptions' => [
					'allowClear' => true
				],
				'pluginEvents' => [
					"change" => "function(){ 
						$.post( '$setbranchrl', { branch_id: this.value, } ) 
					}",
				]
				]);?>
			</div>
		<?php } ?>
	  </div>
    </nav>
  </header>
    
	
	
	
	<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          
		  <img src="<?= (Yii::$app->user->identity->profilepic)?(Url::to('@frontendimage'.'/profilepic/'.Yii::$app->user->identity->profilepic)):(Url::to('@frontendimage'.'/noimage.png'))?>" alt="NIWCRM"  class="img-circle"/>
        </div>
        <div class="pull-left info">
          <p><?= Yii::$app->user->identity->username ?></p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
	 <?php  
		echo Menu::widget([
		'items' => [
			// Important: you need to specify url as 'controller/action',
			// not just as 'controller' even if default action is used.
			[	'label' => 'Home', 
				'url' => ['/site/index'],
				'template'=>'<a href="{url}"><i class="fa fa-home"></i>
				<span>{label}</span></a>',
			],
			
			[
                'label' => ' Companies && Branches',
                'items' => [
					[
						'label' => 'Companies',
						'url' => ['/companies']
					],
					[
						'label' => 'Branches',
						'url' => ['/company-branches']
					],
                ],
				'visible' => !Yii::$app->user->isGuest && Yii::$app->user->can('Admin'),
				'options'=>['class'=>'treeview'],
				'submenuTemplate'=>'<ul class="treeview-menu">{items}</ul>',
				'template'=>'<a href="#"><i class="fa fa-building"></i>
				<span>{label}</span>
				<span class="pull-right-container">
				  <i class="fa fa-angle-left pull-right"></i>
				</span></a>'
			],
			
			['label' => ' All Customers', 'url' => ['/customers/index'],'template'=>'<a href="{url}"><i class="fa fa-users"></i><span>{label}</span></a>','visible' => Yii::$app->user->can('Admin')],			
			
			['label' => ' Company Customers', 'url' => ['/company-customers/index'],'template'=>'<a href="{url}"><i class="fa fa-users"></i><span>{label}</span></a>','visible' => !Yii::$app->user->isGuest],		
			
			['label' => ' Users', 'url' => ['/company-users/index'],'template'=>'<a href="{url}"><i class="glyphicon glyphicon-user"></i><span>{label}</span></a>','visible' => (Yii::$app->user->can('Admin') || Yii::$app->user->can('manager'))],			
			
			[
                'label' => ' Sales Summary',
                'items' => [
					[
					'label' => 'NEW Sale',
					'url' => ['/orders/create'],
					'visible' => (Yii::$app->user->can('Admin') || Yii::$app->user->can('manager'))
					],
					[
					'label' => 'TODAY\'S Opened Sale',
					'url' => ['/orders/opened-today'],
					'visible' => (Yii::$app->user->can('Admin') || Yii::$app->user->can('manager'))
					],
					[
					'label' => 'Approval Pending Sale',
					'url' => ['/orders/approval-pending'],
					'visible' => (Yii::$app->user->can('Admin') || Yii::$app->user->can('manager'))
					],
					[
					'label' => 'All Sales',
					'url' => ['/orders/index'],
					'visible' => (Yii::$app->user->can('Admin') || Yii::$app->user->can('manager'))
					],
						
                ],
				'visible' => (Yii::$app->user->can('Admin') || Yii::$app->user->can('manager')),
				'options'=>['class'=>'treeview'],
				'submenuTemplate'=>'<ul class="treeview-menu">{items}</ul>',
				'template'=>'<a href="#"><i class="fa fa-copy"></i>
				<span>{label}</span>
				<span class="pull-right-container">
				  <i class="fa fa-angle-left pull-right"></i>
				</span></a>'
			],
			
			[
                'label' => ' Users Manager',
                'items' => [
					[
					'label' => 'Users',
					'url' => ['/usermanager/index'],
					'visible' => (Yii::$app->user->can('Admin') || Yii::$app->user->can('manager'))
					],
						
                ],
				//'visible' => !Yii::$app->user->isGuest && Yii::$app->user->can('Admin'),
				'options'=>['class'=>'treeview'],
				'submenuTemplate'=>'<ul class="treeview-menu">{items}</ul>',
				'template'=>'<a href="#"><i class="fa fa-user"></i>
				<span>{label}</span>
				<span class="pull-right-container">
				  <i class="fa fa-angle-left pull-right"></i>
				</span></a>'
			],
			
			[
                'label' => 'Promotions',
                'items' => [
					[
					'label' => 'Manage SMS Templates',
					'url' => ['/promosms-templates/index'],
					'visible' => (Yii::$app->user->can('Admin') || Yii::$app->user->can('manager'))
					],
					[
					'label' => 'Promote Campaign via SMS',
					'url' => ['/promotional/index'],
					'visible' => (Yii::$app->user->can('Admin') || Yii::$app->user->can('manager'))
					],
					
					[
					'label' => 'My Mail',
					'url' => ['/promotional/my-mail'],
					'visible' => (Yii::$app->user->can('Admin') || Yii::$app->user->can('manager'))
					],
					
                ],
				//'visible' => !Yii::$app->user->isGuest && Yii::$app->user->can('Admin'),
				'options'=>['class'=>'treeview'],
				'submenuTemplate'=>'<ul class="treeview-menu">{items}</ul>',
				'template'=>'<a href="#"><i class="fa fa-bullhorn "></i>
				<span>{label}</span>
				<span class="pull-right-container">
				  <i class="fa fa-angle-left pull-right"></i>
				</span></a>'
			],
			
			
			[
                'label' => ' Permissions',
                'items' => [
					[
						'label' => 'Permissions',
						'url' => ['/rbac/permission/index']
					],
					[
						'label' => 'Roles',
						'url' => ['/rbac/role']
					],
					[
						'label' => 'Rules',
						'url' => ['/rbac/rule']
					],
					[
						'label' => 'Role Assignments',
						'url' => ['/rbac/assignment']
					],
                
                ],
				'visible' => !Yii::$app->user->isGuest && Yii::$app->user->can('Admin'),
				'options'=>['class'=>'treeview'],
				'submenuTemplate'=>'<ul class="treeview-menu">{items}</ul>',
				'template'=>'<a href="#"><i class="fa fa-lock"></i>
				<span>{label}</span>
				<span class="pull-right-container">
				  <i class="fa fa-angle-left pull-right"></i>
				</span></a>'
			],
			[
                'label' => ' Settings',
                'items' => [
					[
						'label' => 'All companies /',
						'url' => ['/company-settings/list'],
						'visible' => !Yii::$app->user->isGuest && Yii::$app->user->can('Admin'),
					],
					[
						'label' => 'Company Settings',
						'url' => ['/company-settings/view?id='.$session['company.company_id']],
						'visible' => Yii::$app->user->can('manager'),
					],
                ],
				'visible' => Yii::$app->user->can('Admin') || Yii::$app->user->can('manager'),
				'options'=>['class'=>'treeview'],
				'submenuTemplate'=>'<ul class="treeview-menu">{items}</ul>',
				'template'=>'<a href="#"><i class="fa fa-cog"></i>
				<span>{label}</span>
				<span class="pull-right-container">
				  <i class="fa fa-angle-left pull-right"></i>
				</span></a>'
			],
			
		],
		'options'=>['class'=>'sidebar-menu','data-widget'=>'tree'],
	]);
	?>
      
    </section>
    <!-- /.sidebar #e7eced; -->
  </aside>
			
		
    <div class="content-wrapper" style="background:rgb(214, 224, 248) none repeat scroll 0% 0%">
		
		<!-- Content Header (Page header) -->
		<section class="content-header" >
			<div class="row">
				<div class=" col-md-12">
					<h1 class="hidden-xs">
					   <?= Html::encode($this->title) ?>
					   
					</h1>
			  
					<?= Breadcrumbs::widget([
						'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
					]) ?>
				</div>
		   </div>
		</section>
		
		<?php 
			if(Yii::$app->user->can('manager') || Yii::$app->user->can('executive')){
				
			$activation_upto = Companies::findOne($company)->activated_upto;
				$activation_upto_date = strtotime($activation_upto); 
				$today_date = strtotime(date('Y-m-d')); 
				$deactivation_msg = false;
			if($activation_upto_date>$today_date){
					if(($activation_upto_date - $today_date)/60/60/24 <= 5){
						$expiry_alert_msg = "Your package date ends on ".$activation_upto;
						$deactivation_msg = true;
					}		
			}else{
				$expiry_alert_msg = "Your package expired on ".$activation_upto;
				$deactivation_msg = true;
			}
			if($deactivation_msg){
				echo '<h5 class="alert alert-danger text-center">'.$expiry_alert_msg.'</h5>';
			}
			
		}?>    
		 
        
       
		<section class="content">
					
			 <?= Alert::widget() ?>
			 
				<?= $content ?>
			
		</section>
		
	</div>
    


<footer class="footer bg-purple" tabindex="1" style="border-top: 1px solid #5a0000;color: #fff;font-weight: 600;padding-top:8px">
    <div class="container">
        <p class="text-center" style="font-weight:600" >&copy; <?= date('Y') ?> v-1.2.0 <a style="color:#e0e0e0; text-decoration:underline" href="https://www.saltlake.in">SALTLAKE.IN WEB SERVICES LLP.</a>&nbsp;<span style="display:inline-block"><i class="fa fa-phone"> </i> 8777433027</span></p>
    </div>
</footer>
<?php $script = <<< JS
$(function(){
	$('#backprevious').on('click', function(e){
    e.preventDefault();
    window.history.back();
});


	$(".numeric").numeric();
	$('#reduce').on('click',function(){
		$('.content *').each(function(){
		   var k =  parseFloat($(this).css('font-size')); 
		   var redSize = ((k*98)/100) ; //here, you can give the percentage( now it is reduced to 90%)
			 $(this).css('font-size',redSize);  

		});
    });
	$('#increase').on('click',function(){
		$('.content *').each(function(){
		   var inc =  parseFloat($(this).css('font-size')); 
		   var incSize = ((inc*105)/100) ; 
			 $(this).css('font-size',incSize);  

		});
    });
	$(".load").click(function(){
		var url = $(this).attr('data-url');
		alert(url);
    $(".content").load(url);
	});
});
JS;

$this->registerJs($script);

$js = <<<SCRIPT
/* To initialize BS3 tooltips set this below */
$(function () { 
    $("[data-toggle='tooltip']").tooltip(); 
});;
/* To initialize BS3 popovers set this below */
$(function () { 
    $("[data-toggle='popover']").popover(); 
});
SCRIPT;
// Register tooltip/popover initialization javascript
$this->registerJs($js);
?>
<?php unset($this->assetBundles['yii\bootstrap\BootstrapPluginAsset']);?>
<?php $this->endBody() ?>

</body>
</html>
<?php $this->endPage() ?>
