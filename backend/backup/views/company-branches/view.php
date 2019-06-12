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
            'branchaddress',
            'branchname',
            'branchcontact_no',
        ],
    ]) ?>

</div>
