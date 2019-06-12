<?php
namespace backend\models;

use Yii;
use yii\base\Model;
use common\models\User;
use yii\web\UploadedFile;
/**
 * Signup form
 */
class SignupForm extends Model
{
   public $first_name;
    public $last_name;
    public $username;
    public $contact_number;
    public $email;
    public $password;
    public $profilepic;
	public $address;
	public $address2;
	public $usertype;
	public $pin;
	public $dob;
	public $company_id;
	public $branch_id;


   // public $status;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['first_name', 'required','message'=>'First Name is required.'],
            ['last_name', 'required'],
            ['opt_in', 'required','on' => 'login','message'=>'Please accept terms & condition to submit'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],
            [['contact_number'],'required'],
            [['profilepic'],'file','skipOnEmpty' => true, 'extensions' => 'png, jpg'],
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            [['email','profilepic'], 'string', 'max' => 255],
            [['address','address2','dob'],'string','max'=>255],
            ['usertype','required'],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address is already in use.'],
            ['contact_number', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This phone number is already registered.'],

            [['company_id','branch_id'], 'integer'],
            ['password', 'required'],
            ['password', 'string', 'min' => 6],
			
        ];
    }
	public function scenarios()
    {
		$scenarios = parent::scenarios();
        $scenarios['login'] = ['contact_number','usertype','password','otp','opt_in'];//Scenario Values Only Accepted
        $scenarios['beforeotp'] = ['contact_number'];//Scenario Values Only Accepted
        $scenarios['backendsignupotp'] = ['contact_number','usertype','password','otp','first_name','last_name'];//Scenario Values Only Accepted
		
        return $scenarios;
    }
    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup($scenario ='')
    {
        if (!$this->validate()) {
            return null;
            
        }
        
        $user = new User();
		if($scenario != ''){
			$user->scenario = $scenario;
			$user->first_name = $this->first_name;
			$user->last_name = $this->last_name;
			$user->username = $this->username;
			$user->contact_number = $this->contact_number;
			$user->email = $this->email;
			$user->address = $this->address;
			$user->address2 = $this->address2;
			$user->pin = $this->pin;
			$user->dob = $this->dob;
			$user->usertype = $this->usertype;
			$user->company_id = $this->company_id;
			$user->branch_id = $this->branch_id;
			$user->setPassword($this->password);
			$user->generateAuthKey();
		}else{
			$user->first_name = $this->first_name;
			$user->last_name = $this->last_name;
			$user->username = $this->username;
			$user->contact_number = $this->contact_number;
			$user->email = $this->email;
			$user->address = $this->address;
			$user->address2 = $this->address2;
			$user->pin = $this->pin;
			$user->dob = $this->dob;
			$user->usertype = $this->usertype;
			$user->company_id = $this->company_id;
			$user->branch_id = $this->branch_id;
			$user->setPassword($this->password);
			$user->generateAuthKey();
		}
		//$user->scenario = 'backend';
       
		//die();
		if(UploadedFile::getInstance($this,'profilepic')){
			$user->profilepic = UploadedFile::getInstance($this,'profilepic');
			$user->profilepic->saveAs(Yii::getAlias('@frontend').'/web/uploads/profilepic/'.$user->username.'.'.$user->profilepic->extension);
			$user->profilepic = $user->username.'.'.$user->profilepic->extension;
		}
			
        
        return $user->save() ? $user : null;
    }
	
}
