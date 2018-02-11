<?
namespace frontend\modules\admin;

use Yii;

class Admin extends \yii\base\Module
{
    public $controllerNamespace = 'app\modules\admin\controllers';
    public function init()
    {
        parent::init();
		
	}

	
	/**
	 * Пункт меню
	 */
	public function mainNav(){
		return [
			[

				'href'	=> '#',
				'sort'	=> 165,
				'class'	=> 'dropdown-toggle',
				'title'	=> 'Админ. Панель',
				'icon'	=> 'navbar-settings-ico settings-ico-style',
				'roles'	=> ['manageOrders'],
				'dropdown'	=> [
					'class'	=> 'dropdown-menu',
					'items'	=> [
						[
							'href' => 'admin/order',
							'title' => 'Заявки',
							'roles'	=> ['manageOrders'],
						],					
						[
							'href' => 'admin/brands',
							'title' => 'Модели',
							'roles'	=> ['manageOrders'],
						],
						[
							'href' => 'admin/equipment',
							'title' => 'Оборудование',
							'roles'	=> ['manageOrders'],
						],
						[
							'href' => 'admin/service',
							'title' => 'Услуги',
							'roles'	=> ['manageOrders'],
						],						
						[
							'href' => 'admin/clients',
							'title' => 'Клиенты',
							'roles'	=> ['manageOrders'],
						],
						[
							'href' => 'admin/order/calendar',
							'title' => 'План установок',
							'roles'	=> ['manageOrders'],
						],						
					]
				]

			]
		];
	}
	
	
	/**
	 * Доступ к модулю по ролям 
	 */
	public function getPermission(){
		return ['manageOrders'];
	}
	
}
