<?php
namespace frontend\widgets;

use yii\base\Widget;

class FilterItemsCheckListWidget extends Widget
{
    public $name = false; // устаревшее свойство
	
    public $feildName;
    public $active;
    public $items;
    public $viewType = 'check';

    public function init()
    {
        parent::init();
    }

    public function run()
    {
		if($this->name){ // поддержка устаревшего свойства
			$this->feildName = $this->name.'_list';
		}
		
		if(!count($this->active) && is_array($this->items)){
			foreach($this->items as $item){
				$this->active[] = $item['id'];
			}
		}
		
		if(is_array($this->items)){		
			$result = [
				'items'		=> $this->items,
				'feildName'	=> $this->feildName,//TODO: исправить на fieldName
				'active'	=> $this->active,
				'viewType'	=> $this->viewType,
			];
			return $this->render('FilterItemsCheckList', $result);
		}
    }
}