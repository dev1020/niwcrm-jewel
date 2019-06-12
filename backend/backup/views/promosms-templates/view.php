<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\PromosmsTemplates */
?>
<div class="promosms-templates-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'sms_title',
            'sms_body:ntext',
        ],
    ]) ?>

</div>
