<?php

namespace backend\models;


use Yii;
use yii\behaviors\SluggableBehavior;

/**
 * This is the model class for table "services".
 *
 * @property int $id
 * @property string $name
 * @property string $price
 * @property string $variable_price
 * @property string $price_max
 * @property string $servicefor
 * @property string $services_icon
 * @property int $category_id
 *
 * @property Categories $category
 */
class Services extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'services';
    }
	public function behaviors()
	{
		return [
			[
				'class' => SluggableBehavior::className(),
				'attribute' => 'name',
				'slugAttribute' => 'services_slug',
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
            [['name', 'price', 'servicefor','fixedprice'], 'required'],
			[['price_max',], 'required', 'when' => function ($model){
					return $model->fixedprice == 'no';
				}, 'whenClient' => "function (attribute, value){
					return $('#services-fixedprice').val() == 'no';
			}"],
			 ['price_max', 'compare','compareAttribute'=>'price','operator'=>'>',
    'message'=>'price_max should be bigger than price', 'when' => function ($model){
					return $model->fixedprice == 'no';
				}, 'whenClient' => "function (attribute, value){
					return $('#services-fixedprice').val() == 'no';
			}"],
            [['servicefor','name_hindi','name_local','services_icon','services_slug'], 'string'],
			[['services_icon'],'file'],
            [['category_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Categories::className(), 'targetAttribute' => ['category_id' => 'category_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'price' => 'Price',
            'servicefor' => 'Servicefor',
            'category_id' => 'Category Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Categories::className(), ['category_id' => 'category_id']);
    }
}
