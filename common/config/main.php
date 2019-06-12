<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
	 'modules' => [
			'sliders' => [
				'class' => 'common\modules\sliders\Slider',
			],
			'rbac' =>  [
			'class' => 'johnitvn\rbacplus\Module'
			],
			'redactor' => [
				'class' => 'yii\redactor\RedactorModule',
				'uploadDir' => '@frontend/web/uploads',
				'uploadUrl' => '@frontendimage',
				'imageAllowExtensions'=>['jpg','png','gif']
			],
			
		],
    'components' => [
       /* 'cache' => [
            'class' => 'yii\caching\DbCache',
			'cacheTable' => 'crmspa_cache',
        ],*/
		'urlManager' => [ 
			'enablePrettyUrl' => false,
			'showScriptName' => false,
				  
		],
		'authManager' => [
        'class' => 'yii\rbac\DbManager', // or use 'yii\rbac\DbManager'
		],
		'elasticsearch' => [
            'class' => 'yii\elasticsearch\Connection',
            'nodes' => [
                ['http_address' => '127.0.0.1:9200'],
                // configure more hosts if you have a cluster
            ],
        ],
		'formatter' => [
            'currencyCode' => 'INR',
            'decimalSeparator' => '.',
            'locale' => 'id',
            'thousandSeparator' => ',',
        ],
		
    ]
];
