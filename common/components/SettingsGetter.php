<?php
namespace common\components;
 
use Yii;
use yii\base\Component;
use backend\models\CompanySettings;


class SettingsGetter extends Component {

    public function get_attribute_value($attributename,$company_id){
        if(CompanySettings::find()->where(['company_id'=>$company_id])->exists()){
			$attribute_value = CompanySettings::find()->where(['company_id'=>$company_id])->one()->$attributename;	
			return $attribute_value;
		}else{
			return false;
		}
    }
}
