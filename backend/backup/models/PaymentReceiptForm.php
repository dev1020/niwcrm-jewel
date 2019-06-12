<?php

namespace backend\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class PaymentReceiptForm extends Model
{
    public $orderid;
    public $pay = ['cash','card','wallet','points'];
    
	
   
    /**
     * {@inheritdoc}
     */
	 
	public function beforeValidate()
	{
		if (parent::beforeValidate())
		{
			
			if ($this->pay['points']==null && $this->pay['cash']==null && $this->pay['wallet']==null && $this->pay['card']==null)  
			{
				$this->addError('error', 'Please Pay With Any One Method <b class="text-danger">Cash</b> / <b class="text-danger">Card</b> / <b class="text-danger">Wallet</b> / <b class="text-danger">Reward Points</b>');
				return false;
			}
        return true;
     }
	}
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['orderid',], 'required'],
			
            ['pay', 'each', 'rule' => ['integer']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            //'category' => 'Category',
            //'subCategory' => 'Sub Category',
            //'services' => 'Services',
        ];
    }

}
