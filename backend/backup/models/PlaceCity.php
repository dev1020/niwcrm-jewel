<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "place_city".
 *
 * @property int $place_city_id
 * @property string $place_city_name
 *
 * @property PlaceLocations[] $placeLocations
 */
class PlaceCity extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'place_city';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['place_city_name'], 'required'],
            [['place_city_name'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'place_city_id' => 'Place City ID',
            'place_city_name' => 'Place City Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlaceLocations()
    {
        return $this->hasMany(PlaceLocations::className(), ['loc_city_id' => 'place_city_id']);
    }
}
