<?php
namespace common\components;
 
use Yii;
use yii\base\Component;



class SliderComponent extends Component {

    public static function getSliders($model) {
        global $data;
		$sliders = $model->find()->all();
       $data = $sliders;
	   return $data;
    }
}