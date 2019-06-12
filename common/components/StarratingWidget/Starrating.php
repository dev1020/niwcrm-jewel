<?php

namespace common\components\StarratingWidget;

use Yii;
use yii\base\Widget;
use common\components\StarratingWidget\StarratingAsset;



class Starrating extends Widget
{
	
    public function run() {
        parent::run();
        ForecastAsset::register($this->view);
		
			return $this->render('forecastfail');
		
        
    }
	
	protected function getForecast()
    {
		$api = $this->pluginOptions['key'];
		$lat = $this->pluginOptions['lat'];
		$lon = $this->pluginOptions['lon'];
		$client = new Client([
			'transport' => 'yii\httpclient\CurlTransport'
		]);
			$response = $client->createRequest()					
					->setFormat(Client::FORMAT_URLENCODED)
					->setMethod('get')
					->setUrl('https://api.darksky.net/forecast/'.$api.'/'.$lat.','.$lon)
					->setData(['exclude' => 'minutely,hourly,alerts,flags', 
								'units' => 'si',
							  ])
					->setOptions([
						CURLOPT_SSL_VERIFYPEER => false, // connection timeout
					])
					->send();
				if ($response->isOk) {
					 $res['data'] = $response->getData();
					$res['status'] = 'ok';	
					
				}else{
					$res['status'] = 'error';	
				}
				//echo "<pre>";
				//print_r($res); exit;
			 return $res;
	}
	
}