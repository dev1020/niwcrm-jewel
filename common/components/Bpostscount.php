<?php
namespace common\components;
 
use Yii;
use yii\base\Component;
use backend\models\Bpostcategories;

class Bpostscount extends Component {

    public static function counter($bpost_id) {
		
        echo $categoriesBpostCount = Bpostcategories::find()->where(['bpostcategories_categories_id'=>$bpost_id])->count();
    }	
	 
}
