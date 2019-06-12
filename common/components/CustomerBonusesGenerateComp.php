<?php
namespace common\components;
 
use Yii;
use yii\base\Component;
use backend\models\CustomersBonuses;

use backend\models\SettingsOptions;
use backend\models\CompanySettings;
use backend\models\Orders;
use backend\models\Customers;


class CustomerBonusesGenerateComp extends Component {

    public function generateLoyaltyBonuses($orderid){
		$company_id = Orders::findOne($orderid)->company_id;
		if(CompanySettings::find()->where(['company_id'=>$company_id])->exists()){
			$loyalypercentage = CompanySettings::find()->where(['company_id'=>$company_id])->one()->loyalty_bonus_percentage;
			if(Orders::findOne($orderid)->status=='completed'){
				$cust_id = Orders::findOne($orderid)->cust_id;
				$bonus_amount = round((Orders::findOne($orderid)->total_amount) * ($loyalypercentage/100));
				$model = new CustomersBonuses();
				$model->cust_id = $cust_id;
				$model->type = 'loyalty';
				$model->order_id = $orderid;
				$model->created_date = date('y-m-d');
				$model->bonus_amount = $bonus_amount;
				$model->save();
				
				return ['status'=>true,'msg'=>'Loyalty Bonus Generated.','bonus'=>round($bonus_amount)];
			}else{
				return ['status'=>false,'msg'=>'Order Payment Is due.'];
			}			
			
		}else{
			return ['status'=>false,'msg'=>'Please set the Loyalty Bonus percentage'];
		}
        
    }
	
	public function generateReferralBonuses($orderid){
		if(CompanySettings::find()->where(['company_id'=>$company_id])->exists()){
			$referralpercentage = CompanySettings::find()->where(['company_id'=>$company_id])->one()->referral_bonus_percentage;
			if(Orders::findOne($orderid)->status=='completed'){
				$cust_id = Orders::findOne($orderid)->cust_id;
				$introducer_id = Customers::findOne($cust_id)->introducer_customer_id;
				
				$bonus_amount = round((Orders::findOne($orderid)->total_amount) * ($referralpercentage/100));
				$model = new CustomersBonuses();
				$model->cust_id = $introducer_id;
				$model->type = 'referral';
				$model->order_id = $orderid;
				$model->created_date = date('y-m-d');
				$model->bonus_amount = $bonus_amount;
				$model->save();
				
				return ['status'=>true,'msg'=>'Referral Bonus Generated.','bonus'=>round($bonus_amount)];
			}else{
				return ['status'=>false,'msg'=>'Order Payment Is due.'];
			}			
			
		}else{
			return ['status'=>false,'msg'=>'Please set the Referral Bonus percentage'];
		}
        
    }
	
	public function redeemBonuses($orderid,$points){
		$model = new CustomersBonuses();
		$model->cust_id = Orders::findOne($orderid)->cust_id;
		$model->type = 'redeem';
		$model->order_id = $orderid;
		$model->created_date = date('y-m-d');
		$model->bonus_amount = $points;
		$model->save();
    }

    
}
