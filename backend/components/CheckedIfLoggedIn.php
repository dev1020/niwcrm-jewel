<?php
namespace backend\components;

use Yii;
use yii\base\Behavior;

class CheckedIfLoggedIn extends Behavior {

   
	public function events()
    {
        return [
            \yii\web\Application::EVENT_BEFORE_REQUEST => 'checkedIfLoggedIn',
        ];
    }
	
	public function checkedIfLoggedIn()
	{
		if(Yii::$app->user->isGuest)
		{
			return Yii::$app->runAction('site/login');
		}
	}
	
}
