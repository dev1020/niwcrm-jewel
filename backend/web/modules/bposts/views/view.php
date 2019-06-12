<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\bposts\models\Bposts */
?>
<div class="bposts-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'bpost_id',
            'bpost_title',
            'bpost_category_id',
            'bpost_place_id',
            'bpost_rating',
            'bpost_description:ntext',
            'bpost_created_by',
            'bpost_created_at',
            'bpost_updated_at',
            'bpost_is_featured',
            'bpost_hitcounter',
            'bpost_phone',
            'bpost_whatsapp',
            'bpost_smsnumber',
            'bpost_email:email',
            'bpost_website',
            'bpost_open24hour',
            'bpost_homedelivery',
            'bpost_alldayopen',
            'bpost_openfrom',
            'bpost_opento',
            'bpost_ismonday',
            'bpost_monfrom',
            'bpost_monto',
            'bpost_istuesday',
            'bpost_tuefrom',
            'bpost_tueto',
            'bpost_iswednesday',
            'bpost_wedfrom',
            'bpost_wedto',
            'bpost_isthursday',
            'bpost_thufrom',
            'bpost_thuto',
            'bpost_isfriday',
            'bpost_frifrom',
            'bpost_frito',
            'bpost_issaturday',
            'bpost_satfrom',
            'bpost_satto',
            'bpost_issunday',
            'bpost_sunfrom',
            'bpost_sunto',
            'bpost_iscash',
            'bpost_iscreditcard',
            'bpost_isdebitcard',
            'bpost_isewallet',
            'bpost_ispaytm',
        ],
    ]) ?>

</div>
