<?php
namespace common\components;
 
use Yii;
use yii\base\Component;
use yii\httpclient\Client;


class Places extends Component {

   public function getPlaceidbyaddress($address1,$address2,$city,$pin)
    {
		$query = $address1.' '.$address2.' '.$city.' '.$pin;
			$client = new Client([
			'transport' => 'yii\httpclient\CurlTransport'
		]);
			$response = $client->createRequest()					
					->setFormat(Client::FORMAT_URLENCODED)
					->setMethod('get')
					->setUrl('https://maps.googleapis.com/maps/api/place/textsearch/json')
					->setData(['query' => $query, 
								'key' => Yii::$app->params['GOOGLEAPI_KEY']
								
							  ])
					->send();
				if ($response->isOk) {
					$res = [$response->getData()] ;  
					print_r($res);
				}
		//return $response = ['response'=>$otp,'body'=>$res->getBody()] ;       
         
	}
}
