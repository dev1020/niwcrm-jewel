<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "customers".
 *
 * @property int $id
 * @property string $name
 * @property string $contact
 * @property int $user_id
 * @property string $gender
 * @property int $introducer_customer_id
 * @property string $address
 
 * @property string $other
 *
 * @property Customers $introducerCustomer
 * @property Customers[] $customers
 * @property CustomersBonuses[] $customersBonuses
 * @property CustomersImportantDates[] $customersImportantDates
 * @property CustomersLog[] $customersLogs
 * @property CustomersServices[] $customersServices
 * @property Orders[] $orders
 */
class Customers extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
	 
	public $other;
	
	
    public static function tableName()
    {
        return 'customers';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            //[['name'], 'required'],
            [['other'], 'required','on' => 'newsession'],
			
            [['user_id', 'introducer_customer_id'], 'integer'],
            [['gender','other'], 'string'],
            [['name', 'address'], 'string', 'max' => 255],
            [['contact'], 'string', 'max' => 15],
            [['contact'], 'unique'],
            [['customer_pic'], 'file'],
            [['introducer_customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Customers::className(), 'targetAttribute' => ['introducer_customer_id' => 'id']],
			
			
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'contact' => 'Contact',
            'user_id' => 'User ID',
            'gender' => 'Gender',
            'introducer_customer_id' => 'Introducer Customer ID',
            'address' => 'Address',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIntroducerCustomer()
    {
        return $this->hasOne(Customers::className(), ['id' => 'introducer_customer_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomers()
    {
        return $this->hasMany(Customers::className(), ['introducer_customer_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomersBonuses()
    {
        return $this->hasMany(CustomersBonuses::className(), ['cust_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomersImportantDates()
    {
        return $this->hasMany(CustomersImportantDates::className(), ['cust_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomersLogs()
    {
        return $this->hasMany(CustomersLog::className(), ['cust_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomersServices()
    {
        return $this->hasMany(CustomersServices::className(), ['cust_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Orders::className(), ['cust_id' => 'id']);
    }
}
