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
use frontend\models\MainDevices;
use yii\data\Pagination;
use frontend\models\Brands;
use frontend\models\BrandsConfig;
use yii\helpers\Json;
use frontend\models\Equipment;
use app\modules\support\models\Images;

/**
 * HarnessController implements the CRUD actions.
 */
class MainDevicesController extends Controller
{
	public $layout='/../../views/layouts/account.php';


    public function behaviors()
    {
        return [
			'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'update', 'create', 'delete', 'create-warranty', 'update-warranty', 'photo'],
                        'allow' => true,
                        'roles' => ['manageOrders'],
                    ],
					 [
                        'actions' => ['brand',],
                        'allow' => true,
                        'roles' => ['manageOrders', 'manageDevices'],
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

    public function actionCreate($orderId, $deviceId=false)
    {
		$model = new MainDevices();
		$model->order_id = $orderId;
		$device = new MainDevices();
		if($deviceId){
			$device = MainDevices::find()->where(['id' => $deviceId])->one();
			$model->model_id = $device->model_id;
			$model->config_id = $device->config_id;
			$model->order_type = $device->order_type;
		}
		if ($model->load(Yii::$app->request->post())) {
			$model->model = str_replace("\n", '', $model->model);
			if(!$deviceId){
				$model->order_type = 0;
			}
			if($model->save())
			{				
				return $this->redirect(['update', 'id' => $model->id, 'orderId' => $orderId]);
			}

		}

		return $this->render('create', [
			'adminModel' => $model,
			'selectArray' => $deviceId ? MainDevices::getSelectArray($device) : MainDevices::getSelectArray($model), 
			'backId' =>  $orderId,
		]);
    }

    public function actionUpdate($id, $orderId)
    {
		$model = $this->findModel($id);
		$brand = Brands::findOne($model->model_id);
		if ($model->load(Yii::$app->request->post())) {
			$model->model = str_replace("\n", '', $model->model);
			$model->save();

			Yii::$app->getSession()->setFlash('success', 'Сохранено');	
			return $this->render('update', [
				'adminModel' => $model,
				'selectArray' => MainDevices::getSelectArray($model),
				'backId' =>  $orderId,
				'brand' => $brand,
			]);			
		}
		return $this->render('update', [
			'adminModel' => $model,
			'selectArray' => MainDevices::getSelectArray($model),
			'backId' =>  $orderId,
			'brand' => $brand,
		]);
    }	
	
    public function actionCreateWarranty($orderId, $deviceId=false)
    {
		$model = new MainDevices();
		$model->order_id = $orderId;
		$device = new MainDevices();
		if($deviceId){
			$device = MainDevices::find()->where(['id' => $deviceId])->one();
			$model->model_id = $device->model_id;
			$model->config_id = $device->config_id;
		}

		
		$configModel = new BrandsConfig();	
		$configModel->owner_type = 'configuration';

		$selectItems = [];
		$selectItems['types'] = Brands::getEquipmentTypes();
		
		if ($model->load(Yii::$app->request->post())) {
			$model->model = str_replace("\n", '', $model->model);
			$model->order_type = 1;
			if($model->save())
			{
				$configModel->owner_id = $model->id;
				if($configModel->load(Yii::$app->request->post())){					
					setlocale(LC_ALL, 'ru_RU.CP1251');//Для работы с русским
					$equipmentIds = $configModel->harnesses;
					$equipmentCounts = $configModel->counts;
					$resultIds = [];
					$i = 0;
					foreach($equipmentIds as $id){
						if($id){
							if(isset($equipmentCounts[$i])){
								$resultIds[] = [$id, $equipmentCounts[$i]];
							}else{
								$resultIds[] = [$id, 1];
							}
						}
						$i++;
					}
					$configModel->equipment_ids = serialize($resultIds);
					$configModel->save();					
				}
				return $this->redirect(['update-warranty', 'id' => $model->id, 'orderId' => $orderId]);
			}

		}

		return $this->render('create-warranty', [
			'adminModel' => $model,
			'selectArray' => $deviceId ? MainDevices::getSelectArray($device) : MainDevices::getSelectArray($model), 
			'backId' =>  $orderId,
			'configModel' => $configModel,
			'selectItems' => $selectItems,
			'harness' => Json::encode(Brands::getEquipmentList()),			
		]);
    }	
	
    public function actionUpdateWarranty($id, $orderId)
    {
		$model = $this->findModel($id);
		$brand = Brands::findOne($model->model_id);

		$configModel = BrandsConfig::find()->where(['owner_id' => $id, 'owner_type' => 'configuration'])->one();
		if(!$configModel){
			$configModel = new BrandsConfig();
			$configModel->owner_type = 'configuration';
			$configModel->owner_id = $model->id;
		}		
		
		if ($model->load(Yii::$app->request->post())) {
			$model->model = str_replace("\n", '', $model->model);
			if($model->save()){
				if($configModel->load(Yii::$app->request->post())){					
					setlocale(LC_ALL, 'ru_RU.CP1251');//Для работы с русским
					$equipmentIds = $configModel->harnesses;
					$equipmentCounts = $configModel->counts;
					$resultIds = [];
					$i = 0;
					foreach($equipmentIds as $id){
						if($id){
							if(isset($equipmentCounts[$i])){
								$resultIds[] = [$id, $equipmentCounts[$i]];
							}else{
								$resultIds[] = [$id, 1];
							}
						}
						$i++;
					}
					$configModel->equipment_ids = serialize($resultIds);
					$configModel->save();					
				}				
			}

			Yii::$app->getSession()->setFlash('success', 'Сохранено');	
			return $this->redirect(['update-warranty', 'id' => $model->id, 'orderId' => $orderId]);			
		}
		

		
		$selectItems = [];
		$selectItems['types'] = Brands::getEquipmentTypes();
		$ids = unserialize($configModel->equipment_ids);
		$namesEquipmentList = Brands::getEquipmentList();
		$equipmentList = [];
		$i = 0;
		if($ids){
			foreach($ids as $item){
				$equipment = Equipment::findOne($item[0]);
				if($equipment){
					$equipmentList[$i]['current_type'] = $equipment->type;
					$equipmentList[$i]['id'] = $equipment->id;
					$equipmentList[$i]['current_name'] = $equipment->id;
					$equipmentList[$i]['names'] = $namesEquipmentList[$equipment->type];
					$equipmentList[$i]['count'] = $item[1];
					$imageConf = Images::find()->where(['and', ['owner_id' => $equipment->id, 'owner_type' => 'equipment']])->one();
					if($imageConf){
						$equipmentList[$i]['image'] = $imageConf->id;
					}				
					$i++;
				}
			}
		}		
		
		return $this->render('update-warranty', [
			'adminModel' => $model,
			'selectArray' => MainDevices::getSelectArray($model),
			'backId' =>  $orderId,
			'brand' => $brand,
			
			'configModel' => $configModel,
			'selectItems' => $selectItems,
			'harness' => Json::encode($namesEquipmentList),
			'equipmentList' => $equipmentList,			
		]);
    }

    public function actionDelete($orderId, $deviceId)
    {
		$model = $this->findModel($deviceId);
		$model->delete();
		return $this->redirect(['/admin/order/update/?id='.$orderId]);
    }
	
	/**
	 * Поиск модели по id
	*/
	protected function findModel($id)
    {
        if (($model = MainDevices::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


	/**
	* Используется в js для заполнения дропдаунов. Формирует код HTML для селектов в зависимости от полученных параметров.
	*/	
	public function actionBrand($brand=false, $model=false, $year=false, $series=false)
	{
		$response = '';
		if($brand && !$model && !$year && !$series)//Если пришло название брэнда, то выдать список моделей
		{
			$response = '<option value=""></option>';
			$brands = Brands::find()->where(['brand'=>$brand, 'deleted' => 0])->orderBy('model ASC')->all();	
			$models = [];	
			$pseudonyms = [];
			foreach($brands as $value)
			{
				if(!in_array($value['model'],$models))//Проверка на повторы
				{
					//$response .= '<option value="'.$value['model'].'">'.$value['model'].'</option>';
					$models[] = trim($value['model']);
				}
				if($value->pseudonym){
					$pseudonymsBuf = unserialize($value->pseudonym);
					foreach($pseudonymsBuf as $item){
						if(!in_array($item, $pseudonyms) && !in_array(trim($item), $pseudonyms)){
							$pseudonyms[] = trim($item);
						}
					}
					
				}
			}
			$allModels = array_merge($models, $pseudonyms);
			sort($allModels);
			foreach($allModels as $item){
				$response .= '<option value="'.$item.'">'.$item.'</option>';
			}
		}
		elseif($brand && $model && !$year && !$series)//Если пришло название брэнда и модели, то выдать список годов
		{
			$response = '<option value=""></option>';
			$brands = Brands::find()->where(['and',['brand'=>$brand, 'model'=>$model, 'deleted' => 0]])->all();
			if(!$brands){
				$brands = [];
				$bufBrands = Brands::find()->where(['and',['brand'=>$brand, 'deleted' => 0]])->all();
				foreach($bufBrands as $brand){
					if($brand->pseudonym){
						$pseudonyms = unserialize($brand->pseudonym);
						if(in_array($model, $pseudonyms)){
							$brands[] = $brand;
							// var_dump('Ok');
						}
						elseif(in_array("\n".$model, $pseudonyms)){
							$brands[] = $brand;
							// var_dump('Ok');
						}
					}
				}
			}
			// exit;
			$years =[];			
			foreach($brands as $value)
			{
				if(!in_array($value['release_date'],$years))//Проверка на повторы
				{				
					$response .= '<option value="'.$value['release_date'].'">'.$value['release_date'].'</option>';
					$years[] = $value['release_date'];
				}
			}			
		}
		elseif($brand && $model && $year && !$series)//Если пришло название брэнда, модели и года, то выдать список конфигурация
		{
			$response = '<option value=""></option>';
			$modelDevice = Brands::find()->where(['and',['brand'=>$brand, 'model'=>$model, 'release_date' => $year, 'deleted' => 0]])->one();
			if(!$modelDevice){//Значит пришел псевдоним
				$brands = Brands::find()->where(['and',['brand'=>$brand, 'release_date' => $year, 'deleted' => 0]])->all();
				
				foreach($brands as $item){
					if($item->pseudonym){
						$pseudonyms = unserialize($item->pseudonym);						
						if(in_array($model, $pseudonyms)){
							$modelDevice = $item;							
							break;
						}
						elseif(in_array("\n".$model, $pseudonyms)){
							$modelDevice = $item;							
							break;
						}						
					}
				}
			}
			$configs = BrandsConfig::find()->where(['owner_id' => $modelDevice->id, 'owner_type' => 'configuration', 'deleted' => 0])->all();
			$seriesList =[];			
			foreach($configs as $value)
			{
				if(!in_array($value->name,$seriesList))//Проверка на повторы
				{				
					$response .= '<option value="'.$value['id'].'">'.$value['name'].'</option>';
					$seriesList[] = $value['name'];
				}
			}
		}
		elseif($brand && $model && $year && $series)//Если пришло название брэнда, модели, год и серия, то выдать id
		{
			$modelDevice = Brands::find()->where(['and',['brand'=>$brand, 'model'=>$model, 'release_date' => $year, 'deleted' => 0]])->one();
			if(!$modelDevice){//Значит пришел псевдоним
				$brands = Brands::find()->where(['and',['brand'=>$brand, 'release_date' => $year, 'deleted' => 0]])->all();
				
				foreach($brands as $item){
					if($item->pseudonym){
						$pseudonyms = unserialize($item->pseudonym);						
						if(in_array($model, $pseudonyms)){
							$modelDevice = $item;							
							break;
						}
						elseif(in_array("\n".$model, $pseudonyms)){
							$modelDevice = $item;							
							break;
						}							
					}
				}
			}			
			$response = $modelDevice->id;
		}
		return $response;
	}


	/**
	* Ajax изменение статуса наличия фото
	*/	
	public function actionPhoto($deviceId, $status)
	{
		$device = MainDevices::findOne($deviceId);
		if($device){
			if($status){
				$device->photo = 0;
			}else{
				$device->photo = 1;
			}
			if($device->save()){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
}
