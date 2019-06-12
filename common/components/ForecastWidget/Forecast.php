<?php

namespace common\components\ForecastWidget;

use Yii;
use yii\base\Widget;
use common\components\ForecastWidget\ForecastAsset;

use yii\httpclient\Client;
use yii\helpers\Json;

class Forecast extends Widget
{
	public $options = [];
	
	public $pluginOptions = [];
	
    public function run() {
        parent::run();
        ForecastAsset::register($this->view);
		$test = $this->options;
		$response = $this->getForecast();
		if($response['status'] == 'ok'){
			return $this->render('forecast',['data'=>$response['data']]);
		}else{
			return $this->render('forecastfail');
		}
        
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