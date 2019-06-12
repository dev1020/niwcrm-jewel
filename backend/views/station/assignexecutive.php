<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use common\models\User;

?>


<?php $form = ActiveForm::begin(['options'=>['id'=>'assign-executive-form']]); ?>
		<?= $form->field($model, 'assigned_executive_id')->widget(Select2::classname(), [
							'data' => ArrayHelper::map(User::find()->where(['usertype'=>'backenduser'])->orderBy(['id' => SORT_ASC])->all(),'id','username'),
							'language' => 'en',
							'options' => ['placeholder' => 'Select a Waiter/DelvBoy'],
							'pluginOptions' => [
								'allowClear' => true,
							]
						])->label('Waiter/DelvBoy Id');
					?>
<?php ActiveForm::End() ?>

