<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "customers_delivery_address".
 *
 * @property int $id
 * @property string $delivery_address
 * @property int $customer_id
 */
class CustomersDeliveryAddress extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'customers_delivery_address';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['delivery_address', 'customer_id'], 'required'],
            [['delivery_address'], 'string'],
            [['customer_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'delivery_address' => 'Delivery Address',
            'customer_id' => 'Customer ID',
        ];
    }
}
