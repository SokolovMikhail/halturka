<?php
namespace frontend\widgets;

use yii\base\Widget;

class TimePickerWidget extends Widget
{
    public $name;
    public $start;
    public $end;
    public $current;

    public function init()
    {
        parent::init();
    }

    public function run()
    {
		$params = [
            'start' => $this->start,
            'end' => $this->end,
            'name' => $this->name,
            'current' => $this->current,
        ];
		return $this->render('TimePicker', $params);
    }
}