<?php
namespace frontend\widgets;

use yii\base\Widget;

class SortingWidget extends Widget
{
    public $fields;
    public $sorting;
    public $container='td';

    public function init()
    {
        parent::init();
    }

    public function run()
    {
        $result = [
            'container'	=> $this->container,
            'fields'	=> $this->fields,
            'sorting'	=> $this->sorting,
            'order'		=> [
				3 => 'sort-desc',
				4 => 'sort-asc'
			],
        ];
		return $this->render('Sorting', $result);
    }
}