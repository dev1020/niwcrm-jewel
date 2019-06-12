<?php
	use yii\helpers\Html;
	use yii\widgets\Pjax;
	use yii\helpers\Url;
/* @var $this yii\web\View */

$this->title = 'SGEM User Profile.';
?>
   
			
				<div class="col-sm-2 quicklinks">
					<div class="box box-info " style="min-height:400px">
						<div class="box-header">
								<i class="fa fa-link"></i>
                                <h3 class="box-title">Quick Links</h3>
						</div>

						<ul class="nav nav-pills nav-stacked">
								<li role="presentation" class="active"><?= Html::a('Profile', ['profile']) ?></li>
								<li role="presentation"><?= Html::a('Change Password', ['changepassword']) ?></li>
								
						</ul>
				
					</div>
				</div>
				<div class="col-sm-10">
					<div class="box box-info">
                                <div class="box-header">
                                    <i class="fa fa-user"></i>
                                    <h3 class="box-title">Profile</h3>
                                </div><!-- /.box-header -->
								<hr>

                                <div class="box-body">
                                    <label>Profile Picture : </label>
									<span><img style="height:150px"src="<?= Url::base().'/'.$model->profilepic?>"></span>
									<br>
									<label>First Name : </label>
									<span><?= $model->first_name;?></span>
									<br>
									<label>Last Name : </label>
									<span><?= $model->last_name;?></span>
									<br>
									<label>Username : </label>
									<span><?= $model->username;?></span>
									<br>
									<label>Email : </label>
									<span><?= $model->email;?></span>
									<br>
									<label>Contact : </label>
									<span><?= $model->contact_number;?></span>
									<br>
									
										
								</div><!-- /.box-body -->
								
                            </div>
				</div>

			