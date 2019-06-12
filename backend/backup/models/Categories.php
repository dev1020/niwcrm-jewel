<?php

namespace backend\models;
use yii\behaviors\SluggableBehavior;
use Yii;

/**
 * This is the model class for table "categories".
 *
 * @property integer $category_id
 * @property integer $category_root
 * @property string $category_name
 * @property string $category_status
 * @property string $category_pic
 * @property string $category_slug
 
 * 
 */
class Categories extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'categories';
    }
	
	public function behaviors()
	{
		return [
			[
				'class' => SluggableBehavior::className(),
				'attribute' => 'category_name',
				'slugAttribute' => 'category_slug',
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
            [['category_name'], 'required'],
            [['category_root','category_displayorder'], 'integer'],
            [['category_status'], 'string'],
			[['category_pic'],'file','skipOnEmpty' => true, 'extensions' => 'png, jpg'],
            [['category_name'], 'string', 'max' => 100],
            [['description'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'category_id' => 'Id',
            'category_root' => 'Parent Category',
            'category_name' => 'Name',
            'category_status' => 'Status',
            'category_pic' => 'Image',
            'category_slug' => 'Category Slug',
            'description' => 'Description',
            'category_displayorder'=>'Display Order'
        ];
    }

    
   public function getCategories()
    {
        return $this->hasOne(Categories::className(), ['category_id' => 'category_root']);
    }
    public function getServices()
    {
        return $this->hasMany(Services::className(), ['category_id' => 'category_id'])->orderBy(['name' => SORT_ASC]);
    }
}
