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
use frontend\models\Brands;
use frontend\models\BrandsConfig;
use frontend\models\MainDevices;
use yii\data\Pagination;
use frontend\models\Equipment;
use yii\helpers\Json;
use app\modules\support\models\Images;
use app\modules\admin\models\ImageUploadMultiple;
use app\modules\admin\models\InstallationStage;
use yii\web\UploadedFile;
use app\modules\admin\models\PurchasingOrder;

/**
 * AdministrationController implements the CRUD actions.
 */
class ConfigurationController extends Controller
{
	public $layout='/../../views/layouts/account.php';


    public function behaviors()
    {
        return [
			'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['instruction', 'update', 'create', 'add-stage', 'update-stage', 'delete-stage', 'get-instruction', 'copy', 'delete', 'get-stage', 'get-equipment', 'stage-image'],
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
            ],
        ];
    }


	//Создание конфигурации
    public function actionCreate($brandId, $ownerType = 'configuration')
    {
		$model = new BrandsConfig();
		$model->owner_id = $brandId;
		$model->owner_type = $ownerType;
		
		$selectItems = [];
		$selectItems['types'] = Brands::getEquipmentTypes();
		
		if ($model->load(Yii::$app->request->post())) {
			setlocale(LC_ALL, 'ru_RU.CP1251');//Для работы с русским
			$equipmentIds = $model->harnesses;
			$equipmentCounts = $model->counts;
			$resultIds = [];
			// echo '<pre>';
			// var_dump($equipmentIds);
			// exit;
			$i = 0;
			if($equipmentIds){
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
			}
			$model->equipment_ids = serialize($resultIds);
			$model->save();
			if($ownerType == 'configuration'){
				return $this->redirect(['update', 'id' => $model->id]);
			}else{
				return $this->redirect('/admin/order/update/?id='.$brandId);
			}
		}
		
		return $this->render('create', [
			'model' => $model,
			'result' => $selectItems,
			'harness' => Json::encode(Brands::getEquipmentList()),
		]);		
    }
	//Копирование конфигурации
    public function actionCopy($id)
    {
		$modelParent = BrandsConfig::findOne($id);
		$model = new BrandsConfig();
		
		$model->name = $modelParent->name;
		$model->owner_id = $modelParent->owner_id;
		$model->equipment_ids = $modelParent->equipment_ids;
		$model->owner_type = $modelParent->owner_type;
		
		$uploadForm = new ImageUploadMultiple();
		$stage = new InstallationStage();
		if ($model->load(Yii::$app->request->post())) {
			setlocale(LC_ALL, 'ru_RU.CP1251');//Для работы с русским			
			$equipmentIds = $model->harnesses;
			$equipmentCounts = $model->counts;
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
			$model->equipment_ids = serialize($resultIds);
			$model->save();
			return $this->redirect(['update', 'id' => $model->id]);
		}	
		
		$stages = InstallationStage::find()->where(['config_id' => $id])->orderBy('sort ASC')->all();
		$stagesList = [];
		$i = 0;
		foreach($stages as $item){
			if($item->attachemnt_id){
				$stagesList[$i]['images'] = $item->attachemnt_id;
			}
			$stagesList[$i]['id'] = $item->id;
			$stagesList[$i]['text'] = $item->text;
			$stagesList[$i]['sort'] = $item->sort;
			$i++;
		}
		
		$selectItems = [];
		$selectItems['types'] = Brands::getEquipmentTypes();
		$ids = unserialize($model->equipment_ids);
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
		return $this->render('update', [
			'model' => $model,
			'result' => $selectItems,
			'harness' => Json::encode($namesEquipmentList),
			'equipmentList' => $equipmentList,
			'stage' => $stage,
			'uploadForm' => $uploadForm,
			'stagesList' => $stagesList,
		]);			
    }	
	
	//Скачивание инструкции
	public function actionGetInstruction($id)
	{
		PurchasingOrder::createInstruction($id);
	}

	//AJAX получение списка оборудования для импорта
	public function actionGetEquipment($id)
	{
		$selectItems = [];
		$selectItems['types'] = Brands::getEquipmentTypes();		
		$harness = Brands::getEquipmentList();	

		
		$model = BrandsConfig::findOne($id);
		$ids = unserialize($model->equipment_ids);
		$equipmentList = [];
		foreach($ids as $item){
			$equipment = Equipment::findOne($item[0]);
			$equipmentList[] = [
				'type' => $equipment->type,
				'id' => $equipment->id,
			];
		}
		
		return $this->renderPartial('equipment-list', [
			'equipmentList' => $equipmentList,
			'harness' => Json::encode(Brands::getEquipmentList()),
			'harnessList' => Brands::getEquipmentList(),
			'selectItems' => $selectItems,
		]);				
	}
	
	//Первый таб update конфигурации
    public function actionUpdate($id)
    {
		$model = BrandsConfig::findOne($id);
		$uploadForm = new ImageUploadMultiple();
		$stage = new InstallationStage();
		if ($model->load(Yii::$app->request->post())) {
			setlocale(LC_ALL, 'ru_RU.CP1251');//Для работы с русским			
			$equipmentIds = $model->harnesses;
			$equipmentCounts = $model->counts;
			$resultIds = [];
			$i = 0;
			if($equipmentIds){
				foreach($equipmentIds as $idEq){
					if($idEq){
						if(isset($equipmentCounts[$i])){
							$resultIds[] = [$idEq, $equipmentCounts[$i]];
						}else{
							$resultIds[] = [$idEq, 1];
						}
					}
					$i++;
				}
			}
			$model->equipment_ids = serialize($resultIds);
			$model->save();
			
		}	
		
		$stages = InstallationStage::find()->where(['config_id' => $id])->orderBy('sort ASC')->all();
		$stagesList = [];
		$i = 0;
		foreach($stages as $item){
			if($item->attachemnt_id){
				$stagesList[$i]['images'] = $item->attachemnt_id;
			}
			$stagesList[$i]['id'] = $item->id;
			$stagesList[$i]['text'] = $item->text;
			$stagesList[$i]['sort'] = $item->sort;
			$i++;
		}
		
		$selectItems = [];
		$selectItems['types'] = Brands::getEquipmentTypes();
		$ids = unserialize($model->equipment_ids);
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
		return $this->render('update', [
			'model' => $model,
			'result' => $selectItems,
			'harness' => Json::encode($namesEquipmentList),
			'equipmentList' => $equipmentList,
			'stage' => $stage,
			'uploadForm' => $uploadForm,
			'stagesList' => $stagesList,
		]);			
    }
	
	
	//Второй таб update конфигурации
    public function actionInstruction($id)
    {
		$model = BrandsConfig::findOne($id);
		$uploadForm = new ImageUploadMultiple();
		$stage = new InstallationStage();	
		
		$stages = InstallationStage::find()->where(['config_id' => $id])->orderBy('sort ASC')->all();
		$stagesList = [];
		$i = 0;
		foreach($stages as $item){
			if($item->attachemnt_id){
				$stagesList[$i]['images'] = $item->attachemnt_id;
			}
			$stagesList[$i]['id'] = $item->id;
			$stagesList[$i]['text'] = $item->text;
			$stagesList[$i]['sort'] = $item->sort;
			$i++;
		}

		$namesEquipmentList = Brands::getEquipmentList();
		$equipmentList = [];
		$i = 0;

		return $this->render('instruction', [
			'model' => $model,
			'stage' => $stage,
			'uploadForm' => $uploadForm,
			'stagesList' => $stagesList,
		]);			
    }	
	

	public function actionUpdateEquipment()//Переход на новый формат хранения
	{
		$models = BrandsConfig::find()->all();
		foreach($models as $item){
			$old = unserialize($item->equipment_ids);
			$new = [];
			if($old){
				foreach($old as $id){
					$new[] = [$id, 1];
				}				
			}
			$item->equipment_ids = serialize($new);
			$item->owner_type = 'configuration';
			$item->save();
			// echo '<pre>';
			// var_dump($item);
			// exit;			
		}
	}
	
    public function actionUpdateStage($id)//Рендер вью для редактирования формы, для AJAX
    {
		$model = InstallationStage::findOne($id);
		$uploadForm = new ImageUploadMultiple();
		// if(Yii::$app->request->post()){
			// return Json::encode(Yii::$app->request->post());
		// }
		if ($model->load(Yii::$app->request->post())) {
			$uploadForm->imageFile = UploadedFile::getInstances($uploadForm, 'imageFile');
			// return Json::encode($uploadForm->imageFile);
			$resultLoading = $uploadForm->upload();
			
			if(isset($resultLoading[0])){
				$item = $resultLoading[0];
				$imageRecord = new Images();
				$imageRecord->path = $item['path'];
				$imageRecord->name = $item['fileName'];
				$imageRecord->owner_id = $model->id;
				$imageRecord->owner_type = 'stage';
				$imageRecord->type = $item['type'];
				$imageRecord->save();
				$model->attachemnt_id = $imageRecord->id;
			}
			$model->save();
			// return $this->redirect('/admin/configuration/update/?id='.$model->config_id);
			return true;
		}else{
			return $this->renderPartial('stage_update', [
				'stage' => $model,
				'uploadForm' => $uploadForm
			]);
		}
	}

	
	
	public function actionGetStage($id)
	{
		$model = InstallationStage::findOne($id);
		return $this->renderPartial('stage_get', [
			'stage' => $model,
		]);		
	}
	
    public function actionDeleteStage($id)//Удаление этапа
    {
		$model = InstallationStage::findOne($id);
		$image = Images::findOne($model->attachemnt_id);
		if($image){
			unlink($image->path);//Удаление картинки
			$image->delete();
		}
		$model->delete();
		return true;
	}
	
    public function actionAddStage($ownerId)//Добавляет этап и редиректит обратно на страницу
    {
		$stage = new InstallationStage();
		$uploadForm = new ImageUploadMultiple();
		if ($stage->load(Yii::$app->request->post())) {
			$sort = $stage->sort;
			$stage->config_id = $ownerId;
			$stage->save();//В любом случае сохраняем первую с текстом
			$uploadForm->imageFile = UploadedFile::getInstances($uploadForm, 'imageFile');//Загрузка картинки
			if($uploadForm->imageFile){
				$resultLoading = $uploadForm->upload();				
				$i = 0;
				foreach($resultLoading as $item){
					if($i>0){
						$stage = new InstallationStage();
						$stage->config_id = $ownerId;
						$stage->save();//Если не первый, то создаем новый этап
					}
					$imageRecord = new Images();
					$imageRecord->path = $item['path'];
					$imageRecord->name = $item['fileName'];
					$imageRecord->owner_id = $stage->id;
					$imageRecord->owner_type = 'stage';
					$imageRecord->type = $item['type'];
					$imageRecord->save();
					$stage->attachemnt_id = $imageRecord->id;
					
					$stage->sort = $sort;
					$stage->save();
					$i++;
				}
			}			
		}
		return $this->redirect('/admin/configuration/instruction/?id='.$ownerId);		
	}

    public function actionDelete($id)
    {
		$model = BrandsConfig::findOne($id);
		$model->deleted = 1;//Типа удаление конфигурации
		if($model->save()){
			return true;
		}else{
			return false;
		}
    }	
}
