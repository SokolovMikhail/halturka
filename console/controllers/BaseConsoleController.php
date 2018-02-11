<?php
/**
 * Родительский контроллер для консольных команд
 */
namespace console\controllers;

use yii\console\Controller;
use common\models\LocalAccounts;

class BaseConsoleController extends Controller {
	
	/**
	 * Обертка для запуска генерации по всем аккаунтам
	 */
	public function actionRunMethod($method, $permit=false){
		echo "This is $method \n";
		LocalAccounts::wallkAndRun($this, $method, $permit);
	}
	
}