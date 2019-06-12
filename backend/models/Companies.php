<?php

namespace backend\models;

use Yii;
use common\models\User;
use yii\behaviors\SluggableBehavior;
/**
 * This is the model class for table "companies".
 *
 * @property int $id
 * @property string $company_name
 * @property string $company_address
 * @property string $company_contact
 * @property int $created_by
 * @property string $created_at
 *
 * @property CompanySettings[] $companySettings
 */
class Companies extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'companies';
    }
	
	public function behaviors()
	{
		return [
			[
				'class' => SluggableBehavior::className(),
				'attribute' => 'company_name',
				'slugAttribute' => 'company_slug',
				'ensureUnique' => true,
			],
		];
	}
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['company_name', 'company_address', 'company_contact', 'created_by', 'created_at'], 'required'],
            [['company_address'], 'string'],
            [['created_by','sms_quota'], 'integer'],
            [['created_at','activated_upto'], 'safe'],
            [['company_name'], 'string', 'max' => 255],
            [['company_contact'], 'string', 'max' => 15],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'company_name' => 'Company Name',
            'company_address' => 'Company Address',
            'company_contact' => 'Company Contact',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompanySettings()
    {
        return $this->hasMany(CompanySettings::className(), ['company_id' => 'id']);
    }
	
	public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }
}
