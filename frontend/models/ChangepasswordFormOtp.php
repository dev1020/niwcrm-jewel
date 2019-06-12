<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\User;
use app\models\Login;
use common\components\Sms;
/**
 * Signup form
 */
class ChangepasswordFormOtp extends Model
{
    //public $password;
	public $contact_number;
	public $otp;
    public $newpassword;
    public $passwordrepeat;
    /**
     * @var \common\models\User
     */
    private $_user;
   
  
	
   public function rules()
    {
        return [
            ['contact_number','required'],
			[['otp','newpassword','passwordrepeat'], 'required'],
            ['contact_number', 'string', 'min' => 10,'max'=>10],
			['newpassword', 'string', 'min' => 6],
            ['passwordrepeat','compare','compareAttribute'=>'newpassword'],
			['otp','validateOtp'],
        ];
    }
  
	public function validateOtp($attribute, $params)
    {
       
		if ($this->otp != Yii::$app->session->get('otp')){
			$this->addError($attribute, 'Incorrect OTP.');
		}
        
    }
   
    public function resetPassword()
    {
		
		$session = Yii::$app->session;
		
        $user = User::findByContact($session->get('contact_number'));
        $user->setPassword($this->newpassword);

        return $user->save(false);
    }
}
