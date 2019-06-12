<?php

namespace backend\models;

use Yii;
use common\models\User;

/**
 * This is the model class for table "orders".
 *
 * @property int $id
 * @property string $order_date
 * @property int $cust_id
 * @property string $status
 * @property string $session_nos
 * @property string $total_amount
 * @property int $created_by
 * @property string $created_date
 * @property string $sms_delivered
 *
 * @property CustomersBonuses[] $customersBonuses
 * @property User $createdBy
 * @property Customers $cust
 */
class Orders extends \yii\db\ActiveRecord
{
	
	public $customer_contact;
	public $customer_name;
	public $points;
	public $settings;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'orders';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_date', 'total_amount','cust_id','created_by','company_id'], 'required'],
            [['customer_contact'], 'required','on'=>'newsale'],
            [['customer_contact','branch_id'], 'required','on'=>'newbranchsale'],
            [['order_date', 'created_date','settings','cancelled_at'], 'safe'],
            [['cust_id', 'created_by','company_id','branch_id'], 'integer'],
            [['status', 'sms_delivered','customer_name','cancelled'], 'string'],
            [['customer_contact'], 'number'],
            [['points'], 'integer'],
            [['total_amount','weight'], 'double'],
            [['session_nos','order_note'], 'safe'],
            ['session_nos', 'unique', 'targetAttribute' => ['company_id','session_nos'],'message' => 'Duplicate Invoice number Please check it.'],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
			[['cancelled_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],			
            [['cust_id'], 'exist', 'skipOnError' => true, 'targetClass' => CompanyCustomers::className(), 'targetAttribute' => ['cust_id' => 'cust_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_date' => 'Order Date',
            'cust_id' => 'Customer',
            'status' => 'Status',
            'session_nos' => 'Invoice No',
            'total_amount' => 'Total Amount',
            'created_by' => 'Created By',
            'created_date' => 'Created Date',
            'sms_delivered' => 'Sms',
            'order_note' =>'Note',
            'weight'=>'Weight (gm)'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomersBonuses()
    {
        return $this->hasMany(CustomersBonuses::className(), ['order_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCust()
    {
        return $this->hasOne(Customers::className(), ['id' => 'cust_id']);
    }
	
	public function getCompany()
    {
        return $this->hasOne(Companies::className(), ['id' => 'company_id']);
    }
	
	public function getBranch()
    {
        return $this->hasOne(CompanyBranches::className(), ['id' => 'branch_id']);
    }
	public function getOrdersPayments()
    {
        return $this->hasMany(OrdersPayments::className(), ['orders_id' => 'id']);
    }
}
