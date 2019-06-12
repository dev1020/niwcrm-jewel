<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\bposts\models\Bposts */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bposts-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'bpost_title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'bpost_category_id')->textInput() ?>

    <?= $form->field($model, 'bpost_place_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'bpost_rating')->textInput() ?>

    <?= $form->field($model, 'bpost_description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'bpost_created_by')->textInput() ?>

    <?= $form->field($model, 'bpost_created_at')->textInput() ?>

    <?= $form->field($model, 'bpost_updated_at')->textInput() ?>

    <?= $form->field($model, 'bpost_is_featured')->dropDownList([ 'Y' => 'Y', 'N' => 'N', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'bpost_hitcounter')->textInput() ?>

    <?= $form->field($model, 'bpost_phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'bpost_whatsapp')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'bpost_smsnumber')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'bpost_email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'bpost_website')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'bpost_open24hour')->dropDownList([ 'Y' => 'Y', 'N' => 'N', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'bpost_homedelivery')->dropDownList([ 'Y' => 'Y', 'N' => 'N', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'bpost_alldayopen')->dropDownList([ 'Y' => 'Y', 'N' => 'N', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'bpost_openfrom')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'bpost_opento')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'bpost_ismonday')->dropDownList([ 'Y' => 'Y', 'N' => 'N', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'bpost_monfrom')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'bpost_monto')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'bpost_istuesday')->dropDownList([ 'Y' => 'Y', 'N' => 'N', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'bpost_tuefrom')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'bpost_tueto')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'bpost_iswednesday')->dropDownList([ 'Y' => 'Y', 'N' => 'N', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'bpost_wedfrom')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'bpost_wedto')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'bpost_isthursday')->dropDownList([ 'Y' => 'Y', 'N' => 'N', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'bpost_thufrom')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'bpost_thuto')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'bpost_isfriday')->dropDownList([ 'Y' => 'Y', 'N' => 'N', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'bpost_frifrom')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'bpost_frito')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'bpost_issaturday')->dropDownList([ 'Y' => 'Y', 'N' => 'N', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'bpost_satfrom')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'bpost_satto')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'bpost_issunday')->dropDownList([ 'Y' => 'Y', 'N' => 'N', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'bpost_sunfrom')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'bpost_sunto')->dropDownList([ 'Y' => 'Y', 'N' => 'N', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'bpost_iscash')->dropDownList([ 'Y' => 'Y', 'N' => 'N', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'bpost_iscreditcard')->dropDownList([ 'Y' => 'Y', 'N' => 'N', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'bpost_isdebitcard')->dropDownList([ 'Y' => 'Y', 'N' => 'N', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'bpost_isewallet')->dropDownList([ 'Y' => 'Y', 'N' => 'N', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'bpost_ispaytm')->dropDownList([ 'Y' => 'Y', 'N' => 'N', ], ['prompt' => '']) ?>

  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
