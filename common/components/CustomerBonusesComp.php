<?php
namespace common\components;
 
use Yii;
use yii\base\Component;
use backend\models\CustomersBonuses;


class CustomerBonusesComp extends Component {

    public function checkBonuses($custid,$company_id){
        $model = new CustomersBonuses();
		if($model->find()->joinWith(['order'])->where(['customers_bonuses.cust_id'=>$custid,'type'=>'loyalty','company_id'=>$company_id,'customers_bonuses.cancelled'=>'no'])->exists()){
			$loyalty_bonuses = $model->find()->joinWith(['order'])->where(['customers_bonuses.cust_id'=>$custid,'type'=>'loyalty','company_id'=>$company_id,'customers_bonuses.cancelled'=>'no'])->sum('bonus_amount');
		}else{
			$loyalty_bonuses = 0;
		}
		if($model->find()->joinWith(['order'])->where(['customers_bonuses.cust_id'=>$custid,'type'=>'referral','company_id'=>$company_id,'customers_bonuses.cancelled'=>'no'])->exists()){
			$referral_bonuses = $model->find()->joinWith(['order'])->where(['customers_bonuses.cust_id'=>$custid,'type'=>'referral','company_id'=>$company_id,'customers_bonuses.cancelled'=>'no'])->sum('bonus_amount');
		}else{
			$referral_bonuses = 0;
		}
		if($model->find()->joinWith(['order'])->where(['customers_bonuses.cust_id'=>$custid,'type'=>'redeem','company_id'=>$company_id,'customers_bonuses.cancelled'=>'no'])->exists()){
			$redeem_bonuses = $model->find()->joinWith(['order'])->where(['customers_bonuses.cust_id'=>$custid,'type'=>'redeem','company_id'=>$company_id,'customers_bonuses.cancelled'=>'no'])->sum('bonus_amount');
		}else{
			$redeem_bonuses = 0;
		}
		
		$totalavailablebonus = $loyalty_bonuses+$referral_bonuses-$redeem_bonuses;
		//$totalavailablebonus = number_format((float)$totalavailablebonus, 2, '.', '');
        return ['loyalty'=>$loyalty_bonuses,'referral'=>$referral_bonuses,'redeem'=>$redeem_bonuses,'available'=>$totalavailablebonus];
    }

    
}
