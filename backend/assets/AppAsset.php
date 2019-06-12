<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
		
		'css/jquery-ui.css',
		'css/jquery-confirm.min.css',
		'css/jquery.timepicker.css',
        'css/AdminLTE.css',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css',
        'css/skin-purple.css',
    ];
    public $js = [
		'js/jquery-ui.js',
		'js/bootstrap.min.js',
		'js/adminlte.min.js',
		'js/mousetrap.js',
		'js/jquery.timepicker.js',
		'js/jquery.slimscroll.min.js',
		'js/ModalRemote.min.js',
        'js/ajaxcrud.min.js',
		'js/custom.js',
		'js/numeric.js',
		'js/jquery-confirm.min.js',
		'js/highcharts-3d.js',
		
    ];
    public $depends = [
       'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
