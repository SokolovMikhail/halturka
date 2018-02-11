<?php
namespace frontend\widgets;

use yii\base\Widget;

class FilterSelectWidget extends Widget
{
    public $name;
    public $active;
    public $heading;
	public $items;

    public function init()
    {
        parent::init();
    }

    public function run()
    {
        $result = array(
            'items' => $this->items,
			'heading' => $this->heading,
			'name' => $this->name,
			// 'field' => 'name'
        );
		if(isset($this->active)){
			$result['active'] = $this->active;
		}
		return $this->render('FilterSelect', $result);
    }
}