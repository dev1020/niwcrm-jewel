<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "receipt_issuer".
 *
 * @property integer $receipt_id
 * @property integer $receipt_partners
 * @property string $receipt_number
 * @property integer $receipt_amount
 * @property string $receipt_issue_date
 * @property string $receipt_date
 *
 * @property User $receiptUser
 */
class ReceiptIssuer extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'receipt_issuer';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[ 'receipt_number', 'receipt_issue_date'], 'required'],
            [['receipt_id', 'receipt_amount'], 'integer'],
            [['receipt_issue_date', 'receipt_date'], 'safe'],
            [['receipt_number'], 'string', 'max' => 255],
			['receipt_number', 'unique', 'targetClass' => '\backend\models\ReceiptIssuer', 'message' => 'Receipt Number already has been issued.'],
            [['receipt_partners'], 'exist', 'skipOnError' => true, 'targetClass' => Partners::className(), 'targetAttribute' => ['receipt_partners' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'receipt_id' => 'ID',
            'receipt_partners' => 'Partner',
            'receipt_number' => 'Receipt Number',
            'receipt_amount' => 'Amount',
            'receipt_issue_date' => 'Issue Date',
            'receipt_date' => 'Date',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReceiptPartners()
    {
        return $this->hasOne(Partners::className(), ['id' => 'receipt_partners']);
    }
}
