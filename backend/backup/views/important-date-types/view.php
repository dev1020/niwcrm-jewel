<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\ImportantDateTypes */
?>
<div class="important-date-types-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'type_name',
        ],
    ]) ?>

</div>
