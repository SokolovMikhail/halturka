<?php
namespace frontend\widgets;

use yii\base\Widget;

class FilterDinamicLimitsWidget extends Widget
{
    public $crashesTypes;
    public $crashLimit;
    public $title;

    public function init()
    {
        parent::init();
    }

    public function run()
    {
        $arResult = array(
            'crashesTypes' => $this->crashesTypes,
			'crashLimit' => $this->crashLimit,
			'title' => $this->title ? $this->title : 'Задать пороги происшествий',
        );
		return $this->render('FilterDinamicLimits', $arResult);
    }
}