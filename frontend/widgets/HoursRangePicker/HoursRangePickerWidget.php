<?php
namespace frontend\widgets\HoursRangePicker;

use Yii;
use yii\base\Widget;
use yii\helpers\ArrayHelper;

class HoursRangePickerWidget extends Widget
{
 	
	public $array;
	public $modelName;
	
	
    public function init()
    {
        parent::init();
    }

	
    public function run()
    {
		$result = [
            'array'			=> $this->array,
			'modelName'			=> $this->modelName,
			'daysOfWeek'	=> $this->getDaysOfWeek(),
        ];
		
// echo '<pre>';
// var_dump($result);
// exit;		
		return $this->render('HoursRangePicker', $result);
    }
	
	private function getDaysOfWeek()
	{
		$result = [
			0	=>	'Пн',
			1	=>	'Вт',
			2	=>	'Ср',
			3	=>	'Чт',
			4	=>	'Пт',
			5	=>	'Сб',
			6	=>	'Вс',
		];
		
		return $result;
	}
}