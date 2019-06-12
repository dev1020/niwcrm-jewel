<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\BpostsRatings */
?>
<div class="bposts-ratings-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'rating_id',
            'rating_bposts_id',
            'rating_score',
            'rating_review_text:ntext',
            'rating_user_id',
            'created_at',
        ],
    ]) ?>

</div>
