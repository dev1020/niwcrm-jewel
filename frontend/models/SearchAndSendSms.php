<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use backend\models\Categories;

/**
 * ContactForm is the model behind the contact form.
 */
class SearchAndSendSms extends Model
{
    public $mobilenumber;
    public $name;
    public $email_id;
    public $category;
   
   


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['mobilenumber','name','category'], 'required'],
            [['email_id'], 'email'],
            
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'mobilenumber' => 'Mobile Number',
            'name' => 'Name',
            'email_id' => 'Email Id',
            'category' => 'category',
        ];
    }

    
}
