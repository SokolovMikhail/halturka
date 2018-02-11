<?php
namespace frontend\widgets\FilterCategoriesWithLimits;
use frontend\widgets\FilterCategoriesWithLimits\FilterCategoriesWithLimitsAssets;
use yii\base\Widget;

class FilterCategoriesWithLimitsWidget extends Widget
{
    public $name;
    public $active;
    public $items;
    public $viewType;
	public $crashesTypes;
	public $crashLimit;

    public function init()
    {
        parent::init();
		if(!count($this->active) && is_array($this->items)){
			foreach($this->items as $item){
				$this->active[] = $item['id'];
			}
		}
		
		$this->viewType = isset($this->viewType) ? $this->viewType : 'check';
		$this->registerAssets();
    }

    public function run()
    {
		if(is_array($this->items)){		
			$arResult = array(
				'items'		=> $this->items,
				'name'		=> $this->name,
				'active'	=> $this->active,
				'viewType'	=> $this->viewType,
				'crashes_types' => $this->crashesTypes,
				'crash_limits' => $this->crashLimit,
			);
			return $this->render('FilterCategoriesWithLimits', $arResult);
		}
    }
	
	/**
	 * Registers the needed assets
	 */
	public function registerAssets()
	{
		$view = $this->getView();
		FilterCategoriesWithLimitsAssets::register($view);
	}	
}