<?php
namespace frontend\components;

use Yii;
use yii\base\Object;
use yii\helpers\ArrayHelper;
use frontend\models\UserStorage;
use frontend\models\Storages;
use frontend\models\AppOptions;

class StoragesDataAccessControl extends Object{
	
	public $mainStorage;
	public $userStorages;
	
	private $allStorages;
	
	private $needleTypes;
	private $onlyActive;
	private $onlyAvailable;
	
	
	public function init(){
		parent::init();
		$this->allStorages = ArrayHelper::index(Storages::find()->asArray()->all(), 'id');
		$this->userStorages = UserStorage::currentUserStorages();
		$this->mainStorage = AppOptions::getOption('reporting_storage_'.Yii::$app->user->identity->id);
		$this->mainStorage = $this->mainStorage ? $this->mainStorage : Yii::$app->user->identity->storage_id;
	}
	
	
	/**
	 * Возвращает копию компонента для установки условий отбора и получения данных об отделах
	 */
	public function find(){
		return clone $this;
	}
	
	
	/**
	 * Данные об отделах в виде массива
	 */
	public function asArray(){
		$result = [];
		
		foreach($this->allStorages as $storage){
			$storage = $this->checkCondition($storage);
			if($storage){
				$result[$storage['id']] = $storage;
			}
		}
		
		if($result){
			ArrayHelper::multisort($result, 'sorting', SORT_ASC);
		}		
		return $result;
	}
	
	
	/**
	 * Данные об отделах в виде дерева
	 */
	public function asTree(){
		$result = [];
		$reportingStorages = $this->getReportingStorages();
		
		if($reportingStorages){
			foreach($reportingStorages as $id=>$s){
				$parent = isset($reportingStorages[$s['parent_id']]) ? $s['parent_id'] : 0;
				$result[$parent]['childs'][$id] = $s;
			}
			
			foreach($result as $parentId=>$item){
				ArrayHelper::multisort($item['childs'], 'sorting', SORT_ASC);
				$result[$parentId]['childs'] = ArrayHelper::index($item['childs'], 'id');
			}
		}		
		return $result;
	}


	/**
	 * Ветка ID доступных родительских и дочерних отделов, относительно текущего (включая текущий)
	 */
	public function asFullAvailableBranch($storage=false){
		$storage = $storage ? $storage : $this->mainStorage;
		
		$reportingStorages = $this->getReportingStorages();
		$availableStoragesByParents = $this->asTree();
		$result = [];
		
		// Все дочерние
		$currentParents = [$storage];
		while($currentParents){
			$parent = array_shift($currentParents);
			$result[] = $parent;
			if(isset($availableStoragesByParents[$parent])){
				foreach($availableStoragesByParents[$parent]['childs'] as $child){
					if($child['available']){
						$currentParents[] = $child['id'];
					}
				}
			}
		}
		
		// Все родительские
		$currentParents = [$reportingStorages[$storage]['parent_id']];
		while($currentParents){
			$parent = array_shift($currentParents);
			if($parent && isset($reportingStorages[$parent]) && $reportingStorages[$parent]['available']){
				$result[] = $parent;
				$currentParents[] = $reportingStorages[$parent]['parent_id'];
			}
		}
		
		return $result;
	}
	
	
	/**
	 * Проверка соответствия отдела условиям запроса
	 */
	private function checkCondition($storage){
		// Только активные
		if($this->onlyActive !== null && $this->onlyActive && !$storage['active']){
			$storage = false;
		}
		
		// Только доступные пользователю
		if($this->onlyAvailable !== null && $this->onlyAvailable && !in_array($storage['id'], $this->userStorages)){
			$storage = false;
		}
		
		// Только определенного типа
		if($this->needleTypes !== null && !in_array($storage['type'], $this->needleTypes)){
			$storage = false;
		}
		
		return $storage;
	}
	
	
	/**
	 * Отделы  меткой доступности
	 */
	public function getReportingStorages(){
	
		$reportingStorages = [];
		
		foreach($this->allStorages as $storage){
			$storage = $this->checkCondition($storage);
			if($storage){
				$storage['available'] = in_array($storage['id'], $this->userStorages) ? 1 : 0;
				$reportingStorages[$storage['id']] = $storage;
			}
			
		}
		
		return $reportingStorages;
	}
	
	
	/**
	 * Часовой пояс отдела
	 */
	public function getTimeZone($storage){
		if(isset($this->allStorages[$storage])){
			return $this->allStorages[$storage]['time_zone']+0;
		}
		else{
			return 0;
		}
	}
	
	
	/**
	 * Смена отчетного отдела
	 */
	public function changeMainStorage($newStorage, $redirectParams=[]){
		if($newStorage){
			$this->checkAccessPermit($newStorage);
			if($newStorage != $this->mainStorage){
				$url = ['/'.Yii::$app->controller->route.'/'] + $redirectParams;
				AppOptions::saveOption('reporting_storage_'.Yii::$app->user->identity->id, $newStorage);
				Yii::$app->getResponse()->redirect($url)->send();
			}
		}
	}
	
	
	/**
	 * Проверка прав доступа пользователя к отделу
	 */
	public function checkAccessPermit($storage, $redirect='/'){
		if(in_array($storage, $this->userStorages)){
			return true;
		}
		else{
			Yii::$app->getResponse()->redirect($redirect)->send();
		}
	}
	
	
	// /**
	//  * Ветка ID доступных отделов, относительно текущего
	//  */
	// public function getAvailableStoragesBranch($storage=false){
	// 	$storage = $storage ? $storage : $this->mainStorage;
		
