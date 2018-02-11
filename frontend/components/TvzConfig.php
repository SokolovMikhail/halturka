<?php
namespace app\components;

use Yii;
use yii\base\Component;

class TvzConfig extends Component{
	public $params;
	
	public function init(){
		parent::init();
		$this->setMainPage();
		$this->setAccountData();
		$this->setUserData();
	}
	
	
	/**
	 * Главная страница приложения, может быть изменена в модуле
	 */
	public function setMainPage(){
		$this->params['main_page'] = '/account/';
	}
	
	
	/**
	 * Данные о текущем аккаунте
	 */
	public function setAccountData(){
		if(isset($_SERVER['HTTP_HOST'])){
			$bases = require(__DIR__ . '/../../common/config/bases.php');
			$sdn = explode('.', $_SERVER['HTTP_HOST']);
			if(array_key_exists($sdn[0], $bases)){
				$this->params['account'] = $bases[$sdn[0]];
			}
		}
	}
	
	
	/**
	 * Роли текущего пользователя
	 */
	public function setUserData(){
		if(isset(Yii::$app->user->identity)){
			$this->params['user'] = ['roles' => array_keys(Yii::$app->authManager->getAssignments(Yii::$app->user->identity->id))];
			// echo'<pre>';
			// var_dump(array_keys(Yii::$app->authManager->getAssignments(Yii::$app->user->identity->id)));
			// echo'</pre>';
		}
	}
}