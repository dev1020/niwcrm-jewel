<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "orders".
 *
 * @property int $id
 * @property string $order_date
 * @property int $cust_id
 * @property string $status
 * @property string $session_nos
 * @property string $total_amount
 * @property string $due_amount
 *
 * @property Customers $cust
 * @property OrdersDetails[] $ordersDetails
 */
class Orders extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
	 public $category_name;
	public $total_price;
	public $catcount;
	public $category_id;
	
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
            [['order_date', 'cust_id', 'status', 'session_nos', 'total_amount', 'due_amount'], 'required'],
            [['order_date'], 'safe'],
            [['cust_id','order_boy_id'], 'integer'],
            [['status','order_type '], 'string'],
            [['total_amount', 'due_amount'], 'number'],
            [['session_nos'], 'string', 'max' => 255],
            [['cust_id'], 'exist', 'skipOnError' => true, 'targetClass' => Customers::className(), 'targetAttribute' => ['cust_id' => 'id']],
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
            'session_nos' => 'Session Nos',
            'total_amount' => 'Total Amount',
            'due_amount' => 'Due Amount',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCust()
    {
        return $this->hasOne(Customers::className(), ['id' => 'cust_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrdersDetails()
    {
        return $this->hasMany(OrdersDetails::className(), ['orders_id' => 'id']);
    }
	
	public function getOrdersPayments()
    {
        return $this->hasMany(OrdersPayments::className(), ['orders_id' => 'id']);
    }
}
