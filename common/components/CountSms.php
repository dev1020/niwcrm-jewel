<?php
namespace common\components;
 
use Yii;
use yii\base\Component;
use backend\models\Companies;


class CountSms extends Component {

   public function countquota($company_id)
    {
		$smsquota = Companies::findOne($company_id)->sms_quota;
		if($smsquota>0){
			return true;
		}else{
			return false;
		}
	}
}
