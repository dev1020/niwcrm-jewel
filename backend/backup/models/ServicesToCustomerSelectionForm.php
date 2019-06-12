<?php

namespace backend\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class ServicesToCustomerSelectionForm extends Model
{
    public $category;
    public $subCategory;
    public $services;
	public $customer_id;
	public $cust_session;
   
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['services','customer_id'], 'required'],
            [['cust_session'], 'safe'],
            [['subCategory', 'category', 'services','customer_id'],'integer']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'category' => 'Category',
            'subCategory' => 'Sub Category',
            'services' => 'Services',
        ];
    }

}
