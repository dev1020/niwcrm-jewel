<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "promosms_templates".
 *
 * @property int $id
 * @property string $sms_title
 * @property string $sms_body
 * @property int $smscount
 */
class PromosmsTemplates extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'promosms_templates';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sms_title', 'sms_body', 'smscount'], 'required'],
            [['sms_body'], 'string'],
            [['smscount'], 'integer'],
            [['sms_title'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sms_title' => 'Sms Title',
            'sms_body' => 'Sms Body',
            'smscount' => 'Smscount',
        ];
    }
}
