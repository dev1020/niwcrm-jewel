<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\SeatsAndChairs */
?>
<div class="seats-and-chairs-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'seatlabel',
            'status',
        ],
    ]) ?>

</div>
