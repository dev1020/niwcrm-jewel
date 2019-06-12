<?php

namespace backend\models;

use common\models\User;

use Yii;

/**
 * This is the model class for table "company_branches".
 *
 * @property int $id
 * @property int $company_id
 * @property string $branch_name
 * @property string $branch_location
 * @property int $created_by
 * @property string $created_at
 *
 * @property CompanyBranches $company
 * @property CompanyBranches[] $companyBranches
 */
class CompanyBranches extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'company_branches';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['company_id', 'branch_name', 'branch_location',], 'required'],
            [['company_id', 'created_by'], 'integer'],
            [['branch_location'], 'string'],
            [['created_at'], 'safe'],
            [['branch_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'company_id' => 'Company',
            'branch_name' => 'Branch Name',
            'branch_location' => 'Branch Location',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompany()
    {
        return $this->hasOne(Companies::className(), ['id' => 'company_id']);
    }
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }
    

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompanyBranches()
    {
        return $this->hasMany(CompanyBranches::className(), ['company_id' => 'id']);
    }
}
