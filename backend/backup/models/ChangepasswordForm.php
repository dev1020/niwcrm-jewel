<?php
namespace backend\models;

use Yii;
use yii\base\Model;
use common\models\User;
use app\models\Login;
/**
 * Signup form
 */
class ChangepasswordForm extends Model
{
    
   
    public $oldpassword;
    public $newpassword;
    public $passwordrepeat;
	
	private $_user;
   
   // public $status;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['oldpassword','newpassword','passwordrepeat'], 'required'],
            ['newpassword', 'string', 'min' => 6],
			['oldpassword','validatePassword'],
            ['passwordrepeat','compare','compareAttribute'=>'newpassword'],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function changepass()
    {
        if (!$this->validate()) {
            return null;
        }
        
        $user = new User();
        $user->setPassword($this->newpassword);
		
			
			//print_r($user); exit;
        
        return $user;
    }
	
	public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = User::findIdentity(Yii::$app->user->identity->id);
			//print_r($user);exit;
            if (!$user->validatePassword($this->oldpassword)){
                $this->addError($attribute, 'Incorrect old password.');
            }
        }
    }
	
	
	
	public function attributeLabels(){
            return [
                'oldpassword'=>'Old Password',
                'newpassword'=>'New Password',
                'passwordrepeat'=>'Retype Password',
            ];
        }
}
