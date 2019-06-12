<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\CompanyBranches */
?>
<div class="company-branches-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'company_id',
            'branch_name',
            'branch_location:ntext',
            'created_by',
            'created_at',
        ],
    ]) ?>

</div>
