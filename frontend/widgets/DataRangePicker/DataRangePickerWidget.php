<?php
namespace frontend\widgets\DataRangePicker;

use Yii;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use frontend\widgets\DataRangePicker\DataRangeAsset;

class DataRangePickerWidget extends Widget
{
 	
	public $name;
	public $startDate;
	public $endDate;
	public $useTimePicker = false;
	public $showRanges = true;
	public $timePicker = false;
	
    public function init()
    {
        parent::init();
		$this->registerAssets();
    }

	
    public function run()
    {
		$visibility = true;
		
		if($this->timePicker){//Поддержка нового свойства
			if(isset($this->timePicker['usage'])){
				$this->useTimePicker = $this->timePicker['usage'];
			}
			if(isset($this->timePicker['visibility'])){
				$visibility = $this->timePicker['visibility'];
			}			
		}
		
		$result = [
            'name'			=> $this->name,
            'startDate'		=> $this->startDate,
            'endDate'		=> $this->endDate,
            'useTimePicker'	=> $this->useTimePicker,
			'timePickerVisibility' => $visibility,
			'showRanges' 	=> $this->showRanges,
        ];
		return $this->render('DataRangePicker', $result);
    }
	
	 /**
	 * Registers the needed assets
	 */
	public function registerAssets()
	{
		$view = $this->getView();
		DataRangeAsset::register($view);
	}	
}