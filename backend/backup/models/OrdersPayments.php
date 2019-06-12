<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "orders_payments".
 *
 * @property int $id
 * @property int $orders_id
 * @property int $payment_type
 * @property string $amount
 * @property string $payment_date
 *
 * @property Orders $orders
 */
class OrdersPayments extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'orders_payments';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['orders_id', 'payment_type', 'amount', 'payment_date'], 'required'],
            [['orders_id'], 'integer'],
            [['amount'], 'number'],
            [['payment_date'], 'safe'],
            [['orders_id'], 'exist', 'skipOnError' => true, 'targetClass' => Orders::className(), 'targetAttribute' => ['orders_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'orders_id' => 'Orders ID',
            'payment_type' => 'Payment Type',
            'amount' => 'Amount',
            'payment_date' => 'Payment Date',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasOne(Orders::className(), ['id' => 'orders_id']);
    }
}
