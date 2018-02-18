<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
if(isset($_SERVER['HTTP_HOST'])){
	$bases = require(__DIR__ . '/bases.php');
	$sdn = explode('.', $_SERVER['HTTP_HOST']);
	if(array_key_exists($sdn[0], $bases)){
		$dbName = $bases[$sdn[0]]['db'];
	}
}


$dbName = 'juridical';


return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',			
            
			'dsn' => 'mysql:host=localhost;dbname='.$dbName,
            'username' => 'root',
            'password' => '',

            // 'dsn' => 'mysql:host=52.169.214.133;dbname='.$dbName,
            // 'username' => 'extend-test-user',
            // 'password' => 'a1!a0cQ3w7Q$3w7#E0v5',
			
            'charset' => 'utf8',
        ],		
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            //'viewPath' => '@common/mail',
            'useFileTransport' => false,
			'transport' => [
            'class' => 'Swift_SmtpTransport',
            'host' => 'smtp.yandex.ru',
            'username' => 'test-server-opros@mail.ru',
            'password' => 'testserver123',
            'port' => '465',
            'encryption' => 'SSL',
			],
        ],
		'authManager' => [
            'class' => 'yii\rbac\DbManager',
		],		
		
    ],
];