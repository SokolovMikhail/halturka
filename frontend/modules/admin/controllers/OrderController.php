<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\User;
use frontend\models\Accounts;
use app\modules\admin\models\PurchasingOrder;
use app\modules\admin\models\GoogleApi;
use yii\data\Pagination;
use frontend\models\MainDevices;
use frontend\models\Brands;
use frontend\models\Devices;
use frontend\models\BrandsConfig;
use yii\helpers\FileHelper;
use app\modules\admin\models\Client;
use Google_Service_Calendar_Event;
use Google_Service_Calendar;
use Google_Client;
use Google_Service_Exception;
use yii\helpers\Url;
use frontend\models\Equipment;
use app\modules\admin\models\Service;
use app\modules\admin\models\ServiceOrder;
use yii\helpers\Json;


/**
 * HarnessController implements the CRUD actions.
 */
class OrderController extends Controller
{
	public $layout='/../../views/layouts/account.php';


    public function behaviors()
    {
        return [
			'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
					'actions' => ['yandex', 'devices-amount', 'index', 'update-date', 'status-update', 'get-installation-task', 'get-equipment-doc-all', 'get-warranty-month-xls', 'get-equipment-doc-warranty', 'devices-list-xls', 'create-child', 'update', 'create', 'delete', 'get-equipment-xls', 'get-supply-contract', 'get-equipment-doc', 'get-harness-xls', 'get-install-contract', 'calendar', 'add-events', 'devices-list', 'get-equipment-xls-compact'],
                        'allow' => true,
                        'roles' => ['manageOrders'],
                    ],
                ],
				'denyCallback' => function ($rule, $action) {
					return $this->goHome();
				}
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                // 'actions' => [
                    // 'delete' => ['post'],
                // ],
            ],
        ];
    }
	
	/**
	* Создать папки для фото на Яндекс Диске
	*/
	public function actionYandex($id)
	{
		$response = PurchasingOrder::createYandexDiskFolders($id);
		return $response;		
	}
	
	// public function actionMigrate()
	// {
		// $orders = PurchasingOrder::find()->all();
		// foreach($orders as $item){
			// if($item->agreement_installer == 3){
				// $item->contract_status = 10;
				// $item->devices_list_status = 10;
				// $item->equipment_prod_status = 30;
				// $item->harness_prod_status = 20;
				// $item->account_status = 10;
				
				// $item->installer_agreement = 1;
				// $item->client_agreement = 1;
				// $item->installed = 1;
				
				// $item->save();
			// }
		// }
	// }
	
	
	//Все типы оборудования
	private static function GetStatusesFilter()
	{
		$result = [];
		$clients = Client::find()->all();
		foreach($clients as $item){
			$result[] = ['id' => $item->id, 'name' => $item->name];
		}		
		return $result;
	}

	public function actionUpdateDate($orderId, $field, $val)
	{
		$order = PurchasingOrder::findOne($orderId);
		if($order){
			if($field == 'date'){
				$order->$field = $val;
				if($_SERVER['SERVER_ADDR'] != '127.0.0.1'){
					$this->checkEvent($order->id);//Проверка события установки в календаре
				}
				if($order->save()){
					if($_SERVER['SERVER_ADDR'] != '127.0.0.1'){
						$this->createEvent($order->id, false);//Создание события установки в календаре//Создание события отгрузки в календаре
					}
					return true;
				}				
			}elseif($field == 'date_shipment'){
				$date = new \DateTime($val);
				$order->$field = $date->format('Y-m-d H:i:s');				
				if($_SERVER['SERVER_ADDR'] != '127.0.0.1'){
					$this->checkShipmentEvent($order->id);//Проверка события отгрузки в календаре
				}
				if($order->save()){
					if($_SERVER['SERVER_ADDR'] != '127.0.0.1'){
						$this->createShipmentEvent($order->id);//Создание события отгрузки в календаре
					}
					return true;
				}
			}else{
				return false;
			}
			
		}else{
			return false;
		}		
	}
	
	public function actionStatusUpdate($orderId, $status, $val)
	{	
		$order = PurchasingOrder::findOne($orderId);
		if($order){
			$order->$status = $val;
			if($_SERVER['SERVER_ADDR'] != '127.0.0.1' && ($status == 'installer_agreement' || $status == 'client_agreement')){
				$this->checkShipmentEvent($order->id);//Проверка события отгрузки в календаре
				$this->checkEvent($order->id);//Проверка события установки в календаре			
			}
			if($order->save()){
				if($_SERVER['SERVER_ADDR'] != '127.0.0.1' && ($status == 'installer_agreement' || $status == 'client_agreement')){
					$this->createShipmentEvent($order->id);//Создание события отгрузки в календаре
					$this->createEvent($order->id, false);//Создание события установки в календаре
				}	
				$className = 'white-background';
				
				if($order->installed){
					$className = 'green-background';
				}elseif($order->contract_status != 0 && $order->devices_list_status == 10 && $order->equipment_prod_status == 30 && $order->harness_prod_status == 20 && $order->account_status != 0 && !($order->installed) && $order->installer_agreement && $order->client_agreement){
					$className = 'yellow-background';
				}elseif($order->contract_status != 0 && $order->installer_agreement){
					$className = 'pink-background';
				}
				
				return $className;
			}else{
				return false;
			}
		}else{
			return false;
		}		
	}
	
	public function actionDevicesAmount($monthFilter)
	{
		$orders = PurchasingOrder::find()->where(['deleted' => 0])->all();
		
		$result = [
			'total_devices_count' => 0,
			'installed_count' => 0,
			'work_count' => 0,
			'others_count' => 0,
		];
		
		foreach($orders as $id=>$item){
			if($item->date && $item->date != 'Invalid date'){
				$item->date = new \DateTime($item->date, new \DateTimeZone('Europe/Moscow'));
				$monthYear = $item->date->format('m-y');
				if($monthYear == $monthFilter){
					$newOrderDevice = MainDevices::find()->where(['order_id' => $item->id, 'order_type' => 0])->all();
					$devices_count = count($newOrderDevice);
					
					if($item->installed){
						$result['installed_count'] += $devices_count;
					}elseif($item->contract_status == 10 && $item->installer_agreement){
						$result['work_count'] += $devices_count;
					}else{
						$result['others_count'] += $devices_count;
					}
					$result['total_devices_count'] += $devices_count;
				}
			}				
		}
		
		return Json::encode($result);
	}
	
	public function actionCalendar()
	{
		// получение коллекции (yii\web\CookieCollection) из компонента "response"
		$cookies = Yii::$app->response->cookies;
		
		// добавление новой куки в HTTP-ответ
		$cookies->add(new \yii\web\Cookie([
			'name' => 'prev',
			'value' => 'calendar',
		]));
		
		$clients = Client::find()->all();
		$clientsArray = [];
		foreach($clients as $item){
			$clientsArray[$item->id] = $item->name;
		}
		
		$result['items'] = PurchasingOrder::find()->where(['deleted' => 0])->all();
		$result['null'] = [];
		$result['ids'] = [];
		$result['plan'] = [];
		$i = 0;
		foreach($result['items'] as $item){
			if($item->date != NULL && $item->date != 'Invalid date'){
				$item->date = new \DateTime($item->date, new \DateTimeZone('Europe/Moscow'));
			}
			else{
				$result['null'][] = $item;
				$result['ids'][] = $i;
			}
			if($item->date_shipment != NULL && $item->date_shipment != 'Invalid date'){
				$item->date_shipment = new \DateTime($item->date_shipment, new \DateTimeZone('Europe/Moscow'));
			}			
			$i++;
		}
		foreach($result['ids'] as $id){
			unset($result['items'][$id]);
		}
		$cities['all'] = [];
		$cities['current'] = [];
		$cities['current_month'] = (new \DateTime())->format('m-y');
		ArrayHelper::multisort($result['items'], 'date', SORT_ASC);
		$result['items'] = array_merge($result['items'], $result['null']);
		$monthsList = [];
		foreach($result['items'] as $item){
			if($item->date && $item->date != 'Invalid date'){
				$monthYear = $item->date->format('m-y');
				$monthsList[$monthYear] = $monthYear;
				if(!isset($cities['all'][$monthYear])){
					$cities['all'][$monthYear] = [];
				}
				
				
				if(!isset($result['plan'][$monthYear])){
					$result['plan'][$monthYear]['items'] = [];
					$result['plan'][$monthYear]['total_devices_count'] = 0;
					$result['plan'][$monthYear]['total_orders_count'] = 0;
					$result['plan'][$monthYear]['installed_count'] = 0;
					$result['plan'][$monthYear]['work_count'] = 0;
					$result['plan'][$monthYear]['others_count'] = 0;
					$month = $item->date->format('m');
					$result['plan'][$monthYear]['date_string'] = isset(PurchasingOrder::getMonthsString()[$month]) ? PurchasingOrder::getMonthsString()[$month] : $month;
				}
				$newOrderDevice = MainDevices::find()->where(['order_id' => $item->id, 'order_type' => 0])->all();
				$devices_count = count($newOrderDevice);
				$warrantyDevices = MainDevices::find()->where(['order_id' => $item->id, 'order_type' => 1])->all();
				$devices_count_warranty = count($warrantyDevices);
				
				$checkedDevices = 0;
				$devicesWithPhoto = 0;
				foreach($warrantyDevices as $d){
					if(strlen($d->warranty_result) > 0){
						$checkedDevices++;
					}
					if($d->photo){
						$devicesWithPhoto++;
					}
				}
				foreach($newOrderDevice as $d){
					if($d->photo){
						$devicesWithPhoto++;
					}
				}				
				$warrantyDevicesChecked = 0;
				if($checkedDevices == $devices_count_warranty){
					$warrantyDevicesChecked = 1;
				}
				
				$orderPhotos = 0;
				if($devices_count + $devices_count_warranty  == $devicesWithPhoto){
					$orderPhotos = 1;
				}				
				
				$configuration = BrandsConfig::find()->where(['owner_id' => $item->id, 'owner_type' => 'order'])->one();
				$equipmentArray = [];
				if($configuration){
					$equipmentArray['config'] = $configuration->id;
					$equipmentList = unserialize($configuration->equipment_ids);
					$equipmentArray = $equipmentList;
				}else{
					$equipmentArray = [];
				}				
				
				$config_count = count($equipmentArray);
				
				$orderName = $item->name;
				if($item->client_id && isset($clientsArray[$item->client_id])){
					$orderName = $clientsArray[$item->client_id].':<br>'.$item->name;
				}
				
				$className = 'white-background';
				
				if($item->installed){
					$className = 'green-background';
				}elseif($item->contract_status != 0 && $item->devices_list_status == 10 && $item->equipment_prod_status == 30 && $item->harness_prod_status == 20 && $item->account_status != 0 && !($item->installed) && $item->installer_agreement && $item->client_agreement){
					$className = 'yellow-background';
				}elseif($item->contract_status != 0 && $item->installer_agreement){
					$className = 'pink-background';
				}
				
				$result['plan'][$monthYear]['items'][] = [
					'id' => $item->id,
					'name' => $orderName,
					'date_shipment' => $item->date_shipment ? $item->date_shipment->format('d.m.Y') : 'Дата отгрузки не выбрана',
					'date' => $item->date->format('d.m.Y'),
					'devices_count' => $devices_count,
					'devices_count_warranty' => $devices_count_warranty,
					'config_count' => $config_count,
					'responsible' => $item->responsible,
					
					'status_1' => $item->contract_status,
					'status_2' => $item->devices_list_status,
					'status_3' => $item->equipment_prod_status,
					'status_4' => $item->harness_prod_status,
					'status_5' => $item->account_status,
					
					'installer_agreement' => $item->installer_agreement,
					'client_agreement' => $item->client_agreement,
					'installed' => $item->installed,
					'installed_comment_short' => strlen($item->installed_comment) > 6 ? mb_substr($item->installed_comment, 0, 4).'...' : (strlen($item->installed_comment) > 0 ? $item->installed_comment : false),
					'installed_comment_full' => strlen($item->installed_comment) > 6 ? $item->installed_comment : false,
					
					'contractor_comment' => $item->contractor_comment,
					'warranty_devices_checked' => $warrantyDevicesChecked,
					'order_photo' => $orderPhotos,
					'background' => $className
				];
				$cities['all'][$monthYear][$item->id] = [
					'name' => $orderName,
					'city' => $item->city ? $item->city : $item->name,
					'content' => $item->date->format('d-m-Y').'<br/>'.$devices_count.'/'.$devices_count_warranty,
					'installed' => $item->installed ? 1 : 0,					
				];
				$cities['current'][$item->id] = [
					'name' => $orderName,
					'city' => $item->city ? $item->city : $item->name,
					'content' => $item->date->format('d-m-Y').'<br/>'.$devices_count.'/'.$devices_count_warranty,
					'installed' => $item->installed ? 1 : 0,
				];
				if($item->installed){
					$result['plan'][$monthYear]['installed_count'] += $devices_count;
				}elseif($item->contract_status == 10 && $item->installer_agreement){
					$result['plan'][$monthYear]['work_count'] += $devices_count;
				}else{
					$result['plan'][$monthYear]['others_count'] += $devices_count;
				}
				$result['plan'][$monthYear]['total_devices_count'] += $devices_count;
				$result['plan'][$monthYear]['total_orders_count']++;				
			}
		}
		return $this->render('calendar', [
			'result' => $result,
			'cities' => Json::encode($cities),
			'currentMonth' => $cities['current_month'],
			'monthsList' => $monthsList,
		]);
		
	}

    public function actionIndex($client = false, array $status_list=[], $contractor = false, $parent = false)
    {
		$parentOrders = PurchasingOrder::find()->select('id, name, client_id')->where(['order_parent' => -1, 'deleted' => 0])->all();
		$clients = Client::find()->all();
		$clientsById = [];
		foreach($clients as $item){
			$clientsById[$item->id] = $item->name;
		}
		
		$parents = [];
		foreach($parentOrders as $item){
			if(isset($clientsById[$item->client_id])){
				$parents[$item->id] = [
					'id' => $item->id,
					'name' => $clientsById[$item->client_id] . ' ' . $item->name,
				];
			}else{
				$parents[$item->id] = [
					'id' => $item->id,
					'name' => $item->name
				];
			}
		}		

		// получение коллекции (yii\web\CookieCollection) из компонента "response"
		$cookies = Yii::$app->response->cookies;		
		
		if($client !== false){
			$cookies->add(new \yii\web\Cookie([
				'name' => 'client',
				'value' => $client,
				'expire' => time() + 86400 * 365
			]));
			$bufClient = $cookies->get('client');			
		}else{
			$bufClient = Yii::$app->request->cookies->getValue('client');
			if($bufClient != NULL){
				$client = $bufClient;
			}
		}
		
		// добавление новой куки в HTTP-ответ
		$cookies->add(new \yii\web\Cookie([
			'name' => 'prev',
			'value' => 'index',
		]));		
		
		$statusesList = [];
		$statusesList += ['clients' => OrderController::GetStatusesFilter()];
		$statusesList['progress'][] = ['id' => 'new', 'name' => 'Новые'];
		$statusesList['progress'][] = ['id' => 'installer_agreement', 'name' => 'Подписанные'];
		$statusesList['progress'][] = ['id' => 'client_agreement', 'name' => 'Гарантия'];
		$statusesList['progress'][] = ['id' => 'installed', 'name' => 'Установлено'];
		$where = [];
		$where['deleted'] = 0; 
		if($client && $client != -1){
			$where['client_id'] = $client;
		}
		if($contractor){
			$where['contractor'] = $contractor;
		}
		$statusWhere = [];
		$contractStatus = [];
		if(in_array('new', $status_list)){
			$statusWhere['where_1']['contract_status'] = 0;
			$statusWhere['where_1']['installed'] = 0;
		}
		if(in_array('installer_agreement', $status_list)){
			$statusWhere['where_2']['contract_status'] = 10;
			$statusWhere['where_2']['installed'] = 0;
		}
		if(in_array('client_agreement', $status_list)){
			$statusWhere['where_3']['contract_status'] = 20;
			$statusWhere['where_3']['installed'] = 0;
		}
		if(in_array('installed', $status_list)){
			$statusWhere['where_4']['installed'] = 1;
		}		
		//Пагинация

		if(!$status_list){
			$statusWhere['where_1']['contract_status'] = 0;
			$statusWhere['where_1']['installed'] = 0;

			$statusWhere['where_2']['contract_status'] = 10;
			$statusWhere['where_2']['installed'] = 0;

			$statusWhere['where_3']['contract_status'] = 20;
			$statusWhere['where_3']['installed'] = 0;			
		}
		if($parent){
			$queries = [];
			foreach($statusWhere as $key=>$val){
				$whereWithStatus = [];
				$whereWithStatus = array_merge($where, $val);						
				$queries[] = PurchasingOrder::find()
					->where(['and', $whereWithStatus])
					->andWhere(['or', ['id' => $parent], ['order_parent' => $parent]]);					
			}
			if($queries){
				$query = array_shift($queries);
				foreach($queries as $q){
					$query->union($q);
				}			
			}				
		}else{
			$queries = [];
			foreach($statusWhere as $key=>$val){
				$whereWithStatus = [];
				$whereWithStatus = array_merge($where, $val);						
				$queries[] = PurchasingOrder::find()
					->where(['and', $whereWithStatus]);				
			}
			if($queries){
				$query = array_shift($queries);
				foreach($queries as $q){
					$query->union($q);
				}			
			}				
		}
		

		$countQuery = clone $query;
		$pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 150]);
		$pages->pageSizeParam = false;
		//--------------------------------------------------------------------------------
		$result = ['items' => $query->offset($pages->offset)
			->limit($pages->limit)
			->all()];		

		$result['null'] = [];
		$result['ids'] = [];
		$i = 0;
		foreach($result['items'] as $item){
			if($item->date != NULL && $item->date != 'Invalid date'){
				$item->date = new \DateTime($item->date, new \DateTimeZone('Europe/Moscow'));
			}
			if($item->date_shipment != NULL && $item->date_shipment != 'Invalid date'){
				$item->date_shipment = new \DateTime($item->date_shipment, new \DateTimeZone('Europe/Moscow'));
			}			
			else{
				$result['null'][] = $item;
				$result['ids'][] = $i;
			}
			$i++;
		}
		foreach($result['ids'] as $id){
			unset($result['items'][$id]);
		}
		
		ArrayHelper::multisort($result['items'], 'date', SORT_ASC);
		$result['items'] = array_merge($result['items'], $result['null']);
		$i = 0;
		$items = NULL;
		foreach($result['items'] as $item){
			$items[$item->id]['count'] = count(MainDevices::find()->where(['order_id' => $item->id, 'order_type' => 0])->all());
			$items[$item->id]['countW'] = count(MainDevices::find()->where(['order_id' => $item->id, 'order_type' => 1])->all());
			$devices = MainDevices::find()->where(['order_id' => $item->id])->all();
			foreach($devices as $device){
				if($device->config_id == NULL || !BrandsConfig::findOne($device->config_id) || $device->model_id == NULL){
					$result['red'][$item->id] = true;
					break;
				}
			}
			$i++;
		}			
		return $this->render('index', [
			'result' => $result, 'pagenator' => $pages,
			'items' => $items,
			'statuses_list' => $statusesList,
			'client' => $client,
			'parents' => $parents
		]);
    }

    public function actionCreateChild($parentId = false)
    {
		$parentOrders = PurchasingOrder::find()->select('id, name, client_id')->where(['order_parent' => -1, 'deleted' => 0])->all();
		$clients = Client::find()->all();
		$clientsById = [];
		foreach($clients as $item){
			$clientsById[$item->id] = $item->name;
		}
		
		$parents = [];
		$parents[-1] = '';
		foreach($parentOrders as $item){
			if(isset($clientsById[$item->client_id])){
				$parents[$item->id] = $clientsById[$item->client_id] . ' ' . $item->name;
			}else{
				$parents[$item->id] = $item->name;
			}
		}
		
		$files = FileHelper::findFiles('uploads/Blanks',['recursive'=>FALSE, 'only'=>['*.doc','*.docx']]);
		$filesNormalized = [];
		foreach($files as $file){
			$file = FileHelper::normalizePath($file, '/');
			$buf = explode("/", $file);
			$filesNormalized[] = $buf[2];
		}		
		//----------------------------------------------------------------------------------------------------------------------------------
		$parent = PurchasingOrder::findOne($parentId);
		
		$model = new PurchasingOrder();
		
		$model->contacts = $parent->contacts;
		$model->card_type = $parent->card_type;
		$model->KPP = $parent->KPP;
		$model->name_controller = $parent->name_controller;
		$model->engagement = $parent->engagement;
		$model->name_defendant = $parent->name_defendant;
		$model->phone_defendant = $parent->phone_defendant;
		$model->mail_defendant = $parent->mail_defendant;
		$model->gsm_operator = $parent->gsm_operator;
		$model->customer = $parent->customer;
		$model->order_parent = $parent->id;
		$model->client_id = $parent->client_id;

		if ($model->load(Yii::$app->request->post())) {
			$date = new \DateTime($model->date_shipment);
			$model->date_shipment = $date->format('Y-m-d H:i:s');			
			$model->deleted = 0;
			if($model->save())
			{
				return $this->redirect(['update', 'id' => $model->id]);
			}

		}

		return $this->render('create', [
			'model' => $model, 
			'cardTypes' => $model::getCardsTypes(), 
			'operators' => $model::getOperatorsTypes(),			
			'files' => $filesNormalized,
			'clients' => Client::getAllClients(),
			'parents' => $parents,
		]);		
	}

    public function actionCreate($id = false, $month = false)
    {
		$parentOrders = PurchasingOrder::find()->select('id, name, client_id')->where(['order_parent' => -1, 'deleted' => 0])->all();
		$clients = Client::find()->all();
		$clientsById = [];
		foreach($clients as $item){
			$clientsById[$item->id] = $item->name;
		}
		
		$parents = [];
		$parents[-1] = '';
		foreach($parentOrders as $item){
			if(isset($clientsById[$item->client_id])){
				$parents[$item->id] = $clientsById[$item->client_id] . ' ' . $item->name;
			}else{
				$parents[$item->id] = $item->name;
			}
		}
		
		$files = FileHelper::findFiles('uploads/Blanks',['recursive'=>FALSE, 'only'=>['*.doc','*.docx']]);
		$filesNormalized = [];
		foreach($files as $file){
			$file = FileHelper::normalizePath($file, '/');
			$buf = explode("/", $file);
			$filesNormalized[] = $buf[2];
		}		
		
		$model = new PurchasingOrder();
		$model->gsm_operator = 1;
		if($id){//Копирование заявки если пришел Id
			$parent = PurchasingOrder::findOne($id);
			//$model->name = $parent->name;
			$model->contacts = $parent->contacts;
			$model->card_type = $parent->card_type;
			$model->wifi = $parent->wifi;
			$model->pocket = $parent->pocket;
			$model->KPP = $parent->KPP;
			$model->name_controller = $parent->name_controller;
			$model->engagement = $parent->engagement;
			$model->name_defendant = $parent->name_defendant;
			$model->phone_defendant = $parent->phone_defendant;
			$model->mail_defendant = $parent->mail_defendant;
			$model->gsm_operator = $parent->gsm_operator;
		}
		if($month){
			$splittedDate = explode('-', $month);
			$startDate = new \DateTime('01-' . $splittedDate[0] . '-20' . $splittedDate[1]);
			$model->date = $startDate->format('d.m.Y');
			$model->date_shipment = $startDate->format('d.m.Y');
		}
		if ($model->load(Yii::$app->request->post())) {
			$date = new \DateTime($model->date_shipment);
			$model->date_shipment = $date->format('Y-m-d H:i:s');			
			$model->deleted = 0;
			if($model->save())
			{
				return $this->redirect(['update', 'id' => $model->id]);
			}

		}

		return $this->render('create', [
			'model' => $model, 
			'cardTypes' => $model::getCardsTypes(), 
			'operators' => $model::getOperatorsTypes(),			
			'files' => $filesNormalized,
			'clients' => Client::getAllClients(),
			'parents' => $parents,
		]);
    }

	//Скачивание списка оборудования
	public function actionGetEquipmentXls($id)
	{		
		PurchasingOrder::createEquipmentXls(PurchasingOrder::find()->where(['id' => $id])->one());
	}

	//Скачивание списка оборудования
	public function actionGetEquipmentXlsCompact($id)
	{
		PurchasingOrder::createEquipmentXlsCompact(PurchasingOrder::find()->where(['id' => $id])->one());
	}	
	
	//Скачивание списка техники
	public function actionDevicesList($id)
	{
		PurchasingOrder::createDevicesDoc(PurchasingOrder::find()->where(['id' => $id])->one());
	}

	//Скачивание списка техники
	public function actionDevicesListXls($id)
	{
		PurchasingOrder::createDevicesXls(PurchasingOrder::find()->where(['id' => $id])->one());
	}
	
	//Скачивание списка жгутов для заказа
	public function actionGetHarnessXls($id)
	{
		PurchasingOrder::createHarnessXls(PurchasingOrder::find()->where(['id' => $id])->one());
	}
	
	//Скачивание приложения к договору
	public function actionGetSupplyContract($id)
	{
		PurchasingOrder::createSupplyContract(PurchasingOrder::find()->where(['id' => $id])->one());
	}
	
	//Скачивание задания на установку
	public function actionGetInstallationTask($id)
	{
		PurchasingOrder::createInstallationTask(PurchasingOrder::find()->where(['id' => $id])->one());
	}	
	
	//Скачивание приложения к договору на установку
	public function actionGetInstallContract($id)
	{
		PurchasingOrder::createInstallContract(PurchasingOrder::find()->where(['id' => $id])->one());
	}	
		//Скачивание выгрузки на Мой склад с новой установки
	public function actionGetEquipmentDoc($id)
	{
		PurchasingOrder::createEquipmentMyStorage(PurchasingOrder::find()->where(['id' => $id])->one());
	}

		//Скачивание выгрузки на Мой склад с гаранатийной части
	public function actionGetEquipmentDocWarranty($id)
	{
		PurchasingOrder::createEquipmentMyStorageWarranty(PurchasingOrder::find()->where(['id' => $id])->one());
	}

		//Скачивание выгрузки на Мой склад с обеих установок
	public function actionGetEquipmentDocAll($id)
	{
		PurchasingOrder::createEquipmentMyStorageAll(PurchasingOrder::find()->where(['id' => $id])->one());
	}	

		//Отчет по гарантиям за месяц в Плане установок
	public function actionGetWarrantyMonthXls($month)
	{
		PurchasingOrder::createWarrantyMonthXls($month);
	}
	
	public function actionPrepareModels()
	{
		$devices = MainDevices::find()->all();
		foreach($devices as $item){
			$item->order_type = 0;
			$item->save();
		}
	}
	
    public function actionUpdate($id, $xls=false, $sort=false)
    {
		$parentOrders = PurchasingOrder::find()->select('id, name, client_id')->where(['order_parent' => -1, 'deleted' => 0])->all();
		$clients = Client::find()->all();
		$clientsById = [];
		foreach($clients as $item){
			$clientsById[$item->id] = $item->name;
		}
		
		$parents = [];
		$parents[-1] = '';
		foreach($parentOrders as $item){
			if(isset($clientsById[$item->client_id])){
				$parents[$item->id] = $clientsById[$item->client_id] . ' ' . $item->name;
			}else{
				$parents[$item->id] = $item->name;
			}
		}
		
		$files = FileHelper::findFiles('uploads/Blanks',['recursive'=>FALSE, 'only'=>['*.doc','*.docx']]);
		$model = $this->findModel($id);
		if($model->date_shipment){
			$bufDate = new \DateTime($model->date_shipment);
			$model->date_shipment = $bufDate->format('d.m.Y');
		}		
		$filesNormalized = [];
		foreach($files as $file){
			$file = FileHelper::normalizePath($file, '/');
			$buf = explode("/", $file);
			$filesNormalized[] = $buf[2];
		}
		if ($model->load(Yii::$app->request->post())) {

			if($_SERVER['SERVER_ADDR'] == '127.0.0.1')//Если не dev, то отладчик не работает
			{
				$date = new \DateTime($model->date_shipment);
				$model->date_shipment = $date->format('Y-m-d H:i:s');
				// $this->checkEvent($id, $model);
				// $oldOrder = PurchasingOrder::findOne($id);
				// $oldStatus = $oldOrder->agreement_installer;
				$model->save();
				// $this->createEvent($id, $oldStatus);
				if($model->date_shipment){
					$bufDate = new \DateTime($model->date_shipment);
					$model->date_shipment = $bufDate->format('d.m.Y');
				}				
			}
			else 
			{
				$date = new \DateTime($model->date_shipment);
				$model->date_shipment = $date->format('Y-m-d H:i:s');
				$this->checkShipmentEvent($id);//Проверка события отгрузки в календаре
				$this->checkEvent($id);//Проверка события установки в календаре			
				$model->save();
				$this->createShipmentEvent($id);//Создание события отгрузки в календаре
				$this->createEvent($id);//Создание события установки в календаре
				if($model->date_shipment){
					$bufDate = new \DateTime($model->date_shipment);
					$model->date_shipment = $bufDate->format('d.m.Y');
				}				
			}

			$serviceOrders = new ServiceOrder();
			
			if ($serviceOrders->load(Yii::$app->request->post())) {//Сохранение услуг
				$i = 0;
				$newIds = [];
				foreach($serviceOrders->ids as $item){
					if($item > 0){
						$serviceOrder = ServiceOrder::findOne($item);
						if(!$serviceOrder){
							$serviceOrder = new ServiceOrder();
						}
					}else{
						$serviceOrder = new ServiceOrder();
					}
					$amount = $serviceOrders->amounts[$i];
					$type = $serviceOrders->types[$i];
					$price = $serviceOrders->prices[$i];
					$owner = $serviceOrders->owners[$i];
					$totalPrice = $serviceOrders->totalPrices[$i];
					if($type!=0){
						$serviceOrder->type = $type;
						$serviceOrder->amount = $amount;
						$serviceOrder->price = $price;
						$serviceOrder->total_price = $totalPrice;
						$serviceOrder->order_id = $id;	
						$serviceOrder->owner_type = $owner;	
						$serviceOrder->save();
						$newIds[] = $serviceOrder->id;
					}
					$i++;
				}
				$serviceOrdersList = ServiceOrder::find()->where(['order_id' => $id])->all();				
				foreach($serviceOrdersList as $item){
					if(!in_array($item->id, $newIds)){
						$item->delete();
					}
				}
			}else{
				$serviceOrdersList = ServiceOrder::find()->where(['order_id' => $id])->all();
				if($serviceOrdersList){
					foreach($serviceOrdersList as $item){
						$item->delete();
					}
				}				
			}

		}

		$services = Service::getList();
		$serviceOrdersListNew = ServiceOrder::find()->where(['order_id' => $id, 'owner_type' => 'new'])->all();
		$serviceOrdersListWarranty = ServiceOrder::find()->where(['order_id' => $id, 'owner_type' => 'warranty'])->all();
		
		$devices = MainDevices::find()->where(['order_id' => $id])->orderBy('id DESC')->all();//Новые машины
		
		// $deviceWarranty = MainDevices::find()->where(['order_id' => $id, 'order_type' => 1])->orderBy('id DESC')->all();//Машины на гарантию
		
		$i = 0;
		$result = [];		
		if($devices){
			foreach($devices as $item)
			{
				$ownerType = $item->order_type;
				if($item->model_id!=NULL && $item->config_id != NULL && BrandsConfig::findOne($item->config_id))
				{
					$brand = Brands::find()->where(['id' => $item->model_id])->one();
					$config = BrandsConfig::findOne($item->config_id);					
					if($brand!=NULL){						
						if($item->model){
							if($brand->pseudonym){
								$pseudonyms = unserialize($brand->pseudonym);
								$pseudonymsArray = [];
								foreach($pseudonyms as $val){
									$buf = str_replace("\n", '', $val);
									$pseudonymsArray[trim($buf)] = $buf;
								}

								if((trim($item->model) != trim($brand->model)) && (!isset($pseudonymsArray[$item->model]))){
									$item->model = 'НЕ ВЫБРАНО, БЫЛО('.$item->model.')';
									$result['items'][$ownerType][$i]['red'] = true;
								}								
							}else{
								if(trim($item->model) != trim($brand->model)){
									$item->model = 'НЕ ВЫБРАНО, БЫЛО('.$item->model.')';
									$result['items'][$ownerType][$i]['red'] = true;
								}								
							}								
							$result['items'][$ownerType][$i]['brands'] = $brand->brand . ' ' . $item->model . ' ' . $brand->release_date;
						}
						else{
							$result['items'][$ownerType][$i]['brands'] = $brand->brand . ' ' . $brand->model . ' ' . $brand->release_date;
						}
						if($config){
							$result['items'][$ownerType][$i]['brands'] .= ' ' . $config->name;
						}
						$result['items'][$ownerType][$i]['brandId'] = $brand->id;
					}
				}
				else{
					if($item->brand){
						$result['items'][$ownerType][$i]['brands'] = $item->brand;
						if($item->model){
							$result['items'][$ownerType][$i]['brands'] .= ' '.$item->model.' (Конфигурация не выбрана)';
							$result['items'][$ownerType][$i]['red'] = true;
						}
						else{
							$result['items'][$ownerType][$i]['brands'] .= ' '.'(Конфигурация не выбрана)';
							$result['items'][$ownerType][$i]['red'] = true;
						}
					}
				}
				if($item->serial_number!=NULL){
					$result['items'][$ownerType][$i]['serial_number'] = $item->serial_number;
				}
				if($item->year!=NULL){
					$result['items'][$ownerType][$i]['year'] = $item->year;
				}else{
					$result['items'][$ownerType][$i]['year'] = '';
				}					
				if($item->garage_number!=NULL){
					$result['items'][$ownerType][$i]['garage_number'] = $item->garage_number;
				}
				if($item->configuration!=NULL){
					if(strlen($item->configuration) < 150){
						$result['items'][$ownerType][$i]['configuration'] = $item->configuration;
					}else{
						setlocale(LC_ALL, 'ru_RU.CP1251');//Для работы с русским
						$result['items'][$ownerType][$i]['configuration'] = substr(substr($item->configuration, 0, 150),0,-1) . '...';
					}
				}				
				$result['items'][$ownerType][$i]['id'] = $item->id;
				$result['items'][$ownerType][$i]['active'] = $item->active;
				$result['items'][$ownerType][$i]['photo'] = $item->photo;				
				$result['items'][$ownerType][$i]['warranty_result'] = strlen($item->warranty_result) > 0 ? 1 : 0;
				$i++;
			}
		}
		if($sort){
			ArrayHelper::multisort($result['items']['0'], 'brands', SORT_ASC);
			ArrayHelper::multisort($result['items']['1'], 'brands', SORT_ASC);
		}
		
		$configuration = BrandsConfig::find()->where(['owner_id' => $id, 'owner_type' => 'order'])->one();
		// echo '<pre>';
		// var_dump($configuration);
		// exit;
		$equipmentArrayWarranty = [];
		if($configuration){
			$equipmentArrayWarranty['config'] = $configuration->id;
			$equipmentList = unserialize($configuration->equipment_ids);
			if($equipmentList){
				foreach($equipmentList as $item){
					$equipment = Equipment::findOne($item[0]);
					$equipmentArrayWarranty['items'][] = [
						'type' => $equipment->type,
						'name' => $equipment->name,
						'count' => $item[1],
					];
				}
			}
		}else{
			$equipmentArrayWarranty['config'] = false;
		}

		$configuration = BrandsConfig::find()->where(['owner_id' => $id, 'owner_type' => 'order_new'])->one();
		$equipmentArrayNew = [];
		if($configuration){
			$equipmentArrayNew['config'] = $configuration->id;
			$equipmentList = unserialize($configuration->equipment_ids);
			if($equipmentList){
				foreach($equipmentList as $item){
					$equipment = Equipment::findOne($item[0]);
					$equipmentArrayNew['items'][] = [
						'type' => $equipment->type,
						'name' => $equipment->name,
						'count' => $item[1],
					];
				}
			}
		}else{
			$equipmentArrayNew['config'] = false;
		}		
		
		return $this->render('update', [
			'equipmentArrayWarranty' => $equipmentArrayWarranty,
			'equipmentArrayNew' => $equipmentArrayNew,
			'model' => $model, 
			'cardTypes' => $model::getCardsTypes(), 			
			'result' => $result, 
			'operators' => $model::getOperatorsTypes(), 
			'files' => $filesNormalized,			
			'clients' => Client::getAllClients(),
			'sort' => $sort,
			'services' => $services,
			'serviceOrdersListNew' => $serviceOrdersListNew,
			'serviceOrdersListWarranty' => $serviceOrdersListWarranty,
			'parents' => $parents,
			'priceList' => Json::encode(Service::getPriceList()),
		]);
    }

    public function actionDelete($id)
    {
		$model = $this->findModel($id);
		if($model){
			$model->deleted = 1;
			$model->save();
		}
		return $this->redirect(['index']);
    }
	
	public function actionAddEvents()
	{
		$orders = PurchasingOrder::find()->where(['and', ['deleted' => 0], ['!=', 'date', 'Invalid date'], ['!=', 'date', ''], ['!=', 'date', 'NULL']])->all();
		foreach($orders as $item){
				$this->checkEvent($item->id);
				$this->createEvent($item->id);
		}
	}
	
	/**
	* Поиск модели по id
	*/
	protected function findModel($id)
    {
        if (($model = PurchasingOrder::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

	private function checkShipmentEvent($id)
	{
		$order = PurchasingOrder::findOne($id);
		if($order->date_shipment && $order->shipment_event_id){
			$calendarId = '52a8m3ramlt94hhs5ftj4d5b6s@group.calendar.google.com';

			if(!defined('APPLICATION_NAME')){
				define('APPLICATION_NAME', 'Google Calendar API PHP Quickstart');
				define('CREDENTIALS_PATH', __DIR__ .'/.credentials/calendar-php-quickstart.json');
				define('CLIENT_SECRET_PATH', __DIR__ . '/client_secret.json');
				// If modifying these scopes, delete your previously saved credentials
				// at ~/.credentials/calendar-php-quickstart.json
				define('SCOPES', implode(' ', array(
					Google_Service_Calendar::CALENDAR)
				));	
			}
			// Get the API client and construct the service object.
			$client = GoogleApi::getClient();
			$service = new Google_Service_Calendar($client);				
			try{
				// First retrieve the event from the API.
				$service->events->delete($calendarId, $order->shipment_event_id);
			} 
			//Перехватываем (catch) исключение, если что-то идет не так.
			catch (Google_Service_Exception $ex) {				
				return;
			}		
		}
	}	

	private function createShipmentEvent($id)
	{		
		$order = PurchasingOrder::findOne($id);
		$calendarId = '52a8m3ramlt94hhs5ftj4d5b6s@group.calendar.google.com';		
		$client = Client::findOne($order->client_id);
		$name = '';
		
		$devices = MainDevices::find()->where(['order_id' => $id])->all();
		$count = count($devices);
		//$name .= ' (' . $count . ')'; 		
		if($client){
			$name = $order->name . ' (' . $count . ') ' . $client->name;
		}else{
			$name = $order->name . ' (' . $count . ') ';
		}
		if($order->date_shipment && $order->installer_agreement){
			$date = new \DateTime($order->date_shipment);
		
			if(!defined('APPLICATION_NAME')){
				define('APPLICATION_NAME', 'Google Calendar API PHP Quickstart');
				define('CREDENTIALS_PATH', __DIR__ .'/.credentials/calendar-php-quickstart.json');
				define('CLIENT_SECRET_PATH', __DIR__ . '/client_secret.json');
				// If modifying these scopes, delete your previously saved credentials
				// at ~/.credentials/calendar-php-quickstart.json
				define('SCOPES', implode(' ', array(
					Google_Service_Calendar::CALENDAR)
				));	
			}
			
			// Get the API client and construct the service object.
			$client = GoogleApi::getClient();
	
			$service = new Google_Service_Calendar($client);
			
			$event = new Google_Service_Calendar_Event(array(
				'summary' => $name,
				'start' => array(
					'date' => $date->format('Y-m-d'),
				),
				'end' => array(
					'date' => $date->format('Y-m-d'),
				),
				'colorId' => '9',
			));

			$event = $service->events->insert($calendarId, $event);			
			$order->shipment_event_id = $event->id;
			$order->save();

			
		}else{
			return;
		}			
	}	
	
	private function checkEvent($id)
	{
		$order = PurchasingOrder::findOne($id);
		if($order->event_id){
			if($order->installer_agreement && !$order->client_agreement){//Если с установщиком, но НЕ с клиентом
				$calendarId = '73dd0npqji4plp03p051ufcdeg@group.calendar.google.com';
			}elseif($order->installer_agreement && $order->client_agreement){//Если с установщиком И с клиентом
				$calendarId = 'beg2j3n7gh2pvnsr98k8fkfsls@group.calendar.google.com';
			}else{
				return;
			}
			
			if(!defined('APPLICATION_NAME')){
				define('APPLICATION_NAME', 'Google Calendar API PHP Quickstart');
				define('CREDENTIALS_PATH', __DIR__ .'/.credentials/calendar-php-quickstart.json');
				define('CLIENT_SECRET_PATH', __DIR__ . '/client_secret.json');
				// If modifying these scopes, delete your previously saved credentials
				// at ~/.credentials/calendar-php-quickstart.json
				define('SCOPES', implode(' ', array(
					Google_Service_Calendar::CALENDAR)
				));	
			}
			// Get the API client and construct the service object.
			$client = GoogleApi::getClient();
			$service = new Google_Service_Calendar($client);				
			try{
				// var_dump('Удаляю');
				// exit;				
				// First retrieve the event from the API.
				$service->events->delete($calendarId, $order->event_id);
				$order->event_id = '';
				$order->save();
			} 
			//Перехватываем (catch) исключение, если что-то идет не так.
			catch (Google_Service_Exception $ex) {				
				return;
			}		
		}
	}
	
	private function createEvent($id, $goHome = true)
	{				
		$order = PurchasingOrder::findOne($id);
		
	
		$colorId = 10;
		$calendarId = '';
		if($order->installer_agreement && !$order->client_agreement){//Если с установщиком, но НЕ с клиентом
			$calendarId = '73dd0npqji4plp03p051ufcdeg@group.calendar.google.com';
			$colorId = 11;
		}elseif($order->installer_agreement && $order->client_agreement){//Если с установщиком И с клиентом
			$calendarId = 'beg2j3n7gh2pvnsr98k8fkfsls@group.calendar.google.com';
		}else{
			return;
		}
		
		$client = Client::findOne($order->client_id);
		$name = '';
		$devices = MainDevices::find()->where(['order_id' => $id, 'order_type' => 0])->all();
		$devicesW = MainDevices::find()->where(['order_id' => $id, 'order_type' => 1])->all();
		$count0 = count($devices);
		$countW = count($devicesW);
		$count = $count0 . '/' . $countW;//TODO: Посчитать количество оборудования
		//$name .= ' (' . $count . ')'; 		
		if($client){
			$name = $order->name . ' (' . $count . ') ' . $client->name;
		}else{
			$name = $order->name;
		}
		if($order->contractor_comment){
			$name .= ' (' . $order->contractor_comment . ')';
		}
		$div = floor($count/12);
		$mod = $count%12;
		if($mod > 0){
			$div++;
		}
		$date = explode('.', $order->date);
		if(count($date) < 3){
			$date = explode('-', $order->date);
		}
		if(count($date) < 3){
			Yii::$app->getSession()->setFlash('error', 'Не выбрана дата');
			Yii::$app->request->referrer ? $this->redirect(Yii::$app->request->referrer) : $this->goHome();
			return;
		}				
		$dayStart = new \DateTime($date[0].'-'.$date[1].'-'.$date[2]);
		$dayEnd = new \DateTime($date[0].'-'.$date[1].'-'.$date[2]);
		$dayEnd->add(new \DateInterval('P'.$div.'D'));
		
		if(!defined('APPLICATION_NAME')){
			define('APPLICATION_NAME', 'Google Calendar API PHP Quickstart');
			define('CREDENTIALS_PATH', __DIR__ .'/.credentials/calendar-php-quickstart.json');
			define('CLIENT_SECRET_PATH', __DIR__ . '/client_secret.json');
			// If modifying these scopes, delete your previously saved credentials
			// at ~/.credentials/calendar-php-quickstart.json
			define('SCOPES', implode(' ', array(
				Google_Service_Calendar::CALENDAR)
			));	
		}
		
		// Get the API client and construct the service object.
		$client = GoogleApi::getClient();

		$service = new Google_Service_Calendar($client);
		
		$event = new Google_Service_Calendar_Event(array(
			'summary' => $name,
			'start' => array(
				'date' => $dayStart->format('Y-m-d'),
			),
			'end' => array(
				'date' => $dayEnd->format('Y-m-d'),
			),
			'colorId' => $colorId,
		));
		
		//$calendarId = '73dd0npqji4plp03p051ufcdeg@group.calendar.google.com';
		$event = $service->events->insert($calendarId, $event);
		// echo '<pre>';
		// var_dump($event);
		// exit;
		$order->event_id = $event->id;
		$order->save();
		if($goHome){
			Yii::$app->getSession()->setFlash('success', 'Событие добавлено');
			Yii::$app->request->referrer ? $this->redirect(Yii::$app->request->referrer) : $this->goHome();		
		}
	}

}
