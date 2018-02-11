<?php
namespace frontend\widgets\NavMenu;

use Yii;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use frontend\widgets\NavMenu\NavMenuAsset;

class NavMenuWidget extends Widget
{
    public $current; 
	public $name;
	private $mainNav = [
		'class'	=> 'nav navbar-nav',
		'items'	=> [
			'configs' => [
				'href'	=> '#',
				'sort'	=> 160,
				'title'	=> 'Настройки',
				'icon'	=> 'navbar-settings-ico settings-ico-style',
				'roles'	=> ['superadmin', 'admin', 'manageOperators'],
				'dropdown'	=> [
					'class'	=> 'dropdown-menu',
					'items'	=> [
						[
							'href'	=> 'operators',
							'title'	=> 'Операторы',
							'roles'	=> ['superadmin', 'admin', 'manageOperators'],
						],
						[
							'href'	=> 'devices',
							'title'	=> 'Техника',
							'roles'	=> ['superadmin', 'admin'],
						],
						[
							'separator' => true,
							'roles'	=> ['superadmin'],
						],
						[
							'href' => 'devices-category',
							'title' => 'Типы техники',
							'roles'	=> ['superadmin'],
						],
						[
							'href' => 'licenses',
							'title' => 'Категории прав',
							'roles'	=> ['superadmin'],
						],
						[
							'href' => 'storages',
							'title' => 'Отделы',
							'roles'	=> ['superadmin'],
						],
						[
							'href'	=> 'settings/kpi',
							'title'	=> 'Настройки KPI',
							'roles'	=> ['superadmin'],
						],
						[
							'separator' => true,
							'roles'	=> ['superadmin'],
						],
						[
							'href'	=> 'settings',
							'title'	=> 'Общие настройки',
							'roles'	=> ['superadmin'],
						],
						[
							'href' => 'user',
							'title' => 'Пользователи',
							'roles'	=> ['superadmin'],
						],
					]
				]
			],
					

			'help' => [
				'href'	=> 'help',
				'sort'	=> 170,
				'title'	=> 'Помощь',			
				'icon'	=> 'icon-question-mark question-mark-ico-style',
				'roles'	=> ['user', 'superadmin', 'admin'],
				'dropdown'	=> [
					'class'	=> 'dropdown-menu',
					'items'	=> [
						[
							'href' => 'help',
							'title' => 'Справка',
							'roles'	=> ['user', 'superadmin', 'admin'],
						]						
					]					
				],
			],
			'logout' => [
				'href'	=> 'account/logout',
				'sort'	=> 180,
				'title'	=> 'Выйти',
				'icon'	=> 'navbar-logout-ico logout-ico-style',
			],
		]
	];
	

    public function init()
    {
        parent::init();
		$this->registerAssets();
    }

    public function run()
    {
		$this->getItems();
		$result = [
            'menu'		=> $this->mainNav,
            'current'	=> $this->current,
        ];
		return $this->render('NavMenu', $result);
    }
	
	
	/**
     * Пункты меню из модулей
     */
    public function getItems(){
		foreach (Yii::$app->getModules() as $id => $module) {
			if (method_exists($module, 'mainNav')) {
				$items = $module->mainNav();
				if($items){
					foreach($items as $key=>$item){
						if(isset($item['parent']))
						{	
							// Зачем отдельный метод setSubModules ?
							$this->setSubModules($item);
						}
						if(isset($item['unset'])){
							unset($this->mainNav['items'][$item['unset']]);
						}
						if(!isset($item['parent']))
						{
							if(!isset($this->mainNav['items'][$key])){
								$this->mainNav['items'][$key] = $item;
							}else{
								$this->mainNav['items'][] = $item;
							}
						}
					}
				}
			}
		}
		ArrayHelper::multisort($this->mainNav['items'], ['sort'], [SORT_ASC]);
	}
	
	public function setSubModules($item)
	{		
		if(isset($this->mainNav['items'][$item['parent']]))
		{
			$this->mainNav['items'][$item['parent']]['dropdown']['items'][] = $item;			
		}
	}
	
	 /**
	 * Registers the needed assets
	 */
	public function registerAssets()
	{
		$view = $this->getView();
		NavMenuAsset::register($view);
	}
}