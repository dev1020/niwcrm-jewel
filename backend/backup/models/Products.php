<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "products".
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property int $category
 * @property string $sku
 * @property string $cost_price
 * @property string $price
 * @property string $description
 * @property string $featured_image
 * @property string $productfor
 */
class Products extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'products';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'slug', 'sku', 'cost_price', 'price', 'description', 'featured_image', 'productfor'], 'required'],
            [['category'], 'integer'],
            [['cost_price', 'price'], 'number'],
            [['description', 'productfor'], 'string'],
            [['name', 'slug', 'featured_image'], 'string', 'max' => 255],
            [['sku'], 'string', 'max' => 10],
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
            'slug' => 'Slug',
            'category' => 'Category',
            'sku' => 'Sku',
            'cost_price' => 'Cost Price',
            'price' => 'Price',
            'description' => 'Description',
            'featured_image' => 'Featured Image',
            'productfor' => 'Productfor',
        ];
    }
}
