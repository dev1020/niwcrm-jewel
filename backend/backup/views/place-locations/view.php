<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\PlaceLocations */
?>
<div class="place-locations-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'loc_id',
            'loc_name',
            'loc_status',
            'loc_city_id',
        ],
    ]) ?>

</div>
