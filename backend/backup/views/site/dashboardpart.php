

<?php Pjax::begin(); ?>
    <?= Html::a("Refresh", ['site/test'], ['class' => 'btn btn-lg btn-primary']) ?>
    <h1>Current time: <?= $time ?></h1>
    <?php Pjax::end(); ?>