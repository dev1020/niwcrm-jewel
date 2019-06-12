<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\BpostsRatings */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bposts-ratings-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'rating_bposts_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'rating_score')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'rating_review_text')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'rating_user_id')->textInput() ?>

    

  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
