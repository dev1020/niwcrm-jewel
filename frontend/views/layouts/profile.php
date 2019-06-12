<?php 
use yii\helpers\Url;
use yii\helpers\Html;

?>
<?php $this->beginContent('@app/views/layouts/main.php'); ?>
<style>
/* Profile sidebar */
.profile-sidebar {
  padding: 20px 0 10px 0;
  background: #fff;
}

.profile-userpic img {
  float: none;
  margin: 0 auto;
  width: 50%;
  height: 50%;
  -webkit-border-radius: 50% !important;
  -moz-border-radius: 50% !important;
  border-radius: 50% !important;
}

.profile-usertitle {
  text-align: center;
  margin-top: 20px;
}

.profile-usertitle-name {
  color: #5a7391;
  font-size: 16px;
  font-weight: 600;
  margin-bottom: 7px;
}

.profile-usertitle-job {
  text-transform: uppercase;
  color: #5b9bd1;
  font-size: 12px;
  font-weight: 600;
  margin-bottom: 15px;
}

.profile-userbuttons {
  text-align: center;
  margin-top: 10px;
}

.profile-userbuttons .btn {
  text-transform: uppercase;
  font-size: 11px;
  font-weight: 600;
  padding: 6px 15px;
  margin-right: 5px;
}

.profile-userbuttons .btn:last-child {
  margin-right: 0px;
}
    
.profile-usermenu {
  margin-top: 30px;
}

.profile-usermenu ul li {
  border-bottom: 1px solid #dde8f0;
background: #f3f3f3;
}

.profile-usermenu ul li:last-child {
  border-bottom: none;
}

.profile-usermenu ul li a {
  color: #6f4dcc;
  font-size: 14px;
  font-weight: 600;
}

.profile-usermenu ul li a i {
  margin-right: 8px;
  font-size: 14px;
}

.profile-usermenu ul li a:hover {
  background-color: #fafcfd;
  color: #5b9bd1;
}

.profile-usermenu ul li.active {
  border-bottom: none;
}

.profile-usermenu ul li.active a {
  color: #5b9bd1;
  background-color: #f6f9fb;
  border-left: 2px solid #5b9bd1;
  margin-left: -2px;
}
</style>
	
		
			<div class="row">
				
					<div  style="padding-bottom:10px;" class="col-lg-12">
									
						
						<div class="col-md-3">
							<div class="profile-sidebar">
								<!-- SIDEBAR USERPIC -->
								<div class="profile-userpic">
									<img src="http://keenthemes.com/preview/metronic/theme/assets/admin/pages/media/profile/profile_user.jpg" class="img-responsive" alt="">
								</div>
								<!-- END SIDEBAR USERPIC -->
								<!-- SIDEBAR USER TITLE -->
								<div class="profile-usertitle">
									<div class="profile-usertitle-name">
										Marcus Doe
									</div>
									<div class="profile-usertitle-job">
										Developer
									</div>
								</div>
								<!-- END SIDEBAR USER TITLE -->
								
								<!-- SIDEBAR MENU -->
								<div class="profile-usermenu">
									<ul class="nav">
										<li><?=  Html::a('<i class="fa fa-dashboard"></i> Profile', ['/profile/dashboard'],
			['title'=> 'Dashboard','class'=>''])?></li>
										<li><?=  Html::a('<i class="fa fa-user-plus"></i> Settings', ['/profile/view'],
			['title'=> 'Profile','class'=>''])?></li>
										<li><?=  Html::a('<i class="fa fa-edit"></i> Change Password', ['/profile/changepassword'],
			['title'=> 'Change Password','class'=>''])?></li>
										<li><?=  Html::a('<i class="fa fa-inr"></i> Orders', ['/profile/orders'],
			['title'=> 'Add Business','class'=>''])?></li>
										<li><?=  Html::a('<i class="fa fa-gift"></i> Your Points ', ['/profile/points-details'],
			['title'=> 'Add Business','class'=>''])?></li>
										<li><?=  Html::a('<i class="fa fa-users"></i> Your Referrals', ['/profile/referrals'],
			['title'=> 'SOS','class'=>''])?></li>
										<li><?=  Html::a('<i class="fa fa-calendar"></i> Add Important Dates', ['/profile/sos-program'],
			['title'=> 'SOS','class'=>''])?></li>
										
									</ul>
								</div>
								<!-- END MENU -->
							</div>
							
						</div>

						<div class="col-md-9 " style="border-left: 2px solid #999999; ">
							<?= $content ?>
						</div>
					<div class="clearfix"> </div>
						
								
				</div>
				
			</div>
		

		
	
<?php $this->endContent(); ?>