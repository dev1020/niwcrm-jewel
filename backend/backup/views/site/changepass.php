<?php
	use yii\helpers\Html;
	use yii\widgets\ActiveForm;
/* @var $this yii\web\View */

$this->title = 'SGEM User change password.';
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
                                    <h3 class="box-title">Change Password</h3>
                                </div><!-- /.box-header -->
								<hr>

                                <div class="box-body">
                                    <?php $form = ActiveForm::begin(); ?>
										<?= $form->field($model, 'oldpassword')->passwordInput() ?>

										<?= $form->field($model, 'newpassword')->passwordInput() ?>
										<?= $form->field($model, 'passwordrepeat')->passwordInput() ?>
										
										<div class="form-group">
											<?= Html::submitButton('Change password', ['class' => 'btn btn-lg btn-primary btn-block']) ?>
										</div>
									<?php ActiveForm::end(); ?>	
								</div><!-- /.box-body -->
								
                            </div>
				</div>

			