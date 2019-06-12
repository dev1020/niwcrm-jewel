<?php
namespace common\components;
 
use Yii;
use yii\base\Component;

class StatsQueryComp extends Component {
	public function ordersValueToday($company=NULL,$branch=NULL,$userid=NULL){
		$connection = Yii::$app->getDb();
		$query = "SELECT SUM(total_amount) as total_amount FROM `orders` WHERE DATE_FORMAT(order_date, '%y-%m-%d') = DATE_FORMAT(NOW(), '%y-%m-%d')";
		if($company!='' || $company!=NULL){
			$query .= " AND company_id = $company" ;
		}
		if($branch!='' || $branch!=NULL){
			$query .= " AND orders.branch_id = $branch" ;
		}
		if($userid!=NULL){
			$query .= " AND created_by = $userid" ;
		}
		$command = $connection->createCommand($query);
		$order_total_amount_today = $command->queryOne();
		return (int)$order_total_amount_today['total_amount'];
	}
	public function ordersCountToday($company=NULL,$branch=NULL,$userid=NULL){
		$connection = Yii::$app->getDb();
		$query = "SELECT COUNT(id) as orderscount FROM `orders` WHERE DATE_FORMAT(order_date, '%y-%m-%d') = DATE_FORMAT(NOW(), '%y-%m-%d')";
		if($company!='' || $company!=NULL){
			$query .= " AND company_id = $company" ;
		}
		if($branch!='' || $branch!=NULL){
			$query .= " AND orders.branch_id = $branch" ;
		}
		if($userid!=NULL){
			$query .= " AND created_by = $userid" ;
		}
		$command = $connection->createCommand($query);
		$ordersCount = $command->queryOne();
		return $ordersCount['orderscount'];
	}
	
	public function ordersPendingToday($company=NULL,$branch=NULL,$userid=NULL){
		$connection = Yii::$app->getDb();
		$query = "SELECT COUNT(id) as orderspending FROM `orders` WHERE DATE_FORMAT(order_date, '%y-%m-%d') = DATE_FORMAT(NOW(), '%y-%m-%d') AND order_approved='no' ";
		if($company!='' || $company!=NULL){
			$query .= " AND company_id = $company" ;
		}
		if($branch!='' || $branch!=NULL){
			$query .= " AND orders.branch_id = $branch" ;
		}
		if($userid!=NULL){
			$query .= " AND created_by = $userid" ;
		}
		$command = $connection->createCommand($query);
		$pendingOrdersCount = $command->queryOne();
		return $pendingOrdersCount['orderspending'];
	}
	
	public function ordersCancelledToday($company=NULL,$branch=NULL,$userid=NULL){
		$connection = Yii::$app->getDb();
		$query = "SELECT COUNT(id) as orderscancelled FROM `orders` WHERE DATE_FORMAT(order_date, '%y-%m-%d') = DATE_FORMAT(NOW(), '%y-%m-%d') AND cancelled='yes' ";
		if($company!='' || $company!=NULL){
			$query .= " AND company_id = $company" ;
		}
		if($branch!='' || $branch!=NULL){
			$query .= " AND orders.branch_id = $branch" ;
		}
		if($userid!=NULL){
			$query .= " AND created_by = $userid" ;
		}
		$command = $connection->createCommand($query);
		$cancelledOrdersCount = $command->queryOne();
		return $cancelledOrdersCount['orderscancelled'];
	}
    
	
}
