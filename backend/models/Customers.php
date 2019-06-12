<?php

namespace backend\models;

use Yii;
//use backend\models\CompanyCustomers;

/**
 * This is the model class for table "customers".
 *
 * @property int $id
 * @property string $name
 * @property string $contact
 * @property int $user_id
 * @property string $gender
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
            [['contact'], 'required','on' => 'companycustomer'],
			
            [['user_id',], 'integer'],
            [['gender','other'], 'string'],
            
            [['address','name'], 'safe'],
            
            [['customer_pic'], 'file'],
            
			
			
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
            
            'address' => 'Address',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    

    /**
     * @return \yii\db\ActiveQuery
     */
    

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomersBonuses()
    {
        return $this->hasMany(CustomersBonuses::className(), ['cust_id' => 'id']);
    }
	
	public function getCompanyCust()
    {
		$session = Yii::$app->session;
		$company_id = $session['company.company_id'];
        return $this->hasOne(CompanyCustomers::className(), ['cust_id' => 'id'])->where(['company_id'=>$company_id]);
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
    public function getOrders()
    {
        return $this->hasMany(Orders::className(), ['cust_id' => 'id']);
    }
}
