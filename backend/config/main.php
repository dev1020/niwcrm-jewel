<?php
//require(__DIR__ . '/../models/SettingsOptions.php');
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);
use \yii\web\Request;

$baseUrl = str_replace('backend/web', '', (new Request)->getBaseUrl());
//$baseUrl = (new Request)->getBaseUrl();


return [
    'id' => 'NIWCRM',
	'name'=>'NIWCRM',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log','assetsAutoCompress'],
    'modules' => [
			'rbac' =>  [
			'class' => 'johnitvn\rbacplus\Module'
			],
			'gridview' =>  [
				'class' => '\kartik\grid\Module'
			],
			'sliders' => [
				'class' => 'backend\modules\sliders\Slider',
			],
			'db-manager' => [
            'class' => 'bs\dbManager\Module',
            // path to directory for the dumps
            'path' => '@backend/backups',
            // list of registerd db-components
            'dbList' => ['db'],
            'as access' => [
                'class' => 'yii\filters\AccessControl',
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['Admin'],
                    ],
					],
				],
			],
			
		],
	'timeZone' => 'Asia/Kolkata',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
			'baseUrl' => $baseUrl,
        ],
        'assetsAutoCompress' =>
        [
            'class'                         => '\skeeks\yii2\assetsAuto\AssetsAutoCompressComponent',
            'enabled'                       => false,
            
            //'readFileTimeout'               => 3,           //Time in seconds for reading each asset file
            
            'jsCompress'                    => true,        //Enable minification js in html code
            'jsCompressFlaggedComments'     => false,        //Cut comments during processing js
            
            'cssCompress'                   => true,        //Enable minification css in html code
            
            'cssFileCompile'                => true,        //Turning association css files
            'cssFileRemouteCompile'         => true,       //Trying to get css files to which the specified path as the remote file, skchat him to her.
            'cssFileCompress'               => true,        //Enable compression and processing before being stored in the css file
            'cssFileBottom'                 => true,       //Moving down the page css files
            'cssFileBottomLoadOnJs'         => true,       //Transfer css file down the page and uploading them using js
            
            'jsFileCompile'                 => true,        //Turning association js files
            'jsFileRemouteCompile'          => false,       //Trying to get a js files to which the specified path as the remote file, skchat him to her.
            'jsFileCompress'                => true,        //Enable compression and processing js before saving a file
            'jsFileCompressFlaggedComments' => false,        //Cut comments during processing js
            
            'htmlCompress'                  => true,        //Enable compression html
            'noIncludeJsFilesOnPjax'        => true,        //Do not connect the js files when all pjax requests
            'htmlCompressOptions'           =>              //options for compressing output result
            [
                'extra' => true,        //use more compact algorithm
                'no-comments' => true   //cut all the html comments
            ],     
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => false,
            'identityCookie' => ['name' => '_identity-backend123', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'niwcrmjewel',
			'class' => 'yii\web\Session',
            'timeout' => 60000,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
		'authManager' => [
			'class' => 'yii\rbac\DbManager',
			'defaultRoles' => ['guest'],
		],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
			'baseUrl' => $baseUrl,
            'rules' => [
            ],
        ],
		'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            //'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => false,
        ],
        
    ],
	'as beforeRequest' => [
		'class' => 'yii\filters\AccessControl',
		'rules' => [
			[
				'allow' => true,
				'actions' => ['login','reset-password','request-password-reset'],
			],
			[
				'allow' => true,
				'roles' => ['@'],
			],
		],
		'denyCallback' => function () {
			return Yii::$app->response->redirect(['site/login']);
		},
	],
    'params' => $params,
];
