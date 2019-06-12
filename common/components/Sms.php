<?php
namespace common\components;
 
use Yii;
use yii\base\Component;
use yii\httpclient\Client;


class Sms extends Component {

   public function sendSms($number,$text)
    {
			
                //return $response = ['response'=>$otp,'body'=>$res] ;    
			$url = 'http://bhashsms.com/api/sendmsg.php?user=saltlake&pass=hohohoH0&sender=NIWCRM&phone='.$number.'&text='.$text.'&priority=ndnd&stype=normal';
			# POST fields go here.
				/*	$data = ['user' => 'saltlake', 
							'pass' => 'hohohoH0',
							'sender' =>'SALTLK',
							'phone' =>$number,
							'text' =>$text,
							'priority' => 'ndnd',
							'stype' =>  'normal',];
			    */
		
		    $ch = curl_init();

			curl_setopt($ch, CURLOPT_URL, $url ); //Url together with parameters
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Return data instead printing directly in Browser
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT , 7); //Timeout after 7 seconds
			curl_setopt($ch, CURLOPT_USERAGENT , "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)");
			curl_setopt($ch, CURLOPT_HEADER, 0);
			
			curl_exec($ch);
			if(!curl_error($ch))
				{
					return 1;
				}
			curl_close($ch);
			
	}
}
