<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "place".
 *
 * @property integer $id
 * @property integer $place_type
 * @property string $google_place_id
 * @property integer $created_by
 * @property integer $created_at
 * @property string $address1
 * @property string $address2
 * @property integer $pin
 * @property integer $location
 * @property integer $place_bpost_id
 */
class Place extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'place';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[ 'created_by', 'created_at', 'address1','location'], 'required'],
			[['address1','address2','location','pin','place_bpost_id'], 'required','on' => 'multilocation'],
            [['place_type', 'created_by', 'pin','location','place_bpost_id'], 'integer'],
            [['pin'], 'safe'],
            [['google_place_id', 'address1','created_at', 'address2'], 'string', 'max' => 255],
        ];
    }

	public function scenarios()
    {
		$scenarios = parent::scenarios();
        $scenarios['userbyotp'] = ['address1','address2','location','pin'];//Scenario Values Only Accepted		
        $scenarios['multilocation'] = ['address1','address2','location','pin','place_bpost_id'];//Scenario Values Only Accepted		
        return $scenarios;
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'place_type' => 'Place Type',
            'google_place_id' => 'Google Place ID',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
            'address1' => 'Address1',
            'address2' => 'Address2',
            'pin' => 'Pin',
            'location' => 'Location',
            'place_bpost_id' => 'Bposts',
        ];
    }
	
	public function getPlaceLocation()
    {
        return $this->hasOne(PlaceLocations::className(), ['loc_id' => 'location']);
    }
	
	public function getPlaceBposts()
    {
        return $this->hasOne(Bposts::className(), ['bpost_id' => 'place_bpost_id']);
    }
	
}
