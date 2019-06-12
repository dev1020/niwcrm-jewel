<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "customers_services".
 *
 * @property int $id
 * @property int $cust_id
 * @property int $service_id
 * @property string $service_status
 * @property string $service_start_time
 * @property string $service_end_time
 * @property int $services_quantity
 *
 * @property Customers $cust
 * @property Services $service
 */
class CustomersServices extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'customers_services';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cust_id', 'service_status','services_date'], 'required'],
            [['cust_id', 'service_id','services_quantity'], 'integer'],
            [['service_status'], 'string'],
            [['service_start_time', 'service_end_time'], 'string', 'max' => 15],
            [['cust_id'], 'exist', 'skipOnError' => true, 'targetClass' => Customers::className(), 'targetAttribute' => ['cust_id' => 'id']],
            [['service_id'], 'exist', 'skipOnError' => true, 'targetClass' => Services::className(), 'targetAttribute' => ['service_id' => 'id']],
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
            'service_id' => 'Service ID',
            'service_status' => 'Service Status',
            'service_start_time' => 'Service Start Time',
            'service_end_time' => 'Service End Time',
            'services_date' => 'Service Date',
            'services_quantity' => 'Quantity',
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
    public function getService()
    {
        return $this->hasOne(Services::className(), ['id' => 'service_id']);
    }
}
