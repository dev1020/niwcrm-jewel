<?php
	use yii\helpers\Html;
	use yii\helpers\Url;
	use yii\widgets\ActiveForm;
/* @var $this yii\web\View */

$this->title = 'Edit Profile';
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
                                    <h3 class="box-title">Edit Profile</h3>
                                </div><!-- /.box-header -->
								<hr>

                                <div class="box-body">
                                    <?php $form = ActiveForm::begin(); ?>
										
										<div class="col-lg-12">
                        					<div class="col-lg-6 image" >
                        						<img id="preview" src="<?= ($model->profilepic)?(Url::to('@frontendimage'.'/profilepic/'.$model->profilepic)):(Url::to('@frontendimage'.'/noimage.png'))?>" alt="your image" />
                        						
                        					</div>
                        					
                        					<div class="col-lg-6" >
                        						<div class="col-lg-12 clearfix">
                        						
                        						<?= $form->field($model, 'profilepic',[
                        												//'template' => "{label}<div class='col-md-7 col-xs-9'>{input}</div>{hint}{error}",
                        												//'labelOptions' => ['class' =>'col-md-5 col-xs-3 text-right']
                        								])->fileInput(['class'=>'imgupload','id'=>'profilepic']) ?>
                        						</div>
                        						
                        					</div>
                        				</div>
										<?= $form->field($model, 'first_name')->textInput(['autofocus' => true]) ?>
										<?= $form->field($model, 'last_name')->textInput() ?>
										<?= $form->field($model, 'username')->textInput() ?>
										<?= $form->field($model, 'email')->textInput() ?>
										<?= $form->field($model, 'contact_number')->textInput() ?>
										
										<div class="form-group">
											<?= Html::submitButton('Save', ['class' => 'btn btn-lg btn-primary btn-block']) ?>
										</div>
									<?php ActiveForm::end(); ?>	
								</div><!-- /.box-body -->
								
                            </div>
				</div>

<?php $script = <<< JS
    $(function(){
       function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
    			if(input.id=="profilepic"){
    				$('#preview').attr('src', e.target.result);
    			}
            }
            reader.readAsDataURL(input.files[0]);
        }
    } 
    $(".imgupload").change(function(){
        readURL(this);
    });

});

JS;
$this->registerJs($script);
?>
			