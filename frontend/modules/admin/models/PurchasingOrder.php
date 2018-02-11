<?php

namespace app\modules\admin\models;

use yii\helpers\ArrayHelper;
use frontend\models\helpers\XlsReport;
use frontend\models\MainDevices;
use frontend\models\Brands;
use frontend\models\Equipment;
use PHPExcel_Cell;
use PHPExcel_Worksheet_PageSetup;
use yii\helpers\FileHelper;
use frontend\models\BrandsConfig;
use app\modules\admin\models\Client;
use app\modules\admin\models\InstallationStage;
use app\modules\support\models\Images;
use app\modules\admin\models\ServiceOrder;
use app\modules\admin\models\Service;
use Arhitector\Yandex\Disk;
use Arhitector\Yandex\Disk\Resource\Closed;
use Arhitector\Yandex\Client\Exception\ForbiddenException;
use Arhitector\Yandex\Client\Exception\NotFoundException;
use Arhitector\Yandex\Client\Exception\UnauthorizedException;

class PurchasingOrder extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'purchasing_order';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['contract_status', 'responsible', 'devices_list_status', 'equipment_prod_status', 'harness_prod_status', 'account_status', 'installer_agreement', 'client_agreement', 'installed', 'installed_comment', 'name', 'city', 'order_parent', 'blank', 'shipment_event_id', 'contractor_comment', 'event_id', 'date_shipment', 'client_id', 'customer', 'client_account', 'contacts','card_type','wifi','pocket','date','agreement_installer','agreement_client','production', 'scan_contract', 'harness_production', 'KPP', 'name_controller', 'engagement', 'name_defendant', 'phone_defendant', 'mail_defendant', 'gsm_operator', 'deleted', 'comments', 'contractor'],'safe'],
			[['name'], 'required',  'message'=>'Поле не может быть пустым.'],
			[['comments_new_order', 'comments_warranty'], 'string', 'max' => 2048],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'name' => 'Название заявки',
            'contacts' => 'Адрес объекта',
			'blank' => 'Бланк договора',
            'card_type' => 'Тип карты',
            'wifi' => 'Wi-Fi',
			'pocket' => 'Карман',
			'date' => 'Дата установки',
			'scan_contract' => 'Наличие скана договора',			
			'KPP' => 'КПП',
			'name_controller' => 'ФИО контроллера',
			'engagement' => 'В лице + на основе №, дата доверенности',
			'name_defendant' => 'ФИО ответственного',
			'phone_defendant' => 'Телефон ответственного',
			'mail_defendant' => 'E-mail ответственного',
			'gsm_operator' => 'GSM оператор',
			'customer' => 'Покупатель',
			'client_id' => 'Название клиента',
			'date_shipment' => 'Дата отгрузки',
			'comments' => 'Комментарии',
			'contractor' => 'Установщик',
			'contractor_comment' => 'Исполнитель',
			'order_parent' => 'Родительская заявка',
			'comments_new_order' => 'Комментарии к новой установке',
			'comments_warranty' => 'Комментарии к гарантии',
			'city' => 'Местоположение объекта',
			'responsible' => 'Ответственный',
			
			//TODO:Удалить
			'agreement_client' => '1) Согласование с клиентом',
			'production' => '2) Стадия производства оборудования',
			'harness_production' => '3) Стадия производства жгутов',
			'client_account' => '4) Клиентский аккаунт',
			'agreement_installer' => '5) Согласование сроков с установщиком',
			//------------
			
			'installer_agreement' => 'Согласовано с установщиком',
			'client_agreement' => 'Согласовано с клиентом',
			
			'installed' => 'Документы',
			'installed_comment' => 'Комментарий по установке',

			'contract_status' => '1) Договор',
			'devices_list_status' => '2) Список техники',
			'equipment_prod_status' => '3) Производство оборудования',
			'harness_prod_status' => '4) Производство жгутов',
			'account_status' => '5) Аккаунт',			
        ];
    }

	public static function getMonthsString()
	{
		return [
			'01' => 'Январь',
			'02' => 'Февраль',
			'03' => 'Март',
			'04' => 'Апрель',
			'05' => 'Май',
			'06' => 'Июнь',
			'07' => 'Июль',
			'08' => 'Август',
			'09' => 'Сентябрь',
			'10' => 'Октябрь',
			'11' => 'Ноябрь',
			'12' => 'Декабрь',			
		];
	}
	
	public static function getDb()
    {
        // use the "db_main" application component
        return \Yii::$app->db_main;
    }
	
	public static function getCardsTypes()
	{
		return [1 => 'HID', 2 => 'Em-marin', 3 => 'Indala'];
	}
	
	public static function getOperatorsTypes()
	{
		return [0 => 'Без GSM', 1 => 'Билайн', 2 => 'МТС', 3 => 'Мегафон'];
	}

	public static function getContractors()
	{
		return [1 => 'Внутренний', 2 => 'Внешний'];
	}

	public static function getContractorsFilter()
	{
		return [['id' => 1, 'name' => 'Внутренний'], ['id' => 2, 'name' => 'Внешний']];
	}
	
	public static function responsiblePersonsLong()
	{
		return [0 => '-', 10 => 'Рита', 20 => 'Александр', 30 => 'Максим Ч.', 40 => 'Слава', 50 => 'Максим П.', 60 => 'Олег', 70 => 'Сергей Г.'];
	}

	public static function responsiblePersonsShort()
	{
		return [0 => '-', 10 => 'Р', 20 => 'А', 30 => 'М', 40 => 'С'];
	}
	
	/**
	 * Новые статусы
	 */
	public static function contractStatuses()
	{
		return [0 => '-', 10 => 'Подписан', 20 => 'Гарантия'];
	}
	
	public static function devicesListStatuses()
	{
		return [0 => 'Создан', 10 => 'Проверен'];
	}

	public static function equipmentProductionStatuses()
	{
		return [0 => '-', 10 => 'На закуп', 20 => 'На подбор', 30 => 'Готово'];
	}

	public static function harnessProductionStatuses()
	{
		return [0 => '-', 10 => 'Заказали', 20 => 'Готово', 30 => 'Не нужны'];
	}

	public static function accountStatuses()
	{
		return [0 => '-', 10 => 'Создан', 20 => 'Не нужен'];
	}
	
	
	/**
	* Создать папки для фото на Яндекс Диске
	*/
	public static function createYandexDiskFolders($id)
	{
		try{
			$folderPostFixes = [
				0 => '',
				1 => '_Гарантия'
			];
			
			$order = PurchasingOrder::findOne($id);
			
			$client = Client::findOne($order->client_id);
			
			$installers = explode("\n", $order->contractor_comment);
			
			//Массив с именами установков
			foreach($installers as $key=>$item){
				$installers[$key] = trim($item);
				if(!$installers[$key]){
					unset($installers[$key]);
				}
			}
				
			
			if($client && $installers){				
				$disk = new Disk('AQAEA7qiCP0hAATM1QG4cIPjOEfwtKAlxstxFgo');//Авторизация
	
				$devices = MainDevices::find()->where(['order_id' => $order->id, 'active' => 1])->all();
				
				$i = 0;
				$result = [];	
				$result['items'] = [];
				//Массив с техникой
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
						if($item->garage_number!=NULL){
							$result['items'][$ownerType][$i]['garage_number'] = $item->garage_number;
						}
						if($item->configuration!=NULL){
							if(strlen($item->configuration) < 150){
								$result['items'][$ownerType][$i]['configuration'] = $item->configuration;
							}else{
								setlocale(LC_ALL, 'ru_RU.CP1251');//Для работы с русским
								$result['items'][$ownerType][$i]['configuration'] = mb_substr(mb_substr($item->configuration, 0, 150),0,-1) . '...';
							}
						}				
						$i++;
					}
				}
	
				//Название директорий с заявками
				$orderFolderNames = [];
				foreach(array_keys($result['items']) as $type){
					$orderFolderNames[$type] = $client->name.' '.$order->name.$folderPostFixes[$type];
				}
							
				foreach($installers as $installer){
					$installerFolder = $disk->getResource('Установки'.'/'.$installer);
					if(!$installerFolder->has()){
						$installerFolder->create();
					}
					
					foreach($orderFolderNames as $orderFolderName){
						$orderFolder = $disk->getResource('Установки'.'/'.$installer.'/'.$orderFolderName);
						if(!$orderFolder->has()){
							$orderFolder->create();
						}
					}					
					foreach($result['items'] as $orderType=>$devices){
						foreach($devices as $device){
							$deviceFullName = '';
							
							if(isset($device['garage_number'])){
								$deviceFullName .= $device['garage_number'] . ' ';
							}
							
							if(isset($device['serial_number'])){
								$deviceFullName .= $device['serial_number'] . ' ';
							}				
							
							$deviceFullName .= $device['brands'];
							
							$deviceFolder = $disk->getResource('Установки'.'/'.$installer.'/'.$orderFolderNames[$orderType].'/'.$deviceFullName);
							if(!$deviceFolder->has()){
								$deviceFolder->create();
							}
						}
					}
				}	
				return 'Папки успешно созданы';
			}else{
				return 'Не выбран клиент или установщики';
			}
		}
		//Возникает если с этой папкой уже ведется работа
		catch(ForbiddenException $exc)
		{
			return 'Ресурс заблокирован. Возможно, над ним выполняется другая операция.';
		}
		//Если во время создания директорий кто то удалит родительскую директорию
		catch(NotFoundException $exc)
		{
			return 'Ресурс занят. Повторите попытку.';
		}
		//Не авторизован
		catch(UnauthorizedException $exc)
		{
			return 'Не авторизован.';
		}
	}
	

	//TODO::Проверить конфликт
	public static function findOrdersWithClients()
	{
		$ordersSelect = [];
		$orders = PurchasingOrder::find()
			->where([
				'and',
				['deleted' => 0], 
				['or', 
					['installed' => 0], 
					['installed' => NULL]
				],
			])
			->asArray()
			->all();			

		$ordersSelect = ['0' => 'Выберите заявку'];
		$ordersSelect += ArrayHelper::map($orders, 'id', 'name');
		$clients = ArrayHelper::map(Client::find()->all(), 'id', 'name');
		
		foreach($orders as $order){
			if($order['client_id'] && isset($clients[$order['client_id']])){
				$ordersSelect[$order['id']] = $clients[$order['client_id']].': '.$order['name'];
			}
		}

		return $ordersSelect;
	}
	
	//Xls для выгрузки в мой склад оборудования по гарантии
	public static function createEquipmentMyStorageWarranty($order)
	{
		$mainDevices = MainDevices::find()->where(['order_id' => $order->id, 'order_type' => 1, 'active' => 1])->all();
		$configs = [];
		foreach($mainDevices as $item){
			if($item->order_type == 1){
				$configuration = BrandsConfig::find()->where(['owner_id' => $item->id, 'owner_type' => 'configuration'])->one();					
			}else{			
				$configuration = BrandsConfig::find()->where(['id' => $item->config_id, 'owner_type' => 'configuration'])->one();
			}			
			if($configuration && $item->model_id){
				$configs[] = $configuration;
			}
		}

		$configuration = BrandsConfig::find()->where(['owner_id' => $order->id, 'owner_type' => 'order'])->one();
		if($configuration){
			$configs[] = $configuration;
		}
		$equipmentResult = [];
		$equipmentMap = [];
		foreach($configs as $item){
			$equips = unserialize($item->equipment_ids);
			foreach($equips as $eq){
				if(!isset($equipmentMap[$eq[0]])){
					$equipmentMap[$eq[0]]['count'] = $eq[1];
				}else{
					$equipmentMap[$eq[0]]['count'] += $eq[1];
				}
			}
		}
		$equipment = Equipment::find()->where(['id' => array_keys($equipmentMap)])->all();
		$equipmentById = [];
		foreach($equipment as $item){
			$equipmentById[$item->id] = ['external_id' => $item->external_id];
		}
		foreach($equipmentMap as $id=>$item){
			$extId = $equipmentById[$id]['external_id'];
			if($extId == ''){
				$extId = 'Код не назначен';
			}
			if(!isset($equipmentResult[$extId])){
				$equipmentResult[$extId] = $item['count'];
			}else{
				$equipmentResult[$extId] += $item['count'];
			}
		}
		
		$xls = new XlsReport;
		$client = Client::findOne($order->client_id);
		if($client){			
			$xls->fileName = $client->name . ' ' .$order->name.' (Мой склад)';
		}else{
			$xls->fileName = $order->name.' (Мой склад)';
		}
		$sheet = $xls->getSheet();
		$sheet->setCellValue('A'.'1', 'Код');
		$sheet->setCellValue('B'.'1', 'Количество');
		$sheet->getColumnDimension(PHPExcel_Cell::stringFromColumnIndex(0))->setWidth(20);
		$sheet->getColumnDimension(PHPExcel_Cell::stringFromColumnIndex(1))->setWidth(20);		

		
		$row = 2;
		foreach($equipmentResult as $key=>$item){
			$sheet->setCellValueByColumnAndRow(0, $row, $key);
			$sheet->setCellValueByColumnAndRow(1, $row, $item);
			$row++;
		}

		$sheet->getStyle('A1:'.'B'.($row-1))->applyFromArray($xls->styleBorders);
		$sheet->getStyle('A1'.':'.'B'.($row-1))->getAlignment()->setWrapText(true);		
		$xls->sendXlsReport();
	}

	//Xls для выгрузки в мой склад оборудования по обеим установкам
	public static function createEquipmentMyStorageAll($order)
	{
		$mainDevices = MainDevices::find()->where(['order_id' => $order->id, 'active' => 1])->all();

		$configs = [];
		//Сбор конфигурация с машин
		foreach($mainDevices as $item){
			if($item->order_type == 1){
				$configuration = BrandsConfig::find()->where(['owner_id' => $item->id, 'owner_type' => 'configuration'])->one();					
			}else{			
				$configuration = BrandsConfig::find()->where(['id' => $item->config_id, 'owner_type' => 'configuration'])->one();
			}				
			if($configuration && $item->model_id){
				$configs[] = $configuration;
			}
		}

		$configuration = BrandsConfig::find()->where(['owner_id' => $order->id, 'owner_type' => 'order'])->one();//добавление списка оборудования с гарантии
		if($configuration){
			$configs[] = $configuration;
		}
		$configuration = BrandsConfig::find()->where(['owner_id' => $order->id, 'owner_type' => 'order_new'])->one();//добавление списка оборудования с новой установки
		if($configuration){
			$configs[] = $configuration;
		}		
		
		$equipmentResult = [];
		$equipmentMap = [];
		foreach($configs as $item){
			$equips = unserialize($item->equipment_ids);
			foreach($equips as $eq){
				if(!isset($equipmentMap[$eq[0]])){
					$equipmentMap[$eq[0]]['count'] = $eq[1];
				}else{
					$equipmentMap[$eq[0]]['count'] += $eq[1];
				}
			}
		}
		$equipment = Equipment::find()->where(['id' => array_keys($equipmentMap)])->all();
		$equipmentById = [];
		foreach($equipment as $item){
			$equipmentById[$item->id] = ['external_id' => $item->external_id];
		}
		foreach($equipmentMap as $id=>$item){
			$extId = $equipmentById[$id]['external_id'];
			if($extId == ''){
				$extId = 'Код не назначен';
			}
			if(!isset($equipmentResult[$extId])){
				$equipmentResult[$extId] = $item['count'];
			}else{
				$equipmentResult[$extId] += $item['count'];
			}
		}
		
		$xls = new XlsReport;
		$client = Client::findOne($order->client_id);
		if($client){			
			$xls->fileName = $client->name . ' ' .$order->name.' (Мой склад)';
		}else{
			$xls->fileName = $order->name.' (Мой склад)';
		}
		$sheet = $xls->getSheet();
		$sheet->setCellValue('A'.'1', 'Код');
		$sheet->setCellValue('B'.'1', 'Количество');
		$sheet->getColumnDimension(PHPExcel_Cell::stringFromColumnIndex(0))->setWidth(20);
		$sheet->getColumnDimension(PHPExcel_Cell::stringFromColumnIndex(1))->setWidth(20);		

		
		$row = 2;
		foreach($equipmentResult as $key=>$item){
			$sheet->setCellValueByColumnAndRow(0, $row, $key);
			$sheet->setCellValueByColumnAndRow(1, $row, $item);
			$row++;
		}

		$sheet->getStyle('A1:'.'B'.($row-1))->applyFromArray($xls->styleBorders);
		$sheet->getStyle('A1'.':'.'B'.($row-1))->getAlignment()->setWrapText(true);		
		$xls->sendXlsReport();
	}
	
	//Xls для выгрузки в мой склад с новой устанвки
	public static function createEquipmentMyStorage($order)
	{
		$mainDevices = MainDevices::find()->where(['order_id' => $order->id, 'order_type' => 0, 'active' => 1])->all();

		$configs = [];
		foreach($mainDevices as $item){
			if($item->order_type == 1){
				$configuration = BrandsConfig::find()->where(['owner_id' => $item->id, 'owner_type' => 'configuration'])->one();					
			}else{			
				$configuration = BrandsConfig::find()->where(['id' => $item->config_id, 'owner_type' => 'configuration'])->one();
			}			
			if($configuration && $item->model_id){
				$configs[] = $configuration;
			}
		}

		$configuration = BrandsConfig::find()->where(['owner_id' => $order->id, 'owner_type' => 'order_new'])->one();
		if($configuration){
			$configs[] = $configuration;
		}
		$equipmentResult = [];
		$equipmentMap = [];
		foreach($configs as $item){
			$equips = unserialize($item->equipment_ids);
			foreach($equips as $eq){
				if(!isset($equipmentMap[$eq[0]])){
					$equipmentMap[$eq[0]]['count'] = $eq[1];
				}else{
					$equipmentMap[$eq[0]]['count'] += $eq[1];
				}
			}
		}
		$equipment = Equipment::find()->where(['id' => array_keys($equipmentMap)])->all();
		$equipmentById = [];
		foreach($equipment as $item){
			$equipmentById[$item->id] = ['external_id' => $item->external_id];
		}
		foreach($equipmentMap as $id=>$item){
			$extId = $equipmentById[$id]['external_id'];
			if($extId == ''){
				$extId = 'Код не назначен';
			}
			if(!isset($equipmentResult[$extId])){
				$equipmentResult[$extId] = $item['count'];
			}else{
				$equipmentResult[$extId] += $item['count'];
			}
		}
		
		$xls = new XlsReport;
		$client = Client::findOne($order->client_id);
		if($client){			
			$xls->fileName = $client->name . ' ' .$order->name.' (Мой склад)';
		}else{
			$xls->fileName = $order->name.' (Мой склад)';
		}
		$sheet = $xls->getSheet();
		$sheet->setCellValue('A'.'1', 'Код');
		$sheet->setCellValue('B'.'1', 'Количество');
		$sheet->getColumnDimension(PHPExcel_Cell::stringFromColumnIndex(0))->setWidth(20);
		$sheet->getColumnDimension(PHPExcel_Cell::stringFromColumnIndex(1))->setWidth(20);		

		
		$row = 2;
		foreach($equipmentResult as $key=>$item){
			$sheet->setCellValueByColumnAndRow(0, $row, $key);
			$sheet->setCellValueByColumnAndRow(1, $row, $item);
			$row++;
		}

		$sheet->getStyle('A1:'.'B'.($row-1))->applyFromArray($xls->styleBorders);
		$sheet->getStyle('A1'.':'.'B'.($row-1))->getAlignment()->setWrapText(true);		
		$xls->sendXlsReport();		
	}
	
	//Создание xls файла со списком жгутов
	public static function createHarnessXls($order)
	{
		$mainDevices = MainDevices::find()->where(['order_id' => $order->id, 'active' => 1])->all();
		$brands = [];
		foreach($mainDevices as $item)
		{
			if(Brands::find()->where(['id' => $item->model_id])->one()){
				$brands[] = Brands::find()->where(['id' => $item->model_id])->one();
			}
		}
		//---------------------ОСНОВНЫЕ ЗАГОЛОВКИ----------------------
		$xls = new XlsReport;
		$date = new \DateTime();
		$client = Client::findOne($order->client_id);
		if($client){
			$xls->fileName = 'Заказ жгутов ' . $date->format('d.m.Y') . ' ' . $client->name . ' ' . $order->name;
		}else{
			$xls->fileName = 'Заказ жгутов '.$date->format('d.m.Y').' '.$order->name;
		}
		$sheet = $xls->getSheet();
		$sheet->setTitle('Заказ оборудования');		
		$sheet->mergeCells('A'.'1'.':B'.'2');
		$sheet->mergeCells('C'.'1'.':D'.'1');
		$sheet->mergeCells('C'.'2'.':D'.'2');
		$sheet->setCellValue('C'.'1', 'Срок производства');
		$shipment = '-';
		if($order->date_shipment){
			$shipmentDate = new \DateTime($order->date_shipment);
			$shipmentDate->sub(new \DateInterval('P2D'));
			$shipment = $shipmentDate->format('d.m.Y');
		}
		$sheet->setCellValue('C'.'2', $shipment);
		if($client){
			$sheet->setCellValue('A'.'1', $client->name . ' ' . $order->name);
		}else{
			$sheet->setCellValue('A'.'1', $order->name);
		}		
				
		$sheet->mergeCells('A'.'3'.':A'.'5');
		$sheet->setCellValue('A'.'3', 'Марка');
		$sheet->getColumnDimension(PHPExcel_Cell::stringFromColumnIndex(0))->setWidth(15);
		$sheet->mergeCells('B'.'3'.':B'.'5');
		$sheet->setCellValue('B'.'3', 'Модель');
		$sheet->getColumnDimension(PHPExcel_Cell::stringFromColumnIndex(1))->setWidth(15);
		$sheet->mergeCells('C'.'3'.':C'.'5');
		$sheet->setCellValue('C'.'3', 'Год');
		$sheet->getColumnDimension(PHPExcel_Cell::stringFromColumnIndex(2))->setWidth(12);
		$sheet->mergeCells('D'.'3'.':D'.'5');
		$sheet->setCellValue('D'.'3', 'Серия');
		$sheet->getColumnDimension(PHPExcel_Cell::stringFromColumnIndex(3))->setWidth(12);
		$sheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
		//------------------------------------------------------------
		
		if(!$mainDevices)//Если нет техники, то возращает пустой документ
		{
			$xls->sendXlsReport();
			return;
		}
		$column = 0;//Начальная колонка
		$row = 6;
		$equipmentIds = [];
		$equipmentList = [];
		$brandsArray = [];
		foreach($brands as $item)
		{			
				if(!isset($brandsArray[$item->id])){
					$sheet->setCellValueByColumnAndRow($column, $row, $item->brand);
					$sheet->setCellValueByColumnAndRow($column + 1, $row, $item->model);
					$sheet->setCellValueByColumnAndRow($column + 2, $row, $item->release_date);
					$sheet->setCellValueByColumnAndRow($column + 3, $row, $item->series);
					$brandsArray[$item->id] = $row;//Запоминание строки, где находится модель
					$row++;
				}
		}
		$equipmentIds = [];
		foreach($mainDevices as $item){
			
			if($item->config_id){

				if($item->order_type == 1){
					$configuration = BrandsConfig::find()->where(['owner_id' => $item->id, 'owner_type' => 'configuration'])->one();					
				}else{			
					$configuration = BrandsConfig::find()->where(['id' => $item->config_id, 'owner_type' => 'configuration'])->one();
				}
				if($configuration){
					$bufIds = array_column(unserialize($configuration->equipment_ids), 0);
					
					foreach($bufIds as $id)
					{
						if(!in_array($id, $equipmentIds))
						{
							$equipmentIds[] = $id;//Формирование массива Id оборудования
						}
					}
				}
				//$equipmentList += Equipment::find()->where(['in', 'id', $equipmentIds])->all();//Список объектов оборудования
								
			}				
		}
		$equipmentList = Equipment::find()->where(['in', 'id', $equipmentIds])->all();//Список объектов оборудования

		$lastRow = $row;//Последняя строка
		$column = 4;
		//----------------------------------------------------КОЛИЧЕСТВО ТЕХНИКИ------------------------------------------------------
		$cellRange = PHPExcel_Cell::stringFromColumnIndex($column) . (1) . ':' . PHPExcel_Cell::stringFromColumnIndex($column) . (2);
		$sheet->mergeCells($cellRange);
		$cellRange = PHPExcel_Cell::stringFromColumnIndex($column) . (3) . ':' . PHPExcel_Cell::stringFromColumnIndex($column) . (4);
		$sheet->mergeCells($cellRange);			
		$sheet->setCellValueByColumnAndRow($column, 1, 'Кол-во');
		$sheet->getColumnDimension(PHPExcel_Cell::stringFromColumnIndex($column))->setWidth(6);
				$j = 0;
				foreach($brands as $item)
				{
					
						$row = $brandsArray[$item->id];
						$val = $sheet->getCellByColumnAndRow($column, $row)->getValue();
						if($val){
							$sheet->setCellValueByColumnAndRow($column, $row, 1 + $val);
						}
						else{
							$sheet->setCellValueByColumnAndRow($column, $row, 1);
						}
						$j++;
					
				}
				$sheet->setCellValueByColumnAndRow($column, 5, $j);		
		//-----------------------------------------------------------------------------------------------------------------------------
		$column = 5;
		$row = 3;
		$type = 1;
		$equipmentArray = [];
		
		do {
			$i = 0;
			foreach($equipmentList as $item)
			{
				if($item->type == $type)
				{
					$equipmentArray[$item->id] = $column;
					$cellRange = PHPExcel_Cell::stringFromColumnIndex($column) . $row . ':' . PHPExcel_Cell::stringFromColumnIndex($column) . ($row + 1);
					//$sheet->getColumnDimension(PHPExcel_Cell::stringFromColumnIndex($column))->setAutoSize(true);//->setWidth(4);
					$sheet->mergeCells($cellRange);
					$sheet->setCellValueByColumnAndRow($column, $row, $item->name);
					$sheet->getColumnDimension(PHPExcel_Cell::stringFromColumnIndex($column))->setWidth(3);
					$column++;
					$i++;
				}
			}			
			$textType = Brands::getEquipmentTypes()[$type];
			$bufI = $i > 0 ? $i : 1;
			$cellRange = PHPExcel_Cell::stringFromColumnIndex($column-$bufI) . (1) . ':' . PHPExcel_Cell::stringFromColumnIndex($column-1) . (2);
			$sheet->mergeCells($cellRange);
			$sheet->setCellValueByColumnAndRow($column - $i, 1, $textType);	
			//$sheet->getColumnDimension(PHPExcel_Cell::stringFromColumnIndex($column - $i))->setAutoSize(true);
			$type ++;
		} while ($type < 5);//Все типы оборудования в шапку		
		foreach($equipmentList as $itemEquipment)
		{
			$equipCount = 0;			
			foreach($mainDevices as $device)
			{
				if($device->model_id && $device->config_id){
					$item = Brands::find()->where(['id' => $device->model_id])->one();
					// $config = BrandsConfig::find()->where(['id' => $device->config_id])->one();
					if($device->order_type == 1){
						$config = BrandsConfig::find()->where(['owner_id' => $device->id, 'owner_type' => 'configuration'])->one();
					}else{			
						$config = BrandsConfig::find()->where(['id' => $device->config_id, 'owner_type' => 'configuration'])->one();
					}					
					if($config){
						if($item){
							$bufIds = array_column(unserialize($config->equipment_ids), 0);
							if(in_array($itemEquipment->id, $bufIds) && (isset($equipmentArray[$itemEquipment->id])) && (isset($brandsArray[$item->id])))
							{
								$value = PurchasingOrder::countElemsInArray($itemEquipment->id, $bufIds);
								$equipCount += $value;
								$val = $sheet->getCellByColumnAndRow($equipmentArray[$itemEquipment->id], $brandsArray[$item->id])->getValue();
								if($val){
									$sheet->setCellValueByColumnAndRow($equipmentArray[$itemEquipment->id], $brandsArray[$item->id], $value + $val);
									
								}
								else{
									$sheet->setCellValueByColumnAndRow($equipmentArray[$itemEquipment->id], $brandsArray[$item->id], $value);
									
								}
							}
						}
					}
				}
			}
			if((isset($equipmentArray[$itemEquipment->id])) && (isset($brandsArray[$item->id]))){
				$sheet->setCellValueByColumnAndRow($equipmentArray[$itemEquipment->id], 5, $equipCount);
			}
		}

		$sheet->getRowDimension(4)->setRowHeight(60);
		$sheet->getStyle('A1:'.PHPExcel_Cell::stringFromColumnIndex($column-1).($lastRow-1))->applyFromArray($xls->styleBorders);
		$sheet->getStyle('E5'.':'.PHPExcel_Cell::stringFromColumnIndex($column - 1).'5')->applyFromArray($xls->styleHeader);
		$sheet->getStyle('E5'.':'.PHPExcel_Cell::stringFromColumnIndex($column - 1).'5')->getAlignment()->setWrapText(true);
		$sheet->getStyle('E3'.':'.PHPExcel_Cell::stringFromColumnIndex($column - 1).'3')->getAlignment()->setTextRotation(90);
		//-------------------------------------------------Конец первого листа------------------------------------------------
		//Второй лист
		$sheet2 = $xls->xls->createSheet(1);
		$sheet2->setTitle('Жгуты');	
		
		$warrantyConfiguration = BrandsConfig::find()->where(['owner_id' => $order->id, 'owner_type' => 'order'])->one();//добавление списка оборудования с гарантии
		if($warrantyConfiguration){
			$warrantyIds = array_column(unserialize($warrantyConfiguration->equipment_ids), 0);
			$equipmentIds = array_merge($equipmentIds, $warrantyIds);
		}
		$newOrderConfiguration = BrandsConfig::find()->where(['owner_id' => $order->id, 'owner_type' => 'order_new'])->one();//добавление списка оборудования с новой установки
		if($newOrderConfiguration){
			$newOrderIds = array_column(unserialize($newOrderConfiguration->equipment_ids), 0);
			$equipmentIds = array_merge($equipmentIds, $newOrderIds);
		}			
		$equipmentList = Equipment::find()->where(['in', 'id', $equipmentIds])->all();
		// echo '<pre>';
		// var_dump($equipmentList);
		// exit;
		$typesArray = [];
		$equipmentArray = [];
		$i = 0;
		foreach($equipmentList as $item){
			if(!isset($typesArray[$item->type]) && $item->type < 5){
				$typesArray[$item->type]['col'] = $i;
				$typesArray[$item->type]['row'] = 3;
				$sheet2->mergeCells(PHPExcel_Cell::stringFromColumnIndex(0+$i).'1'.':'.PHPExcel_Cell::stringFromColumnIndex(1+$i).'2');
				$sheet2->setCellValue(PHPExcel_Cell::stringFromColumnIndex(0+$i).'1', Brands::getEquipmentTypes()[$item->type]);
				$sheet2->getColumnDimension(PHPExcel_Cell::stringFromColumnIndex($i))->setWidth(17);
				$sheet2->getColumnDimension(PHPExcel_Cell::stringFromColumnIndex($i+1))->setWidth(4);
				$i = $i+2;
			}
		}//Шапка из всех типов жгутов
		foreach($equipmentList as $item){
			if(isset($typesArray[$item->type])){
				$col = $typesArray[$item->type]['col'];
				$row = $typesArray[$item->type]['row'];
				$equipmentArray[$item->id]['col'] = $col + 1;
				$equipmentArray[$item->id]['row'] = $row;
				$sheet2->setCellValue(PHPExcel_Cell::stringFromColumnIndex($col).$row, $item->name);
				$typesArray[$item->type]['row']++;
			}
		}//Все имена жгутов по местам, под соответствующими типами
		$lastCol = 0;
		$lastRow = 0;
		foreach($mainDevices as $device)
		{
			if($device->model_id && $device->config_id){
				// $config = BrandsConfig::find()->where(['id' => $device->config_id])->one();
				if($device->order_type == 1){
					$config = BrandsConfig::find()->where(['owner_id' => $device->id, 'owner_type' => 'configuration'])->one();
				}else{			
					$config = BrandsConfig::find()->where(['id' => $device->config_id, 'owner_type' => 'configuration'])->one();
				}				
				if($config){
					$bufIds = array_column(unserialize($config->equipment_ids), 0);//Id всех устройств
					foreach($bufIds as $id){
						if(isset($equipmentArray[$id])){							
							$col = $equipmentArray[$id]['col'];
							$row = $equipmentArray[$id]['row'];
							//Подсчет количества затрачиваемых строк и столбцов, чтобы потом натянуть стили
							if($row > $lastRow){
								$lastRow = $row;
							}
							if($col > $lastCol){
								$lastCol = $col;
							}
							
							$val = $sheet2->getCellByColumnAndRow($col, $row)->getValue();
							if($val){
								$sheet2->setCellValueByColumnAndRow($col, $row, 1 + $val);
								
							}
							else{
								$sheet2->setCellValueByColumnAndRow($col, $row, 1);
								
							}							
						}
					}
				}
			}					
		}
		if($warrantyConfiguration){
			$equipment = unserialize($warrantyConfiguration->equipment_ids);
			foreach($equipment as $eq){
				$id = $eq[0];
				$amount = $eq[1];
				if(isset($equipmentArray[$id])){
					$col = $equipmentArray[$id]['col'];
					$row = $equipmentArray[$id]['row'];
					
					//Подсчет количества затрачиваемых строк и столбцов, чтобы потом натянуть стили
					if($row > $lastRow){
						$lastRow = $row;
					}
					if($col > $lastCol){
						$lastCol = $col;
					}
					
					$val = $sheet2->getCellByColumnAndRow($col, $row)->getValue();
					if($val){
						$sheet2->setCellValueByColumnAndRow($col, $row, $val + $amount);
						
					}
					else{
						$sheet2->setCellValueByColumnAndRow($col, $row, $amount);						
					}					
					// echo '<pre>';
					// var_dump($eq);
					// exit;					
				}
			}
		}
		if($newOrderConfiguration){
			$equipment = unserialize($newOrderConfiguration->equipment_ids);
			foreach($equipment as $eq){
				$id = $eq[0];
				$amount = $eq[1];
				if(isset($equipmentArray[$id])){
					$col = $equipmentArray[$id]['col'];
					$row = $equipmentArray[$id]['row'];
					
					//Подсчет количества затрачиваемых строк и столбцов, чтобы потом натянуть стили
					if($row > $lastRow){
						$lastRow = $row;
					}
					if($col > $lastCol){
						$lastCol = $col;
					}
					
					$val = $sheet2->getCellByColumnAndRow($col, $row)->getValue();
					if($val){
						$sheet2->setCellValueByColumnAndRow($col, $row, $val + $amount);
						
					}
					else{
						$sheet2->setCellValueByColumnAndRow($col, $row, $amount);						
					}					
					// echo '<pre>';
					// var_dump($eq);
					// exit;					
				}
			}
		}		
		
		$sheet2->getStyle('A1:'.PHPExcel_Cell::stringFromColumnIndex($lastCol).($lastRow))->applyFromArray($xls->styleFont12);
		$xls->sendXlsReport();
		
	}

	//Создание страницы для списка оборудования
	private static function makeEquipmentPage($mainDevices, $sheet, $xls, $configurationOrder, $orderId)
	{		
		$startRow = $row = 2;
		if($mainDevices){
			$sheet->mergeCells('A'.'3'.':A'.'5');
			$sheet->setCellValue('A'.'3', 'Марка');
			$sheet->getColumnDimension(PHPExcel_Cell::stringFromColumnIndex(0))->setWidth(15);
			$sheet->mergeCells('B'.'3'.':B'.'5');
			$sheet->setCellValue('B'.'3', 'Модель');
			$sheet->getColumnDimension(PHPExcel_Cell::stringFromColumnIndex(1))->setWidth(15);
			$sheet->mergeCells('C'.'3'.':C'.'5');
			$sheet->setCellValue('C'.'3', 'Год');
			$sheet->getColumnDimension(PHPExcel_Cell::stringFromColumnIndex(2))->setWidth(15);
			$sheet->mergeCells('D'.'3'.':D'.'5');
			$sheet->setCellValue('D'.'3', 'Серия');
			$sheet->getColumnDimension(PHPExcel_Cell::stringFromColumnIndex(3))->setWidth(15);
			$sheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);		
			
			$brands = [];
			foreach($mainDevices as $item)
			{
				if(Brands::find()->where(['id' => $item->model_id])->one()){
					$brands[] = Brands::find()->where(['id' => $item->model_id])->one();
				}
			}
			
	
			$column = 0;//Начальная колонка
			$row = 6;
			$equipmentIds = [];
			$equipmentList = [];
			$brandsArray = [];
			foreach($brands as $item)
			{			
				if(!isset($brandsArray[$item->id])){
					$sheet->setCellValueByColumnAndRow($column, $row, $item->brand);
					$sheet->setCellValueByColumnAndRow($column + 1, $row, $item->model);
					$sheet->setCellValueByColumnAndRow($column + 2, $row, $item->release_date);
					$sheet->setCellValueByColumnAndRow($column + 3, $row, $item->series);
					$brandsArray[$item->id] = $row;//Запоминание строки, где находится модель
					$row++;
				}
			}
			$equipmentIds = [];
			foreach($mainDevices as $item){
				
				if($item->config_id){
					// $configuration = BrandsConfig::find()->where(['owner_id' => $item->id, 'owner_type' => 'configuration'])->one();
					if($item->order_type == 1){
						$configuration = BrandsConfig::find()->where(['owner_id' => $item->id, 'owner_type' => 'configuration'])->one();					
					}else{			
						$configuration = BrandsConfig::find()->where(['id' => $item->config_id, 'owner_type' => 'configuration'])->one();
					}						
					// if(!$configuration){
						// $configuration = BrandsConfig::find()->where(['id' => $item->config_id, 'owner_type' => 'configuration'])->one();//Поддержка старого формата хранения
					// }	
					
					if($configuration){
						$bufIds = array_column(unserialize($configuration->equipment_ids), 0);
						
						foreach($bufIds as $id)
						{
							if(!in_array($id, $equipmentIds))
							{
								$equipmentIds[] = $id;//Формирование массива Id оборудования
							}
						}
					}									
				}				
			}
		
			$equipmentList = Equipment::find()->where(['in', 'id', $equipmentIds])->all();//Список объектов оборудования
	
			$lastRow = $row;//Последняя строка
			$column = 4;
			//----------------------------------------------------КОЛИЧЕСТВО ТЕХНИКИ------------------------------------------------------
			$cellRange = PHPExcel_Cell::stringFromColumnIndex($column) . (1) . ':' . PHPExcel_Cell::stringFromColumnIndex($column) . (2);
			$sheet->mergeCells($cellRange);
			$cellRange = PHPExcel_Cell::stringFromColumnIndex($column) . (3) . ':' . PHPExcel_Cell::stringFromColumnIndex($column) . (4);
			$sheet->mergeCells($cellRange);			
			$sheet->setCellValueByColumnAndRow($column, 1, 'Кол-во');
			$sheet->getColumnDimension(PHPExcel_Cell::stringFromColumnIndex($column))->setWidth(6);
					$j = 0;
					foreach($brands as $item)
					{
						
							$row = $brandsArray[$item->id];
							$val = $sheet->getCellByColumnAndRow($column, $row)->getValue();
							if($val){
								$sheet->setCellValueByColumnAndRow($column, $row, 1 + $val);
							}
							else{
								$sheet->setCellValueByColumnAndRow($column, $row, 1);
							}
							$j++;
						
					}
					$sheet->setCellValueByColumnAndRow($column, 5, $j);		
			//-----------------------------------------------------------------------------------------------------------------------------
			$column = 5;
			$row = 3;
			$type = 1;
			$equipmentArray = [];
			
			do {
				$i = 0;
				foreach($equipmentList as $item)
				{
					if($item->type == $type)
					{
						$equipmentArray[$item->id] = $column;
						$cellRange = PHPExcel_Cell::stringFromColumnIndex($column) . $row . ':' . PHPExcel_Cell::stringFromColumnIndex($column) . ($row + 1);
						$sheet->mergeCells($cellRange);
						$sheet->setCellValueByColumnAndRow($column, $row, $item->name);
						$sheet->getColumnDimension(PHPExcel_Cell::stringFromColumnIndex($column))->setWidth(3);
						$column++;
						$i++;
					}
				}			
				$textType = Brands::getEquipmentTypes()[$type];
				$bufI = $i > 0 ? $i : 1;
				$cellRange = PHPExcel_Cell::stringFromColumnIndex($column-$bufI) . (1) . ':' . PHPExcel_Cell::stringFromColumnIndex($column-1) . (2);
				$sheet->mergeCells($cellRange);
				$sheet->setCellValueByColumnAndRow($column - $i, 1, $textType);	
				$type ++;
			} while ($type < 10);//Все типы оборудования в шапку		
			foreach($equipmentList as $itemEquipment)
			{
				$equipCount = 0;			
				foreach($mainDevices as $device)
				{
					if($device->model_id && $device->config_id){
						$item = Brands::find()->where(['id' => $device->model_id])->one();
						// $config = BrandsConfig::find()->where(['id' => $device->config_id])->one();//Старый вариант
						if($device->order_type == 1){
							$config = BrandsConfig::find()->where(['owner_id' => $device->id, 'owner_type' => 'configuration'])->one();					
						}else{			
							$config = BrandsConfig::find()->where(['id' => $device->config_id, 'owner_type' => 'configuration'])->one();
						}						
						if($config){
							if($item){
								$bufIds = array_column(unserialize($config->equipment_ids), 0);
								if(in_array($itemEquipment->id, $bufIds))
								{
									$value = PurchasingOrder::countElemsInArray($itemEquipment->id, $bufIds);
									$equipCount += $value;
									$val = $sheet->getCellByColumnAndRow($equipmentArray[$itemEquipment->id], $brandsArray[$item->id])->getValue();
									if($val){
										$sheet->setCellValueByColumnAndRow($equipmentArray[$itemEquipment->id], $brandsArray[$item->id], $value + $val);
										
									}
									else{
										$sheet->setCellValueByColumnAndRow($equipmentArray[$itemEquipment->id], $brandsArray[$item->id], $value);
										
									}
								}
							}
						}
					}
				}
	
				$sheet->setCellValueByColumnAndRow($equipmentArray[$itemEquipment->id], 5, $equipCount);
			}
			$sheet->getRowDimension(4)->setRowHeight(60);
			$sheet->getStyle('A1:'.PHPExcel_Cell::stringFromColumnIndex($column-1).($lastRow-1))->applyFromArray($xls->styleBorders);
			$sheet->getStyle('E5'.':'.PHPExcel_Cell::stringFromColumnIndex($column - 1).'5')->applyFromArray($xls->styleHeader);
			$sheet->getStyle('E5'.':'.PHPExcel_Cell::stringFromColumnIndex($column - 1).'5')->getAlignment()->setWrapText(true);
			$sheet->getStyle('E3'.':'.PHPExcel_Cell::stringFromColumnIndex($column - 1).'3')->getAlignment()->setTextRotation(90);
			
	
			$startRow = $row = $lastRow + 2;
		}		
		if($configurationOrder){

			$equipment = unserialize($configurationOrder->equipment_ids);
			if($equipment){
				$sheet->setCellValue('A'.$row, 'Навзвание');
				$sheet->getColumnDimension(PHPExcel_Cell::stringFromColumnIndex(0))->setWidth(15);
				$sheet->setCellValue('B'.$row, 'Количество');
				$row++;
				foreach($equipment as $eq){
					$id = $eq[0];
					$amount = $eq[1];
					$equipmentModel = Equipment::findOne($id);
					$sheet->setCellValue('A'.$row, $equipmentModel->name);
					$sheet->setCellValue('B'.$row, $amount);
					$row++;
				}
				$sheet->getStyle('A'.$startRow.':'.'B'.($row-1))->applyFromArray($xls->styleBorders);
				$sheet->getStyle('A'.$startRow.''.':'.'B'.$startRow)->applyFromArray($xls->styleHeader);				
			}
		}
		
		return $sheet;
	}
	
	//Создание xls файла со списком оборудования
	public static function createEquipmentXls($order)
	{
		//---------------------ОСНОВНЫЕ ЗАГОЛОВКИ----------------------
		$xls = new XlsReport;
		$client = Client::findOne($order->client_id);
		if($client){
			$name = $client->name . ' ' . $order->name.' Оборудование';			
			$xls->fileName = $name;
		}else{
			$name = $order->name.' Оборудование';
			$xls->fileName = $name;
		}
		
		$sheet = $xls->getSheet();
		$sheet->setTitle('Заказ оборудования');		
		$sheet->mergeCells('A'.'1'.':D'.'2');
		if($client){
			$sheet->setCellValue('A'.'1', $client->name . ' ' . $order->name);
		}else{
			$sheet->setCellValue('A'.'1', $order->name);
		}		
				

		//------------------------------------------------------------		
		
		$mainDevices = MainDevices::find()->where(['order_id' => $order->id, 'order_type' => 0, 'active' => 1])->all();
		$mainDevicesWarranty = MainDevices::find()->where(['order_id' => $order->id, 'order_type' => 1, 'active' => 1])->all();
		$configuration = BrandsConfig::find()->where(['owner_id' => $order->id, 'owner_type' => 'order_new'])->one();
		$configurationWarranty = BrandsConfig::find()->where(['owner_id' => $order->id, 'owner_type' => 'order'])->one();
		
		if(!$mainDevices && !$mainDevicesWarranty && !$configuration && !$configurationWarranty)//Если нет техники, то возращает пустой документ
		{
			$xls->sendXlsReport();
			return;
		}		
		$sheet = PurchasingOrder::makeEquipmentPage($mainDevices, $sheet, $xls, $configuration, $order->id);
			
		$sheet2 = $xls->xls->createSheet(1);
		$sheet2->setTitle('Заказ оборудования на гарантию');		
		$sheet2->mergeCells('A'.'1'.':D'.'2');
		if($client){
			$sheet2->setCellValue('A'.'1', $client->name . ' ' . $order->name);
		}else{
			$sheet2->setCellValue('A'.'1', $order->name);
		}						
	
		$sheet2 = PurchasingOrder::makeEquipmentPage($mainDevicesWarranty, $sheet2, $xls, $configurationWarranty , $order->id);		

		$xls->sendXlsReport();
		
	}
	
	//Создание приложения к договору поставки
	public static function createSupplyContract($order)
	{
		$mainDevices = MainDevices::find()->where(['order_id' => $order->id, 'order_type' => 0])->all();
		$brands = [];

		foreach($mainDevices as $item)
		{
			$brands[] = Brands::find()->where(['id' => $item->model_id])->one();
		}		
		$objPHPWord =  new \PhpOffice\PhpWord\PhpWord();
		$files = FileHelper::findFiles('uploads/Blanks',['recursive'=>FALSE, 'only'=>['*.doc','*.docx']]);
		if($order->blank && isset($files[$order->blank])){
			$template = $files[$order->blank];
		}
		else{
			$template = $files [0];
		}
		
		$client = Client::findOne($order->client_id);
		$name = $client ? $client->name . ' ' . $order->name : $order->name;
		$document = $objPHPWord->loadTemplate($template);
		$document->setValue('name', $name);
		$document->setValue('contact_name', $order->name_defendant);
		$document->setValue('contact_phone', $order->phone_defendant);
		$document->setValue('mail', $order->mail_defendant);
		$document->setValue('kpp', $order->KPP);
		$document->setValue('adress', $order->contacts);
		$document->setValue('gsm_operator', PurchasingOrder::getOperatorsTypes()[$order->gsm_operator]);
		$document->setValue('engagement', $order->engagement);
		$document->setValue('customer', $order->customer);
		$document->setValue('name_controller', $order->name_controller);
		$document->setValue('card_type', PurchasingOrder::getCardsTypes()[$order->card_type]);
		$section =  $objPHPWord -> addSection();
		
		$document->cloneRow('id', count($brands));
		$i = 1;
		foreach($mainDevices as $device)
		{

			$item = Brands::find()->where(['id' => $device->model_id])->one();
			if($item){
				$document->setValue('id#'.$i, $i);
				$document->setValue('type#'.$i, Brands::getTehTypeList()[$item->type]);
				$document->setValue('brand#'.$i, $item->brand);
				if($device->model){
					$document->setValue('model#'.$i, $device->model);
				}else{
					$document->setValue('model#'.$i, $item->model);
				}
				$document->setValue('serial#'.$i, $device->serial_number);
				$document->setValue('date#'.$i, $device->year);
				$i++;
			}
			else{
				$item = Brands::find()->where(['and', ['brand' => $device->brand, 'model' => $device->model]])->one();
				if($item){
					$document->setValue('id#'.$i, $i);
					$document->setValue('type#'.$i, Brands::getTehTypeList()[$item->type]);
					$document->setValue('brand#'.$i, $item->brand);
					$document->setValue('model#'.$i, $item->model);
					$document->setValue('serial#'.$i, $device->serial_number);
					$document->setValue('date#'.$i, $device->year);
					$i++;					
				}
				else{
					$document->setValue('id#'.$i, $i);
					$document->setValue('type#'.$i, 'Не выбрано');
					$document->setValue('brand#'.$i, 'Не выбрано');
					$document->setValue('model#'.$i, 'Не выбрано');
					$document->setValue('serial#'.$i, $device->serial_number);
					$document->setValue('date#'.$i, 'Не выбрано');
					$i++;					
				}
			}
		}

		$document->cloneRow('id2', count($brands));
		$i = 1;
		foreach($mainDevices as $device)
		{
			$item = Brands::find()->where(['id' => $device->model_id])->one();
			if($item){
				$document->setValue('id2#'.$i, $i);
				$document->setValue('type2#'.$i, Brands::getTehTypeList()[$item->type]);
				if($device->model){
					$document->setValue('model2#'.$i, $device->model);
				}else{
					$document->setValue('model2#'.$i, $item->model);
				}				
				$document->setValue('garage_number#'.$i, $device->garage_number);
				$document->setValue('serial2#'.$i, $device->serial_number);
				$document->setValue('gsm#'.$i, PurchasingOrder::getOperatorsTypes()[$order->gsm_operator]);
				$i++;
			}
			else{
				$item = Brands::find()->where(['and', ['brand' => $device->brand, 'model' => $device->model]])->one();
				if($item){
					$document->setValue('id2#'.$i, $i);
					$document->setValue('type2#'.$i, Brands::getTehTypeList()[$item->type]);
					if($device->model){
						$document->setValue('model2#'.$i, $device->model);
					}else{
						$document->setValue('model2#'.$i, $item->model);
					}
					$document->setValue('garage_number#'.$i, $device->garage_number);
					$document->setValue('serial2#'.$i, $device->serial_number);
					$document->setValue('gsm#'.$i, PurchasingOrder::getOperatorsTypes()[$order->gsm_operator]);;
					$i++;					
				}
				else{
					$document->setValue('id2#'.$i, $i);
					$document->setValue('type2#'.$i, 'Не выбрано');
					$document->setValue('model2#'.$i, 'Не выбрано');
					$document->setValue('garage_number#'.$i, $device->garage_number);
					$document->setValue('serial2#'.$i, $device->serial_number);
					$document->setValue('gsm#'.$i, PurchasingOrder::getOperatorsTypes()[$order->gsm_operator]);
					$i++;					
				}
			}			
		} 
		$document->setValue('count2', $i-1);

		$newFileName = str_replace(',', ' ', $name.' (Приложение к договору поставки)');
		
		$file = 'uploads/docs/'.$newFileName.'.docx';
		$document->saveAs($file);
		if (file_exists($file)) {
			// сбрасываем буфер вывода PHP, чтобы избежать переполнения памяти выделенной под скрипт
			// если этого не сделать файл будет читаться в память полностью!
			if (ob_get_level()) {
			ob_end_clean();
			}
			// заставляем браузер показать окно сохранения файла
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename=' . $newFileName.'.docx');
			header('Content-Transfer-Encoding: binary');
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: public');
			header('Content-Length: ' . filesize($file));
			// читаем файл и отправляем его пользователю
			readfile($file);
			unlink($file);
			exit;
		}		
	}

	//Создание инструкции для установки
	public static function createInstruction($id)
	{
		// Create a new PHPWord Object
		$PHPWord = new \PhpOffice\PhpWord\PhpWord();
		
		// Every element you want to append to the word document is placed in a section. So you need a section:
		$section = $PHPWord->createSection();
		
		$configuration = BrandsConfig::findOne($id);
		
		$stages = InstallationStage::find()->where(['config_id' => $configuration->id])->orderBy('sort ASC')->all();
		
		$i = 1;
		$j = 1;
		foreach($stages as $item){
			$section->addText($j . '. ' . $item->text, array('name'=>'Tahoma', 'size'=>14, 'bold'=>false));
			$image = Images::findOne($item->attachemnt_id);
			if($image && file_exists($image->path)){
				// var_dump($image);
				// exit;
				$section->addImage($image->path, array('width'=>550, 'height'=>732, 'align'=>'center'));
			}
			$i = $i + 2;
			$j++;
		}

		//$section->addImage('G:\OpenServer\OpenServer\domains\origin\frontend/uploads/brands/i-4.jpg');
		
		// At least write the document to webspace:
		$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($PHPWord, 'Word2007');
		$file = 'uploads/docs/filename.docx';
		$objWriter->save($file);	

		if (file_exists($file)) {
			if (ob_get_level()) {
			ob_end_clean();
			}
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename=' . basename($file));
			header('Content-Transfer-Encoding: binary');
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: public');
			header('Content-Length: ' . filesize($file));
			readfile($file);
			unlink($file);
			exit;
		}		
	}	

	//Создание задания на установку
	public static function createInstallationTask($order)
	{
		$client = Client::findOne($order->client_id);
		$name = $client ? $client->name . ' ' . $order->name : $order->name;

		$objPHPWord =  new \PhpOffice\PhpWord\PhpWord();
		
		$mainDevicesWarranty = MainDevices::find()->where(['order_id' => $order->id, 'order_type' => 1])->all();
		$mainDevices = MainDevices::find()->where(['order_id' => $order->id, 'order_type' => 0])->all();
		
		if(count($mainDevicesWarranty) > 0 && count($mainDevices) > 0){			
			$document = $objPHPWord->loadTemplate('uploads/Blank2/Putevka_all.docx');
		}elseif(count($mainDevices) > 0){
			$document = $objPHPWord->loadTemplate('uploads/Blank2/Putevka_new.docx');
		}elseif(count($mainDevicesWarranty) > 0){
			$document = $objPHPWord->loadTemplate('uploads/Blank2/Putevka_warranty.docx');
		}
		$document->setValue('object_name', $client ? $client->name . ' ' . $order->name : $order->name);
		$document->setValue('contact_name', $order->name_defendant);
		$document->setValue('contact_phone', $order->phone_defendant);
		$document->setValue('install_date', $order->date);
		$document->setValue('installer', $order->contractor_comment);
		$document->setValue('adress', str_replace("\n", "<w:br/>", $order->contacts));

		$document->setValue('comments_new_order', str_replace("\n", "<w:br/>", $order->comments_new_order));
		$document->setValue('comments_warranty', str_replace("\n", "<w:br/>", $order->comments_warranty));	
		
		if(count($mainDevices) > 0){
			$i = 1;
			$document->cloneRow('id', count($mainDevices));
			foreach($mainDevices as $item){
				$brand = Brands::find()->where(['id' => $item->model_id])->one();
				$brandName = '-';
				$modelName = '-';
				$year = '-';
				$type = '-';
				if($brand){
					$brandName = $brand->brand;
					
					if($item->model){
						$modelName = $item->model;
					}else{
						$modelName = $brand->model;
					}				
					
					$type = Brands::getTehTypeList()[$brand->type];
					$year = $brand->release_date;
				}else{
					$brand = Brands::find()->where(['and', ['brand' => $item->brand, 'model' => $item->model]])->one();
					if($brand){
						$brandName = $brand->brand;
						
						if($item->model){
							$modelName = $item->model;
						}else{
							$modelName = $brand->model;
						}
						
						$type = Brands::getTehTypeList()[$brand->type];
						$year = $brand->release_date;					
					}				
				}				
				
				$document->setValue('id#'.$i, $i);
				$document->setValue('category#'.$i, $type);
				$document->setValue('brand#'.$i, $brandName);
				$document->setValue('model#'.$i, $modelName);
				$document->setValue('serial_number#'.$i, $item->serial_number ? $item->serial_number : '-');
				$document->setValue('year#'.$i, $year);
				$document->setValue('garage_number#'.$i, $item->garage_number ? $item->garage_number : '-');
				$document->setValue('id_terminal#'.$i, $item->ext_id ? $item->ext_id : '-');
				$i++;
			}
		}
		
		if(count($mainDevicesWarranty) > 0){		
			$document->cloneRow('id2', count($mainDevicesWarranty));
			$i = 1;
			
			foreach($mainDevicesWarranty as $item){
				$brand = Brands::find()->where(['id' => $item->model_id])->one();
				$brandName = '-';
				$modelName = '-';
				$year = '-';
				$type = '-';
				if($brand){
					$brandName = $brand->brand;
					
					if($item->model){
						$modelName = $item->model;
					}else{
						$modelName = $brand->model;
					}				
					
					$type = Brands::getTehTypeList()[$brand->type];
					$year = $brand->release_date;
				}else{
					$brand = Brands::find()->where(['and', ['brand' => $item->brand, 'model' => $item->model]])->one();
					if($brand){
						$brandName = $brand->brand;
						
						if($item->model){
							$modelName = $item->model;
						}else{
							$modelName = $brand->model;
						}
						
						$type = Brands::getTehTypeList()[$brand->type];
						$year = $brand->release_date;					
					}				
				}				
				
				$document->setValue('id2#'.$i, $i);
				$document->setValue('category2#'.$i, $type);
				$document->setValue('brand2#'.$i, $brandName);
				$document->setValue('model2#'.$i, $modelName);
				$document->setValue('serial_number2#'.$i, $item->serial_number ? $item->serial_number : '-');
				$document->setValue('year2#'.$i, $year);
				$document->setValue('gn2#'.$i, $item->garage_number ? $item->garage_number : '-');
				$document->setValue('comment2#'.$i, $item->configuration ? $item->configuration : '-');
				$i++;
			}		
		}
		$newFileName = $name.' (Задание на установку)';
		$file = 'uploads/docs/'.str_replace(',', ' ', $newFileName).'.docx';
		$document->saveAs($file);

		if (file_exists($file)) {
			// сбрасываем буфер вывода PHP, чтобы избежать переполнения памяти выделенной под скрипт
			// если этого не сделать файл будет читаться в память полностью!
			if (ob_get_level()) {
			ob_end_clean();
			}
			// заставляем браузер показать окно сохранения файла
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename=' . basename($file));
			header('Content-Transfer-Encoding: binary');
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: public');
			header('Content-Length: ' . filesize($file));
			// читаем файл и отправляем его пользователю
			readfile($file);
			unlink($file);
			exit;
		}		
	}
	
	//Создание doc с полным списком техники
	public static function createDevicesDoc($order)
	{
		$mainDevices = MainDevices::find()->where(['order_id' => $order->id, 'order_type' => 0])->all();
		
		$client = Client::findOne($order->client_id);
		$name = $client ? $client->name . ' ' . $order->name : $order->name;		
		
		$objPHPWord =  new \PhpOffice\PhpWord\PhpWord();
		$document = $objPHPWord->loadTemplate('uploads/Blank2/TechList.docx');
		$document->cloneRow('id', count($mainDevices));
		$i = 1;
		foreach($mainDevices as $item){
			$brand = Brands::find()->where(['id' => $item->model_id])->one();
			$brandName = 'n/a';
			$modelName = 'n/a';
			$year = 'n/a';
			$type = 'n/a';
			if($brand){
				$brandName = $brand->brand;
				
				if($item->model){
					$modelName = $item->model;
				}else{
					$modelName = $brand->model;
				}				
				
				$type = Brands::getTehTypeList()[$brand->type];
				$year = $brand->release_date;
			}else{
				$brand = Brands::find()->where(['and', ['brand' => $device->brand, 'model' => $device->model]])->one();
				if($brand){
					$brandName = $brand->brand;
					
					if($item->model){
						$modelName = $item->model;
					}else{
						$modelName = $brand->model;
					}
					
					$type = Brands::getTehTypeList()[$brand->type];
					$year = $brand->release_date;					
				}				
			}				
			
			$document->setValue('id#'.$i, $i);
			$document->setValue('category#'.$i, $type);
			$document->setValue('brand#'.$i, $brandName);
			$document->setValue('model#'.$i, $modelName);
			$document->setValue('serial_number#'.$i, $item->serial_number ? $item->serial_number : 'n/a');
			$document->setValue('year#'.$i, $year);
			$document->setValue('garage_number#'.$i, $item->garage_number ? $item->garage_number : 'n/a');
			$document->setValue('id_terminal#'.$i, $item->ext_id ? $item->ext_id : 'n/a');
			$i++;
		}
		
		$newFileName = $name.' (Список техники)';
		$file = 'uploads/docs/'.str_replace(',', ' ', $newFileName).'.docx';
		$document->saveAs($file);

		if (file_exists($file)) {
			// сбрасываем буфер вывода PHP, чтобы избежать переполнения памяти выделенной под скрипт
			// если этого не сделать файл будет читаться в память полностью!
			if (ob_get_level()) {
			ob_end_clean();
			}
			// заставляем браузер показать окно сохранения файла
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename=' . basename($file));
			header('Content-Transfer-Encoding: binary');
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: public');
			header('Content-Length: ' . filesize($file));
			// читаем файл и отправляем его пользователю
			readfile($file);
			unlink($file);
			exit;
		}		
	}

	//Отчет по гарантиям за месяц в Плане установок
	public static function createWarrantyMonthXls($month)
	{
		$splittedDate = explode('-', $month);
		$startDate = new \DateTime('01-' . $splittedDate[0] . '-20' . $splittedDate[1]);
		$finishDate = clone $startDate;
		$finishDate->add(new \DateInterval('P1M'));
		
		$result = [];
		$result['ids'] = [];
		$result['items'] = PurchasingOrder::find()->where(['deleted' => 0, 'contractor' => 1])->all();
		$orders = [];
		$i = 0;
		foreach($result['items'] as $item){
			if($item->date != NULL && $item->date != 'Invalid date'){
				$item->date = new \DateTime($item->date);
				if(!($item->date >= $startDate && $item->date < $finishDate)){
					$result['ids'][] = $i;
				}else{
					$orders[$item->id] = $item;
				}					
			}
			else{
				// $result['null'][] = $item;
				$result['ids'][] = $i;
			}
			if($item->date_shipment != NULL && $item->date_shipment != 'Invalid date'){
				$item->date_shipment = new \DateTime($item->date_shipment);
			}			
			$i++;
		}
		foreach($result['ids'] as $id){
			unset($result['items'][$id]);
		}
		
		$services = ServiceOrder::find()->where(['order_id' => array_keys($orders), 'owner_type' => 'warranty'])->all();
		
		$objPHPWord = new \PhpOffice\PhpWord\PhpWord();
		
		$document = $objPHPWord->loadTemplate('uploads/Blank2/Warranty_Monthly.docx');
		$document->cloneRow('id', count($services));
		$serviceTypes = Service::getList();
		$i = 1;
		$totalPrice = 0;
		foreach($services as $item){
			$order = $orders[$item->order_id];
			$client = Client::findOne($orders[$item->order_id]->client_id);
			$document->setValue('id#'.$i, $i);
			$document->setValue('date#'.$i, $order->date->format('d.m.Y'));
			$document->setValue('name#'.$i, $serviceTypes[$item->type]);
			$document->setValue('city#'.$i, $order->name );
			$document->setValue('client#'.$i, $client ? $client->name : '-');
			$document->setValue('amount#'.$i, $item->amount);
			$document->setValue('price#'.$i, $item->price);
			$document->setValue('total_price#'.$i, $item->total_price);
			$totalPrice += $item->total_price;
			$i++;
		}
		
		$document->setValue('total', floor($totalPrice));
		$document->setValue('total_abs', $totalPrice);
		$document->setValue('total_mod', floor(($totalPrice - floor($totalPrice)) * 100));
		
		$document->setValue('date_start', $startDate->format('d.m.Y'));
		$finishDate->sub(new \DateInterval('P1D'));
		$document->setValue('date_finish', $finishDate->format('d.m.Y'));
		$name = 'Гарантии за период (' . $startDate->format('d.m.Y') . ' - ' . $finishDate->format('d.m.Y') . ')';
		$file = 'uploads/docs/'.str_replace(',', ' ', $name).''.'.docx';
		$document->saveAs($file);

		if (file_exists($file)) {
			// сбрасываем буфер вывода PHP, чтобы избежать переполнения памяти выделенной под скрипт
			// если этого не сделать файл будет читаться в память полностью!
			if (ob_get_level()) {
			ob_end_clean();
			}
			// заставляем браузер показать окно сохранения файла
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename=' . basename($file));
			header('Content-Transfer-Encoding: binary');
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: public');
			header('Content-Length: ' . filesize($file));
			// читаем файл и отправляем его пользователю
			readfile($file);
			unlink($file);
			exit;
		}		
		// echo '<pre>';
		// var_dump($services);
		// exit;
	}
	
	//Создание приложения к договору установки(для Димы который)
	public static function createInstallContract($order)
	{
		$defaultDate = '«__» __________ 20__г.';
		if($order->date){
			$bufDate = new \DateTime($order->date);
			$bufDate = $bufDate->sub(new \DateInterval('P10D'));
			$defaultDate = $bufDate->format('d.m.Y');
		}
		
		
		$mainDevices = MainDevices::find()->where(['order_id' => $order->id, 'order_type' => 0])->all();
		$brands = [];

		foreach($mainDevices as $item)
		{
			$brands[] = Brands::find()->where(['id' => $item->model_id])->one();
		}		
		$objPHPWord =  new \PhpOffice\PhpWord\PhpWord();

		$client = Client::findOne($order->client_id);
		$name = $client ? $client->name . ' ' . $order->name : $order->name;		
		$document = $objPHPWord->loadTemplate('uploads/Blank2/Install.docx');
		$document->setValue('name', $name);
		$document->setValue('date', $defaultDate);
		$document->setValue('contact_name', $order->name_controller);
		$document->setValue('contact_phone', $order->phone_defendant);
		$document->setValue('mail', $order->mail_defendant);
		$document->setValue('order_id', $order->id);
		$now = new \DateTime();
		$document->setValue('now', $now->format('d.m.Y'));	
		$document->setValue('adress', $order->contacts);		
		$document->setValue('name_controller', $order->name_controller);
		$document->setValue('install_date', $order->date);
		
		//$section =  $objPHPWord -> addSection();
		
		$document->cloneRow('id', count($brands));
		$i = 1;
		foreach($mainDevices as $device)
		{

			$item = Brands::find()->where(['id' => $device->model_id])->one();
			if($item){
				$document->setValue('id#'.$i, $i);
				$document->setValue('type#'.$i, Brands::getTehTypeList()[$item->type]);
				$document->setValue('brand#'.$i, $item->brand);
				if($device->model){
					$document->setValue('model#'.$i, $device->model);
				}else{
					$document->setValue('model#'.$i, $item->model);
				}
				$document->setValue('serial#'.$i, $device->serial_number);
				$document->setValue('date#'.$i, $item->release_date);
				$i++;
			}
			else{
				$item = Brands::find()->where(['and', ['brand' => $device->brand, 'model' => $device->model]])->one();
				if($item){
					$document->setValue('id#'.$i, $i);
					$document->setValue('type#'.$i, Brands::getTehTypeList()[$item->type]);
					$document->setValue('brand#'.$i, $item->brand);
					$document->setValue('model#'.$i, $item->model);
					$document->setValue('serial#'.$i, $device->serial_number);
					$document->setValue('date#'.$i, $device->year);
					$i++;					
				}
				else{
					$document->setValue('id#'.$i, $i);
					$document->setValue('type#'.$i, 'Не выбрано');
					$document->setValue('brand#'.$i, 'Не выбрано');
					$document->setValue('model#'.$i, 'Не выбрано');
					$document->setValue('serial#'.$i, $device->serial_number);
					$document->setValue('date#'.$i, 'Не выбрано');
					$i++;					
				}
			}
		}
		$devicesCount = $i-1;
		$document->setValue('count2', $devicesCount);
		$document->setValue('total_price', $devicesCount * 8000);
		$servicesList = ServiceOrder::find()->where(['order_id' => $order->id, 'owner_type' => 'new' ])->all();
		if(count($servicesList) > 0){
			$services = Service::getList();
			$document->cloneRow('service_num', count($servicesList));
			$i = 1;
			$totalPrice = 0;
			foreach($servicesList as $item)
			{
				$document->setValue('service_num#'.$i, $i);
				$document->setValue('service_name#'.$i, $services[$item->type]);
				$document->setValue('service_price#'.$i, $item->price);
				$document->setValue('service_amount#'.$i, $item->amount);
				$document->setValue('service_total#'.$i, $item->total_price);
				$totalPrice += $item->total_price;
				$i++;
			}
		}else{
			$totalPrice = 0;
			$document->setValue('service_num', 1);
			$document->setValue('service_name', ' ');
			$document->setValue('service_price', ' ');
			$document->setValue('service_amount', ' ');
			$document->setValue('service_total', ' ');			
		}			
		
		$document->setValue('total', $totalPrice);
		
		$newFileName = $name.' (Приложение к договору на устанвку)';
		
		$file = 'uploads/docs/'.str_replace(',', ' ', $newFileName).'.docx';
		$document->saveAs($file);

		if (file_exists($file)) {
			// сбрасываем буфер вывода PHP, чтобы избежать переполнения памяти выделенной под скрипт
			// если этого не сделать файл будет читаться в память полностью!
			if (ob_get_level()) {
			ob_end_clean();
			}
			// заставляем браузер показать окно сохранения файла
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename=' . basename($file));
			header('Content-Transfer-Encoding: binary');
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: public');
			header('Content-Length: ' . filesize($file));
			// читаем файл и отправляем его пользователю
			readfile($file);
			unlink($file);
			exit;
		}		
	}	
	private static function countElemsInArray($element, $list)
	{
		$i = 0;
		foreach($list as $item)
		{
			if($item == $element)
			{
				$i++;
			}
		}
		return $i;
	}

	//Создание doc с полным списком техники
	public static function createDevicesXls($order)
	{
		$xls = new XlsReport;
		$client = Client::findOne($order->client_id);
		if($client){
			$xls->fileName = $client->name . ' ' . $order->name.' (Список техники)';
		}else{
			$xls->fileName = $order->name.' (Список техники)';
		}
		
		$sheet = $xls->getSheet();
		$sheet->setTitle('Список техники');

		
		$mainDevices = MainDevices::find()->where(['order_id' => $order->id, 'order_type' => 0])->all();
			
		$sheet->setCellValueByColumnAndRow(0, 1, 'Марка');
		$sheet->getColumnDimension(PHPExcel_Cell::stringFromColumnIndex(0))->setWidth(15);
		$sheet->setCellValueByColumnAndRow(1, 1, 'Модель');
		$sheet->getColumnDimension(PHPExcel_Cell::stringFromColumnIndex(1))->setWidth(15);
		$sheet->setCellValueByColumnAndRow(2, 1, 'Год');
		$sheet->getColumnDimension(PHPExcel_Cell::stringFromColumnIndex(2))->setWidth(15);
		$sheet->setCellValueByColumnAndRow(3, 1, 'Серийник');
		$sheet->getColumnDimension(PHPExcel_Cell::stringFromColumnIndex(3))->setWidth(15);
		$sheet->setCellValueByColumnAndRow(4, 1, 'Гаражный номер');
		$sheet->getColumnDimension(PHPExcel_Cell::stringFromColumnIndex(4))->setWidth(15);
		$sheet->setCellValueByColumnAndRow(5, 1, 'Примечание');	
		$sheet->getColumnDimension(PHPExcel_Cell::stringFromColumnIndex(5))->setWidth(15);
		
		$row = 2;
		foreach($mainDevices as $item){
			$brand = Brands::find()->where(['id' => $item->model_id])->one();
			$brandName = 'n/a';
			$modelName = 'n/a';
			$year = 'n/a';
			$type = 'n/a';
			if($brand){
				$brandName = $brand->brand;
				
				if($item->model){
					$modelName = $item->model;
				}else{
					$modelName = $brand->model;
				}				
				
				$type = Brands::getTehTypeList()[$brand->type];
				$year = $brand->release_date;
			}else{
				$brand = Brands::find()->where(['and', ['brand' => $item->brand, 'model' => $item->model]])->one();
				if($brand){
					$brandName = $brand->brand;
					
					if($item->model){
						$modelName = $item->model;
					}else{
						$modelName = $brand->model;
					}
					
					$type = Brands::getTehTypeList()[$brand->type];
					$year = $brand->release_date;					
				}				
			}				
			$sheet->setCellValueByColumnAndRow(0, $row, $brandName);
			$sheet->setCellValueByColumnAndRow(1, $row, $modelName);
			$sheet->setCellValueByColumnAndRow(2, $row, $item->year ? $item->year : 'n/a');
			$sheet->setCellValueByColumnAndRow(3, $row, $item->serial_number ? $item->serial_number : 'n/a');
			$sheet->setCellValueByColumnAndRow(4, $row, $item->garage_number ? $item->garage_number : 'n/a');
			$sheet->setCellValueByColumnAndRow(5, $row, $item->configuration ? $item->configuration : 'n/a');

			$row++;
		}
		
		$sheet->getStyle('A1:'.PHPExcel_Cell::stringFromColumnIndex(5).($row-1))->applyFromArray($xls->styleBorders);
		$sheet->getStyle('A1' . ':' . 'F1')->applyFromArray($xls->styleHeader);
		$xls->sendXlsReport();		
				
	}	
	//Создание xls файла со списком оборудования без жгутов
	public static function createEquipmentXlsCompact($order)
	{
		$mainDevices = MainDevices::find()->where(['order_id' => $order->id, 'order_type' => 0, 'active' => 1])->all();
		$brands = [];
		foreach($mainDevices as $item)
		{
			if(Brands::find()->where(['id' => $item->model_id])->one()){
				$brands[] = Brands::find()->where(['id' => $item->model_id])->one();
			}
		}
		//---------------------ОСНОВНЫЕ ЗАГОЛОВКИ----------------------
		$xls = new XlsReport;
		$client = Client::findOne($order->client_id);
		if($client){
			$xls->fileName = $client->name . ' ' . $order->name.' (Список оборудования без жгутов)';
		}else{
			$xls->fileName = $order->name.' (Список оборудования)';
		}
		
		$sheet = $xls->getSheet();
		$sheet->setTitle('Заказ оборудования');		
		$sheet->mergeCells('A'.'1'.':D'.'2');
		if($client){
			$sheet->setCellValue('A'.'1', $client->name . ' ' . $order->name);
		}else{
			$sheet->setCellValue('A'.'1', $order->name);
		}		
				
		$sheet->mergeCells('A'.'3'.':A'.'5');
		$sheet->setCellValue('A'.'3', 'Марка');
		$sheet->getColumnDimension(PHPExcel_Cell::stringFromColumnIndex(0))->setWidth(15);
		$sheet->mergeCells('B'.'3'.':B'.'5');
		$sheet->setCellValue('B'.'3', 'Модель');
		$sheet->getColumnDimension(PHPExcel_Cell::stringFromColumnIndex(1))->setWidth(15);
		$sheet->mergeCells('C'.'3'.':C'.'5');
		$sheet->setCellValue('C'.'3', 'Год');
		$sheet->getColumnDimension(PHPExcel_Cell::stringFromColumnIndex(2))->setWidth(15);
		$sheet->mergeCells('D'.'3'.':D'.'5');
		$sheet->setCellValue('D'.'3', 'Серия');
		$sheet->getColumnDimension(PHPExcel_Cell::stringFromColumnIndex(3))->setWidth(15);
		$sheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
		//------------------------------------------------------------
		
		if(!$mainDevices)//Если нет техники, то возращает пустой документ
		{
			$xls->sendXlsReport();
			return;
		}
		$column = 0;//Начальная колонка
		$row = 6;
		$equipmentIds = [];
		$equipmentList = [];
		$brandsArray = [];
		foreach($brands as $item)
		{			
				if(!isset($brandsArray[$item->id])){
				$sheet->setCellValueByColumnAndRow($column, $row, $item->brand);
				$sheet->setCellValueByColumnAndRow($column + 1, $row, $item->model);
				$sheet->setCellValueByColumnAndRow($column + 2, $row, $item->release_date);
				$sheet->setCellValueByColumnAndRow($column + 3, $row, $item->series);
				$brandsArray[$item->id] = $row;//Запоминание строки, где находится модель
				$row++;
				}
		}
		$equipmentIds = [];
		foreach($mainDevices as $item){
			
			if($item->config_id){
				// var_dump($item);
				// exit;
				$configuration = BrandsConfig::find()->where(['id' => $item->config_id])->one();
				if($configuration){
					$bufIds = array_column(unserialize($configuration->equipment_ids), 0);
					
					foreach($bufIds as $id)
					{
						if(!in_array($id, $equipmentIds))
						{
							$equipmentIds[] = $id;//Формирование массива Id оборудования
						}
					}
				}
				//$equipmentList += Equipment::find()->where(['in', 'id', $equipmentIds])->all();//Список объектов оборудования
								
			}				
		}
		$equipmentList = Equipment::find()->where(['in', 'id', $equipmentIds])->all();//Список объектов оборудования
		// var_dump($equipmentList);
		// exit;
		$lastRow = $row;//Последняя строка
		$column = 4;
		//----------------------------------------------------КОЛИЧЕСТВО ТЕХНИКИ------------------------------------------------------
		$cellRange = PHPExcel_Cell::stringFromColumnIndex($column) . (1) . ':' . PHPExcel_Cell::stringFromColumnIndex($column) . (2);
		$sheet->mergeCells($cellRange);
		$cellRange = PHPExcel_Cell::stringFromColumnIndex($column) . (3) . ':' . PHPExcel_Cell::stringFromColumnIndex($column) . (4);
		$sheet->mergeCells($cellRange);			
		$sheet->setCellValueByColumnAndRow($column, 1, 'Кол-во');
		$sheet->getColumnDimension(PHPExcel_Cell::stringFromColumnIndex($column))->setWidth(6);
				$j = 0;
				foreach($brands as $item)
				{
					
						$row = $brandsArray[$item->id];
						$val = $sheet->getCellByColumnAndRow($column, $row)->getValue();
						if($val){
							$sheet->setCellValueByColumnAndRow($column, $row, 1 + $val);
						}
						else{
							$sheet->setCellValueByColumnAndRow($column, $row, 1);
						}
						$j++;
					
				}
				$sheet->setCellValueByColumnAndRow($column, 5, $j);		
		//-----------------------------------------------------------------------------------------------------------------------------
		$column = 5;
		$row = 3;
		$type = 5;
		$equipmentArray = [];
		
		do {
			$i = 0;
			foreach($equipmentList as $item)
			{
				if($item->type == $type)
				{
					$equipmentArray[$item->id] = $column;
					$cellRange = PHPExcel_Cell::stringFromColumnIndex($column) . $row . ':' . PHPExcel_Cell::stringFromColumnIndex($column) . ($row + 1);
					//$sheet->getColumnDimension(PHPExcel_Cell::stringFromColumnIndex($column))->setAutoSize(true);//->setWidth(4);
					$sheet->mergeCells($cellRange);
					$sheet->setCellValueByColumnAndRow($column, $row, $item->name);
					$sheet->getColumnDimension(PHPExcel_Cell::stringFromColumnIndex($column))->setWidth(3);
					$column++;
					$i++;
				}
			}			
			$textType = Brands::getEquipmentTypes()[$type];
			$bufI = $i > 0 ? $i : 1;
			$cellRange = PHPExcel_Cell::stringFromColumnIndex($column-$bufI) . (1) . ':' . PHPExcel_Cell::stringFromColumnIndex($column-1) . (2);
			$sheet->mergeCells($cellRange);
			$sheet->setCellValueByColumnAndRow($column - $i, 1, $textType);	
			//$sheet->getColumnDimension(PHPExcel_Cell::stringFromColumnIndex($column - $i))->setAutoSize(true);
			$type ++;
		} while ($type < 10);//Все типы оборудования в шапку		
		foreach($equipmentList as $itemEquipment)
		{
			if(isset($equipmentArray[$itemEquipment->id])){
				$equipCount = 0;			
				foreach($mainDevices as $device)
				{
					if($device->model_id && $device->config_id){
						$item = Brands::find()->where(['id' => $device->model_id])->one();
						$config = BrandsConfig::find()->where(['id' => $device->config_id])->one();
						if($config){
							if($item){
								$bufIds = array_column(unserialize($config->equipment_ids), 0);
								if(in_array($itemEquipment->id, $bufIds))
								{
									$value = PurchasingOrder::countElemsInArray($itemEquipment->id, $bufIds);
									$equipCount += $value;
									$val = $sheet->getCellByColumnAndRow($equipmentArray[$itemEquipment->id], $brandsArray[$item->id])->getValue();
									if($val){
										$sheet->setCellValueByColumnAndRow($equipmentArray[$itemEquipment->id], $brandsArray[$item->id], $value + $val);
										
									}
									else{
										$sheet->setCellValueByColumnAndRow($equipmentArray[$itemEquipment->id], $brandsArray[$item->id], $value);
										
									}
								}
							}
						}
					}
				}
	
				$sheet->setCellValueByColumnAndRow($equipmentArray[$itemEquipment->id], 5, $equipCount);
			}
		}
		$sheet->getRowDimension(4)->setRowHeight(60);
		$sheet->getStyle('A1:'.PHPExcel_Cell::stringFromColumnIndex($column-1).($lastRow-1))->applyFromArray($xls->styleBorders);
		$sheet->getStyle('E5'.':'.PHPExcel_Cell::stringFromColumnIndex($column - 1).'5')->applyFromArray($xls->styleHeader);
		$sheet->getStyle('E5'.':'.PHPExcel_Cell::stringFromColumnIndex($column - 1).'5')->getAlignment()->setWrapText(true);
		$sheet->getStyle('E3'.':'.PHPExcel_Cell::stringFromColumnIndex($column - 1).'3')->getAlignment()->setTextRotation(90);
		$xls->sendXlsReport();
		
	}
	

}
