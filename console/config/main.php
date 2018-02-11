<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => [
        'log', 'gii', 'mailingreports', 'reports', 'company'
    ],
    'controllerNamespace' => 'console\controllers',
    'modules' => [
        'gii' => 'yii\gii\Module',
        'mailingreports' => [
            'class' => 'frontend\modules\mailingreports\MailingReports',
        ],
        'reports' => [
            'class' => 'frontend\modules\reports\Reports',
        ],
        'company' => [
            'class' => 'frontend\modules\company\Company',
        ],
        'battery' => [
            'class' => 'frontend\modules\battery\Battery',
        ],
    ],
    'components' => [
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'user' => [
			'class' => 'yii\web\User',
            'identityClass' => 'common\models\User',
        ],
		'storagesData' => [
			'class' => 'frontend\components\StoragesDataAccessControl',
		],		
    ],
    'params' => $params,
];