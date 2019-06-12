<?php

namespace backend\models;

use Yii;
use yii\behaviors\SluggableBehavior;
/**
 * This is the model class for table "settings_options".
 *
 * @property int $id
 * @property string $settings_attribute_name
 * @property string $settings_attribute_label
 * @property string $settings_attribute_value
 * @property string $settings_attribute_type
 */
class SettingsOptions extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'settings_options';
    }
	
	public function behaviors()
	{
		return [
			[
				'class' => SluggableBehavior::className(),
				'attribute' => 'settings_attribute_label',
				'slugAttribute' => 'settings_attribute_name',
				'ensureUnique' => true,
			],
		];
	}

   

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['settings_attribute_label'], 'required'],
            [['settings_attribute_name', 'settings_attribute_value','settings_attribute_label','settings_attribute_type'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'settings_attribute_label' => 'Settings Attribute Label',
            'settings_attribute_name' => 'Settings Attribute Name',
            'settings_attribute_value' => 'Settings Attribute Value',
            'settings_attribute_type' => 'Settings Attribute Type',
        ];
    }
}
