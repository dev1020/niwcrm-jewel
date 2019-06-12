<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "customers_bonuses".
 *
 * @property int $id
 * @property int $cust_id
 * @property string $type
 * @property int $order_id
 * @property string $created_date
 * @property string $valid_upto
 * @property string $bonus_amount
 *
 * @property Customers $cust
 * @property Orders $order
 */
class CustomersBonuses extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'customers_bonuses';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cust_id', 'type', 'order_id', 'created_date', 'bonus_amount'], 'required'],
            [['cust_id', 'order_id'], 'integer'],
            [['type'], 'string'],
            [['created_date', 'valid_upto'], 'safe'],
            [['bonus_amount'], 'number'],
            [['cust_id'], 'exist', 'skipOnError' => true, 'targetClass' => Customers::className(), 'targetAttribute' => ['cust_id' => 'id']],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Orders::className(), 'targetAttribute' => ['order_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cust_id' => 'Cust ID',
            'type' => 'Type',
            'order_id' => 'Order ID',
            'created_date' => 'Created Date',
            'valid_upto' => 'Valid Upto',
            'bonus_amount' => 'Bonus Amount',
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
    public function getOrder()
    {
        return $this->hasOne(Orders::className(), ['id' => 'order_id']);
    }
}
