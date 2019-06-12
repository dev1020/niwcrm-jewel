<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "customers_log".
 *
 * @property int $id
 * @property int $cust_id
 * @property string $log_date
 * @property string $start_session_time
 * @property string $end_session_time
 * @property string $time_spent
 * @property string $status
 * @property string $session_no
 *
 * @property Customers $cust
 */
class CustomersLog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'customers_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cust_id', 'log_date', 'start_session_time', 'status', 'session_no'], 'required'],
            [['cust_id','address_id','assigned_executive_id'], 'integer'],
            [['log_date'], 'safe'],
            [['status'], 'string'],
            [['start_session_time', 'end_session_time'], 'string', 'max' => 20],
            [['time_spent'], 'string', 'max' => 10],
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
            'cust_id' => 'Cust ID',
            'log_date' => 'Log Date',
            'start_session_time' => 'Start Session Time',
            'end_session_time' => 'End Session Time',
            'time_spent' => 'Time Spent',
            'status' => 'Status',
            'session_no' => 'Session No',
            'address_id' => 'Address',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCust()
    {
        return $this->hasOne(Customers::className(), ['id' => 'cust_id']);
    }
	
	public function getExecutive()
    {
        return $this->hasOne(User::className(), ['id' => 'assigned_executive_id']);
    }
}
