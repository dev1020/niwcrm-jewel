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
	public $orderdue;
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
			 $this->pay['points'] = (int)($this->pay['points']!=null)? $this->pay['points'] : 0;
			 $this->pay['cash'] = (int)($this->pay['cash']!=null)? $this->pay['cash'] : 0;
			 $this->pay['wallet'] = (int)($this->pay['wallet']!=null)? $this->pay['wallet'] : 0;
			 $this->pay['card'] = (int)($this->pay['card']!=null)? $this->pay['card'] : 0;
			 $totalpay = $this->pay['card'] + $this->pay['cash'] + $this->pay['wallet'] + $this->pay['points'];
			 if($totalpay>$this->orderdue){
				 $this->addError('error', 'Excess Payment Please Check The Payment Form Before Submit'.'<br><b class="text-danger">Excess <i class="fa fa-inr"></i> '.($totalpay-$this->orderdue).'</b>');
				return false;
			 }
			
        return true;
     }
	}
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['orderid','orderdue'], 'required'],
			
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
