<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => [
    	'log', 
    	'moduleManager', 
    	'config', 
    	'maintenance'
    ],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
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
        'errorHandler' => [
            'errorAction' => 'account/error',
        ],

		'authManager' => [
            'class' => 'yii\rbac\DbManager',
		],		
		'urlManager' => [
			'enablePrettyUrl' => true,
			'showScriptName' => false,
			'suffix' => '/', 
			'rules' => [
				'<_c:[\w\-]+>/<id:\d+>' => '<_c>/view',
				'<_c:[\w\-]+>' => '<_c>/index',
				'<_c:[\w\-]+>/<_a:[\w\-]+>/' => '<_c>/<_a>',
				'<_c:[\w\-]+>/<_a:[\w\-]+>/<id:\d+>' => '<_c>/<_a>',
				'<module:\w+>/<_c:[\w\-]+>/<_a:[\w\-]+>' => '<module>/<_c>/<_a>',
				// 'positioning/' => 'positioning/default/index',
				// 'positioning/<_a>' => 'positioning/default/<_a>',
				// 'wms/' => 'positioning/default/index',
				// 'wms/<_a>' => 'positioning/default/<_a>',
				// 'sportmasterwms/' => 'sportmasterwms/default/index',
				// 'sportmasterwms/<_a>' => 'sportmasterwms/default/<_a>/',
			],
		],
		'assetManager' => [
			'appendTimestamp' => true,
			'forceCopy' => true,
		],
		'config' => [
			'class' => 'app\components\TvzConfig',
		],
		'storagesData' => [
			'class' => 'frontend\components\StoragesDataAccessControl',
		],
		'moduleManager' => [
			'class' => 'frontend\components\ModuleManager',
		],
    ],
    'params' => $params,
	'modules' => [
        // 'racking' => [
            // 'class' => 'app\modules\racking\Racking',
		// ],
		// 'positioning' => [
            // 'class' => 'app\modules\positioning\Positioning',
		// ],
		// 'wms' => [
            // 'class' => 'frontend\modules\wms\Wms',
		// ],
		'maintenance' => [
            'class' => 'frontend\modules\maintenance\Maintenance',
		]
   ],
];
