<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "customers_important_dates".
 *
 * @property int $id
 * @property int $cust_id
 * @property string $imp_date
 * @property int $type
 * @property string $title
 *
 * @property ImportantDateTypes $type0
 */
class CustomersImportantDates extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'customers_important_dates';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cust_id', 'imp_date', 'type', 'title'], 'required'],
            [['cust_id', 'type'], 'integer'],
            [['imp_date'], 'safe'],
            [['title'], 'string', 'max' => 30],
            [['type'], 'exist', 'skipOnError' => true, 'targetClass' => ImportantDateTypes::className(), 'targetAttribute' => ['type' => 'id']],
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
            'imp_date' => 'Imp Date',
            'type' => 'Type',
            'title' => 'Title',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getType0()
    {
        return $this->hasOne(ImportantDateTypes::className(), ['id' => 'type']);
    }
}
