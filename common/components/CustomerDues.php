<?php
namespace common\components;
 
use Yii;
use yii\base\Component;
use backend\models\Orders;
use backend\models\Customers;
use backend\models\CustomersServices;


class CustomerDues extends Component {

    public function getDues($custid){
		$due_amount = Orders::find()->where(['cust_id'=>$custid,'status'=>'isdue'])->sum('due_amount');
		return ['due_amount'=>(int)$due_amount];
    }
	
	public function getBillValue($custid,$session_no){
		if(Orders::find()->where(['cust_id'=>$custid])->andWhere('find_in_set(:key, session_nos)', [':key' => $session_no])->exists()){
						
			$orders = Orders::find()->where(['cust_id'=>$custid])->andWhere('find_in_set(:key, session_nos)', [':key' => $session_no])->one();
			$total_amount = $orders->total_amount;
			$status = 'billed';
		}elseif(CustomersServices::find()->where(['cust_id'=>$custid,'session_no'=>$session_no,'billing_status'=>'unbilled'])->exists()){
				$total_amount = CustomersServices::find()->where(['cust_id'=>$custid,'session_no'=>$session_no,'billing_status'=>'unbilled'])->sum('services_price');
				$status = 'unbilled';
		}else{
			$total_amount = 0;
			$status = 'unbilled';
		}
		
		return ['total_amount'=>(int)$total_amount,'status'=>$status];
	}
	public function getBillValuebyseat($seatid,$session_no){
		if(Orders::find()->where(['seat_id'=>$seatid])->andWhere('find_in_set(:key, session_nos)', [':key' => $session_no])->exists()){
						
			$orders = Orders::find()->where(['seat_id'=>$seatid])->andWhere('find_in_set(:key, session_nos)', [':key' => $session_no])->one();
			$total_amount = $orders->total_amount;
			$status = 'billed';
		}elseif(CustomersServices::find()->where(['seat_id'=>$seatid,'session_no'=>$session_no,'billing_status'=>'unbilled'])->exists()){
				$total_amount = CustomersServices::find()->where(['seat_id'=>$seatid,'session_no'=>$session_no,'billing_status'=>'unbilled'])->sum('services_price * services_quantity');
				$status = 'unbilled';
		}else{
			$total_amount = 0;
			$status = 'unbilled';
		}
		
		return ['total_amount'=>(int)$total_amount,'status'=>$status];
	}
	
}
