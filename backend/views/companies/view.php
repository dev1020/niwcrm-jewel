<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Companies */
?>
<div class="companies-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'company_name',
            'company_address:ntext',
            'company_contact',
            'created_by',
            'created_at',
        ],
    ]) ?>

</div>
