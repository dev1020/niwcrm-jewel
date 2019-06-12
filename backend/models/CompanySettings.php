<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "company_settings".
 *
 * @property int $id
 * @property int $company_id
 * @property string $brand_name
 * @property string $loyalty_bonus_percentage
 * @property string $referral_bonus_percentage
 * @property string $site_logo
 * @property string $multi_store
 * @property string $package
 *
 * @property Companies $company
 */
class CompanySettings extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'company_settings';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['company_id'], 'required'],
            [['company_id','bonus_valid_days'], 'integer'],
			[['sms_senderid'],'string','max'=>8],
			[['sms_text_after_payment','welcome_sms_text','referral_text_after_payment'],'safe'],
            [['multi_store','sms_after_payment','welcome_sms','sms_after_order','enable_multiple_payment_type','package','bonus_redemption'], 'string'],
            [['brand_name', 'site_logo'], 'string', 'max' => 255],
            [['loyalty_bonus_percentage', 'referral_bonus_percentage'], 'double'],
            [['company_id'], 'exist', 'skipOnError' => true, 'targetClass' => Companies::className(), 'targetAttribute' => ['company_id' => 'id']],
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
            'brand_name' => 'Brand Name',
            'loyalty_bonus_percentage' => 'Loyalty Bonus Percentage',
            'referral_bonus_percentage' => 'Referral Bonus Percentage',
            'site_logo' => 'Site Logo',
            'multi_store' => 'Multi Store',
            'bonus_valid_days' => 'Valid Days',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompany()
    {
        return $this->hasOne(Companies::className(), ['id' => 'company_id']);
    }
}
