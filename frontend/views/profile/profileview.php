<?php

use yii\helpers\Html;
use yii\helpers\Url;


/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = Yii::$app->name.' | '.'User Profile.';

?>
<style>

</style>
<div class="row">
	  <h2>Profile</h2>
	  <hr>
	  <div class="row">
		<!-- left column -->
		<div class="col-md-3 col-sm-6 col-xs-12">
		  <div class="text-center">
			<img id="blah" src="<?= ($model->profilepic)?(Url::to('@frontendimage'.'/profilepic/'.$model->profilepic)):(Url::to('@frontendimage'.'/userpic.png'))?>" alt="your image" class="avatar img-circle img-thumbnail">
		  </div>
		</div>
		<!-- edit form column -->
		<div class="col-md-7 col-sm-6 col-xs-12 personal-info">
		    <?php if (Yii::$app->session->hasFlash('success')): ?>
			  <div class="alert alert-success alert-dismissable">
			  <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
			  <h4><i class="icon fa fa-check"></i> <?= Yii::$app->session->getFlash('success') ?></h4>
			  
			  </div>
			<?php endif; ?>
		  <h3>Personal info</h3>
		  <form class="form-horizontal" role="form">
			<div class="form-group">
			  <label class="col-lg-4 control-label text-info">First name:</label>
			  <div class="col-lg-8">
				<label class=" control-label"><?=($model->first_name)?($model->first_name):''?></label>
			  </div>
			</div>
			<div class="form-group">
			  <label class="col-lg-4 control-label text-info">Last name:</label>
			  <div class="col-lg-8">
				<label class=" control-label"><?=($model->last_name)?($model->last_name):''?></label>
			  </div>
			</div>
			
			<div class="form-group">
			  <label class="col-md-4 control-label text-info">Username:</label>
			  <div class="col-md-8">
				<label class=" control-label"><?=($model->username)?($model->username):''?></label>
			  </div>
			</div>
			
			<div class="form-group">
			  <label class="col-md-4 control-label text-info">Contact:</label>
			  <div class="col-md-8">
				<label class=" control-label"><?=($model->contact_number)?($model->contact_number):''?></label>
			  </div>
			</div>
			
			<div class="form-group">
			  <label class="col-lg-4 control-label text-info">Email:</label>
			  <div class="col-lg-8">
				<label class=" control-label"><?=($model->email)?($model->email):''?></label>
			  </div>
			</div>
			
			
			<div class="form-group">
			  <label class="col-md-4 control-label text-info">Address Line 1 :</label>
			  <div class="col-md-8">
				<label class=" control-label"><?=($model->address)?($model->address):''?></label>
			  </div>
			</div>
			<div class="form-group">
			  <label class="col-md-4 control-label text-info">Address line 2:</label>
			  <div class="col-md-8">
				<label class=" control-label"><?=($model->address2)?($model->address2):''?></label>
			  </div>
			</div>
			<div class="form-group">
			  <label class="col-md-4 control-label text-info">Pincode:</label>
			  <div class="col-md-8">
				<label class=" control-label"><?=($model->pin)?($model->pin):''?></label>
			  </div>
			</div>
			
		  </form>
		</div>
		
		<div class="col-md-2">
			<div class="btn-group">
				<a class="btn dropdown-toggle btn-info" data-toggle="dropdown" href="#">
					Action 
					<span class="icon-cog icon-white"></span><span class="caret"></span>
				</a>
				<ul class="dropdown-menu">
					<li><?=  Html::a('<i class="fa fa-wrench"></i> Modify/Edit', ['update'],
			['title'=> 'Edit','class'=>''])?></li>
					
				</ul>
			</div>
		</div>
	  </div>
	
</div>
<?php $script = <<< JS
$("#imgupload").change(function(){
		readURL(this);
	});

JS;
$this->registerJs($script);
?>

<script>
	
	function readURL(input) {

		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function (e) {
				$('#blah').attr('src', e.target.result);
			}
			reader.readAsDataURL(input.files[0]);
		}
	}
</script>