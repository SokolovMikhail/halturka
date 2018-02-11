<?php
namespace frontend\widgets\ActiveDiagram;

use yii\base\Widget;
use frontend\widgets\ActiveDiagram\ActiveDiagramAsset;
use frontend\models\AppOptions;

class ActiveDiagramWidget extends Widget
{
    public $items;
    public $storage;
    public $diagramTimeData;
    public $entityName;
    public $subentityName;
    public $isHourly;
    public $useActive;
    public $useWork;
    public $useVoltage;

    public function init()
    {
        parent::init();
		$this->registerAssets();
    }

    public function run()
    {
		$arResult = array(
            'items'				=> $this->items,
            'storage'			=> $this->storage,
            'diagramTimeData'	=> $this->diagramTimeData,
            'entityName'		=> $this->entityName,
            'subentityName'		=> $this->subentityName,
            'isHourly'			=> $this->isHourly,
            'useActive'			=> $this->useActive,
            'useWork'			=> AppOptions::can('useWorkStatistic'),
            'useVoltage'		=> $this->useVoltage,
        );
		return $this->render('ActiveDiagram', $arResult);
    }
	
	/**
	 * Registers the needed assets
	 */
	public function registerAssets()
	{
		$view = $this->getView();
		ActiveDiagramAsset::register($view);
	}
}