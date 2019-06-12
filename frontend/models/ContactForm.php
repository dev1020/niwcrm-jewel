<?php

namespace frontend\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class ContactForm extends Model
{
    public $name;
    public $email;
    public $subject;
    public $body;
    public $verifyCode;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['name', 'email', 'subject', 'body'], 'required'],
            // email has to be a valid email address
            ['email', 'email'],
            // verifyCode needs to be entered correctly
            ['verifyCode', 'captcha'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'verifyCode' => 'Verification Code',
        ];
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     *
     * @param string $email the target email address
     * @return bool whether the email was sent
     */
    public function sendEmail($email)
    {
       $message = [
           'name'=>$this->name,
           'email'=>$this->email,
           'subject'=>$this->subject,
           'body'=>$this->body,
           ];
      
        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'contactmail-html'],
                ['message'=>$message]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name.'|Contact Form'])
            ->setTo(Yii::$app->params['supportEmail'])
            ->setSubject('Contact Form Submission | ' . Yii::$app->name)
            ->setTextBody($this->body)
            ->send();
    }
}
