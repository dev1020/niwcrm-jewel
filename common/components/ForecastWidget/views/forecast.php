<?php
use common\components\ForecastWidget\ForecastAsset;?>

<?php $bundle = ForecastAsset::register($this);?>

<?php $day = ['Sun'=>'0','Mon'=>'1','Tue'=>'2','Wed'=>'3','Thu'=>'4','Fri'=>'5','Sat'=>'6'];
		

?>
<!--<img src="<?=$bundle->baseUrl?>/images/weather.jpg">-->
<div class="forecast-wrapper" style="background-color: #514949;background-image:url(<?=$bundle->baseUrl?>/images/<?= $data['currently']['icon']?>.jpg); ">
	<div class="day">
	
	<h5 class="upper" ><?= Yii::$app->formatter->asDatetime($data['currently']['time'],"php:l")?></h5>
	<h6><?= Yii::$app->formatter->asDatetime($data['currently']['time'],"php:F j")?></h6>
	<span>Now <?= $data['currently']['temperature'] ?> &#8451 </span>
	</div>

	<div class="icon" ><img src="<?=$bundle->baseUrl?>/images/<?= $data['currently']['icon']  ?>.png"></div>
	<div class="temp" > 
		<sup><?= $data['daily']['data'][$day[Yii::$app->formatter->asDatetime($data['currently']['time'],"php:D")]]['temperatureMin']?> &#176 </sup> / 
		<sub><?= $data['daily']['data'][$day[Yii::$app->formatter->asDatetime($data['currently']['time'],"php:D")]]['temperatureMax']?> &#8451 </sub> 
		<br> 
		<span>realfeel <?= $data['currently']['apparentTemperature'] ?> &#8451 </span>
	</div>
	<div class="summary">
		<?= $data['daily']['data'][$day[Yii::$app->formatter->asDatetime($data['currently']['time'],"php:D")]]['summary']?>
	</div>

</div>