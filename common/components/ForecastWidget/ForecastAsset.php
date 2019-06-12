<?php


namespace common\components\ForecastWidget;
use yii\web\AssetBundle;

class ForecastAsset extends AssetBundle {

   public $sourcePath = '@common/components/ForecastWidget/assets/';

    public $css = ['forecast.css'];
    public $js = ['forecast.js'];
    public $images = ['/images'];

    public $depends = ['yii\web\JqueryAsset'];
}