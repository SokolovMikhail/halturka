<?php
/**
 * Управление модулями приложения
 */
namespace frontend\components;

use Yii;
use yii\base\Component;
use frontend\models\entities\Module;

class ModuleManager extends Component{
	public $params;
	
	public function init(){
		parent::init();
		$this->enableModules();	
				
		// Работа с модулями из консоли
		// if (Yii::$app instanceof \yii\console\Application) {
			// $this->controllerNamespace = 'app\modules\forum\commands';
		// }
		// Your commands will then be available from the command line using the following route:
		// yii <module_id>/<command>/<sub_command>
	}
	
	
	/**
	 * Активация модулей из БД
	 */
	private function enableModules(){ 
		if(Yii::$app->db->schema->getTableSchema(Module::tableName()) != null){
			$modules = Module::find()->where(['active'=>1])->orderBy('depth DESC')->all();	
			foreach ($modules as $module){
			
				if(isset(Yii::$app->user->identity)){
					if($module->permit && !in_array($module->permit, Yii::$app->config->params['user']['roles'])){
						continue;
					}
				}
				Yii::$app->setModule($module->id, $module->source);
				
				if(isset(Yii::$app->user->identity)){
					$moduleObj = Yii::$app->getModule($module->id);;
					if(method_exists($moduleObj, 'mainPage') && array_intersect(Yii::$app->config->params['user']['roles'], $moduleObj->getPermission())){
						Yii::$app->config->params['main_page'] = $moduleObj->mainPage();
					}
				}
			}
		}
	}
	
	
	/**
	 * Вызов action из модулей
	 */
	public function runActions($actionsList){
		$result = [];
		foreach($actionsList as $moduleId => $actions){
			if(isset(Yii::$app->modules[$moduleId])){
				$moduleObj = Yii::$app->getModule($moduleId);
				foreach($actions as $action=>$actionData){
					$result[] = $moduleObj->runAction($action, $actionData);
				}
			}
		}
		return $result;
	}
	
}