<?php
namespace frontend\widgets;

use yii\base\Widget;

class NotFoundWidget extends Widget
{
    public $title;

    public function init()
    {
        parent::init();
    }

    public function run()
    {
        $arResult = array(
            'title' => $this->title,
        );
		return $this->render('NotFound', $arResult);
    }
}