	// 	$reportingStorages = $this->getReportingStorages();
	// 	$availableStoragesByParents = $this->asTree();
	// 	$result = [];
		
	// 	// Все дочерние
	// 	$currentParents = [$storage];
	// 	while($currentParents){
	// 		$parent = array_shift($currentParents);
	// 		$result[] = $parent;
	// 		if(isset($availableStoragesByParents[$parent])){
	// 			foreach($availableStoragesByParents[$parent]['childs'] as $child){
	// 				if($child['available']){
	// 					$currentParents[] = $child['id'];
	// 				}
	// 			}
	// 		}
	// 	}
		
	// 	// Все родительские
	// 	$currentParents = [$reportingStorages[$storage]['parent_id']];
	// 	while($currentParents){
	// 		$parent = array_shift($currentParents);
	// 		if($parent && isset($reportingStorages[$parent]) && $reportingStorages[$parent]['available']){
	// 			$result[] = $parent;
	// 			$currentParents[] = $reportingStorages[$parent]['parent_id'];
	// 		}
	// 	}
		
	// 	return $result;
	// }

	
	/**
	 * Ветка ID доступных родительских отделов, относительно текущего (включая текущий)
	 */
	public function getAvailableParentsBranch($storage=false){
		$storage = $storage ? $storage : $this->mainStorage;
		
		$reportingStorages = $this->getReportingStorages();
		$availableStoragesByParents = $this->asTree();
		$result = [];
		$result[]= $storage;
		
		// Все родительские
		$currentParents = [$reportingStorages[$storage]['parent_id']];
		while($currentParents){
			$parent = array_shift($currentParents);
			if($parent && isset($reportingStorages[$parent]) && $reportingStorages[$parent]['available']){
				$result[] = $parent;
				$currentParents[] = $reportingStorages[$parent]['parent_id'];
			}
		}
		
		return $result;
	}
	
	/**
	 * Ветка ID доступных дочерних отделов, относительно текущего (включая текущий)
	 */
	public function getAvailableChildrenBranch($storage=false){
		$storage = $storage ? $storage : $this->mainStorage;
		
		$reportingStorages = $this->getReportingStorages();
		$availableStoragesByParents = $this->asTree();
		$result = [];
		
		// Все дочерние
		$currentParents = [$storage];
		while($currentParents){
			$parent = array_shift($currentParents);
			$result[] = $parent;
			if(isset($availableStoragesByParents[$parent])){
				foreach($availableStoragesByParents[$parent]['childs'] as $child){
					if($child['available']){
						$currentParents[] = $child['id'];
					}
				}
			}
		}
		
		return $result;
	}	
	
	/**
	 * Поиск корневого отдела относительно текущего
	 */
	public function getRootStorage($storage=false){
		if(!$storage){
			$storage = $this->mainStorage;
		}
		$reportingStorages = $this->getReportingStorages();
		$main = false;
		if($reportingStorages[$storage]['type']==1){
			$main = $reportingStorages[$storage];
		}
		elseif($reportingStorages[$storage]['type']==2){
			$currentParents = [$reportingStorages[$storage]['parent_id']];
			while($currentParents){
				$parent = array_shift($currentParents);
				if($parent && isset($this->allStorages[$parent]) && $this->allStorages[$parent]['type']==1){
					$main = $this->allStorages[$parent];
				}
				else{
					$currentParents[] = $this->allStorages[$parent]['parent_id'];
				}
			}
		}
		return $main;
	}
	
	
	/**
	 *  Типы отделов, в зависимости от уровня статистики
	 */
	public function allTypes(){
		$this->needleTypes = [Storages::TYPE_HI, Storages::TYPE_ROOT, Storages::TYPE_LOW];
		return $this;
	}
		
	public function hiTypes(){
		$this->needleTypes = [Storages::TYPE_HI, Storages::TYPE_ROOT];
		return $this;
	}
		
	public function lowTypes(){
		$this->needleTypes = [Storages::TYPE_ROOT, Storages::TYPE_LOW];
		return $this;
	}
		
	public function rootTypes(){
		$this->needleTypes = [Storages::TYPE_ROOT];
		return $this;
	}
	
	
	/**
	 * Выбор только активных отделов
	 */
	public function onlyActive($onlyActive=true){
		$this->onlyActive = $onlyActive;
		return $this;
	}
	
	
	/**
	 * Выбор только активных отделов
	 */
	public function onlyAvailable($onlyAvailable=true){
		$this->onlyAvailable = $onlyAvailable;
		return $this;
	}
	
	
	/**
	 * Имя доступного отдела по ID
	 */
	public function getAvailableStorageName($storageId){
		if(
			in_array($storageId, $this->userStorages)
			&& isset($this->allStorages[$storageId])
		){
			$name = $this->allStorages[$storageId]['name'];
		}
		else{
			$name = 'Отдел недоступен';
		}
		return $name;
	}
	
}