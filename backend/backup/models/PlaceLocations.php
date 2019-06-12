<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "place_locations".
 *
 * @property int $loc_id
 * @property string $loc_name
 * @property string $loc_status
 * @property int $loc_city_id
 *
 * @property PlaceCity $locCity
 */
class PlaceLocations extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'place_locations';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['loc_name', 'loc_city_id'], 'required'],
            [['loc_status'], 'string'],
            [['loc_city_id'], 'integer'],
            [['loc_name'], 'string', 'max' => 50],
            [['loc_city_id'], 'exist', 'skipOnError' => true, 'targetClass' => PlaceCity::className(), 'targetAttribute' => ['loc_city_id' => 'place_city_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'loc_id' => 'ID',
            'loc_name' => 'Location',
            'loc_status' => 'Status',
            'loc_city_id' => 'City',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLocCity()
    {
        return $this->hasOne(PlaceCity::className(), ['place_city_id' => 'loc_city_id']);
    }
	
	
}
