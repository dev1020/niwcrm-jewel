<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\CompanyCustomers */

?>
<div class="company-customers-create">
    <?= $this->render('_form', [
        'customermodel' => $customermodel,
        'model' => $model,
    ]) ?>
</div>
