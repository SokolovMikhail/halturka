<?php
namespace frontend\widgets\FilterItemsInToStorages;

use yii\base\Widget;
use frontend\models\helpers\ViewHelper;


class FilterItemsInToStoragesWidget extends Widget
{
	public $storages;
    public $items;
    public $storagesTreeArr;
    public $fieldName;
    public $active;
    public $data;
	public $collapsed = false;


    public function init()
    {
        parent::init();		
		$itemsInToStoragesArr = [];
		if(is_array($this->items)){
			foreach($this->items as $i){
				$itemsInToStoragesArr[$i['storage_id']][] = $i;
			}
			if(!count($this->active)){
				foreach($this->items as $i){
					$this->active[] = $i['id'];
				}
			}
			$this->items = $itemsInToStoragesArr;		
		}
    }


    public function run()
    {

    	$tree = ViewHelper::itemsInStoragesTreeBuilder(
			$this->storages,
			$this->items,
			0,
			$this->fieldName,
			$this->active,
			0,
			isset($this->data) ? $this->data : false,
			$this->collapsed
		);

		return $this->render('FilterItemsInToStorages',['result' => $tree['output']]);
    }

}