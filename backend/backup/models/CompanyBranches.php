<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "company_branches".
 *
 * @property int $id
 * @property string $branchaddress
 * @property string $branchname
 * @property string $branchcontact_no
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
            [['branchaddress', 'branchname', 'branchcontact_no'], 'required'],
            [['branchaddress', 'branchname'], 'string', 'max' => 255],
            [['branchcontact_no'], 'string', 'max' => 15],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'branchaddress' => 'Branchaddress',
            'branchname' => 'Branchname',
            'branchcontact_no' => 'Branchcontact No',
        ];
    }
}
