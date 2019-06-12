<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "company_customers".
 *
 * @property int $id
 * @property int $company_id
 * @property int $cust_id
 * @property int $introducer_id 
 * @property string $created_date
 * @property string $customer_number
 *
 * @property Companies $company
 * @property Customers $cust
 */
class CompanyCustomers extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
	 public $mode;
    public static function tableName()
    {
        return 'company_customers';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['company_id', 'cust_id','introducer_id'], 'integer'],
            [['cust_id', 'customer_number','company_id'], 'required'],
            [['created_date'], 'safe'],
            [['customer_number'], 'string', 'max' => 50],
            [['company_id'], 'exist', 'skipOnError' => true, 'targetClass' => Companies::className(), 'targetAttribute' => ['company_id' => 'id']],
            [['cust_id'], 'exist', 'skipOnError' => true, 'targetClass' => Customers::className(), 'targetAttribute' => ['cust_id' => 'id']],
            [['introducer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Customers::className(), 'targetAttribute' => ['introducer_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'company_id' => 'Company ID',
            'cust_id' => 'Cust ID',
            'created_date' => 'Created Date',
            'customer_number' => 'Customer Number',
            'introducer_id ' => 'Introducer Id',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompany()
    {
        return $this->hasOne(Companies::className(), ['id' => 'company_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCust()
    {
        return $this->hasOne(Customers::className(), ['id' => 'cust_id']);
    }
	
	public function getIntroducer()
    {
        return $this->hasOne(Customers::className(), ['id' => 'introducer_id']);
    }
}
