<?php
namespace frontend\widgets;

use Yii;
use yii\base\Widget;
use frontend\models\helpers\ViewHelper;

class FilterStoragesWidget extends Widget
{
    public $storages = false; // устаревшее свойство
	
    public $form = false;
    public $containerClass = false;


    public function init()
    {
        parent::init();
    }


    public function run()
    {
		// поддержка устаревшего свойства
		if($this->storages){
			$storagesTree = $this->storages['available'];
			$currentStorage = $this->storages['current']['id'];
		}
		else{
			$storagesTree = Yii::$app->storagesData->find()->onlyActive(false)->onlyAvailable()->lowTypes()->asTree();
			$currentStorage = Yii::$app->storagesData->mainStorage;
		}

        $storagesSelect = ViewHelper::storagesSelectBuilder($storagesTree, 0, 0);
		
		$result = [
            'storagesSelect'	=> $storagesSelect['output'],
            'currentStorage'	=> $currentStorage,
            'form'				=> $this->form,
            'containerClass'	=> $this->containerClass,
        ];
		return $this->render('FilterStorages', $result);
    }
}