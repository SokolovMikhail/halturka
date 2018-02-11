<?php
namespace frontend\widgets;

use yii\base\Widget;

class TableWidget extends Widget
{
    public $fields;
    public $items;
    public $container = 'td';

    public function init()
    {
        parent::init();
    }

    public function run()
    {
        $result = [
            'container'	=> $this->container,
            'fields'	=> $this->fields,
            'items'	=> $this->items,
        ];
		return $this->render('Table', $result);
    }
}