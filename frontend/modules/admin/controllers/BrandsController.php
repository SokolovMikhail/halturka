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
use app\modules\admin\models\ImageUploadSingle;
use yii\web\UploadedFile;
use CURLFile;
use frontend\models\LogCrude24h;

/**
 * AdministrationController implements the CRUD actions.
 */
class BrandsController extends Controller
{
	public $layout='/../../views/layouts/account.php';
	public $enableCsrfValidation = false;

    public function behaviors()
    {
        return [
			'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'update', 'create', 'delete', 'get-access-list', 'copy', 'image'],
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
                    // 'download' => ['post'],
                // ],
            ],
        ];
    }
	
	
	//Списко всех брендов
    public function actionIndex($brandFilter = false)
    {
		if($brandFilter){
			$filterItems['brands_current'] = $brandFilter;
		}else{
			$filterItems['brands_current'] = 'all';
		}
		$filterItems['brands'] = Brands::getAllBrands();

		if($brandFilter && $brandFilter!='all'){
			$result = ['items' => Brands::find()->where(['brand' => $brandFilter, 'deleted' => 0])->orderBy('brand ASC')->all()];
		}else{
			$result = ['items' => Brands::find()->where(['deleted' => 0])->orderBy('brand ASC')->all()];
		}
		foreach($result['items'] as $item){
			if($item->pseudonym){//Проверка на наличие псевдонимов и сериализация в текст для вывода
				$pseudonyms = unserialize($item->pseudonym);
				$item->pseudonym = $pseudonyms[0];
				for($i = 1; $i < count($pseudonyms); $i++){
					$item->pseudonym .= ', '.$pseudonyms[$i];
				}
			}
			$configurations = BrandsConfig::find()->where(['owner_id' => $item->id, 'owner_type' => 'configuration', 'deleted' => 0])->all();
			$item->series = '';
			$i = 0;
			foreach($configurations as $config){
				if($i==0){
					$item->series .= $config->checked ? '<mark class="green-background">' . $config->name . '</mark>' : '<mark class="pink-background">' . $config->name . '</mark>';
				}
				else{
					$item->series .= $config->checked ? ', ' . '<mark class="green-background">' . $config->name . '</mark>' :  ', ' . '<mark class="pink-background">' . $config->name . '</mark>';
				}
				
				$i++;
			}
		}
		$result['images'] = [];
		foreach($result['items'] as $item){
			$image = Images::find()->where(['and', ['owner_id' => $item->id, 'owner_type' => 'brand']])->one();
			$result['images'][$item->id] = $image;
		}
		return $this->render('index', [
			'result' => $result,
			'filterItems' => $filterItems
		]);
    }

	
	//Создание бренда
    public function actionCreate()
    {
		$uploadForm = new ImageUploadSingle();
		$model = new Brands();
		$selectItems = [];
		$selectItems['types'] = Brands::getEquipmentTypes(); 
		if ($model->load(Yii::$app->request->post())) {
			
			setlocale(LC_ALL, 'ru_RU.CP1251');//Для работы с русским
			$model->brand = strtoupper($model->brand);
			$model->model = strtoupper($model->model);
			$model->series = strtoupper($model->series);
			$model->pseudonym = serialize(explode("\r", $model->pseudonym));
			$model->save();
					//-------Добавление картинки---------
					
					$uploadForm->imageFile = UploadedFile::getInstance($uploadForm, 'imageFile');//Загрузка картинки
					if($uploadForm->imageFile){
						
						$resultLoading = $uploadForm->upload();
						$imageRecord = new Images();
						$imageRecord->path = $resultLoading['path'];
						$imageRecord->name = $resultLoading['fileName'];
						$imageRecord->owner_id = $model->id;
						$imageRecord->owner_type = 'brand';
						$imageRecord->type = $resultLoading['type'];
						$imageRecord->save();
					}					
					//----------------
			if($model->getErrors()){
				$message = '';
				foreach($model->getErrors() as $k=>$s){
					$message.= $k.', ';
				}
				$model->pseudonym = unserialize($model->pseudonym);
				$model->pseudonym = implode($model->pseudonym);				
				Yii::$app->getSession()->setFlash('error', 'Повторяются псевдонимы: '.$message);
			}
			else{
				return $this->redirect(['update', 'id' => $model->id]);
			}
		}

		return $this->render('create', [
			'model' => $model,
			'license' => Brands::getLicensesList(),
			'type' => Brands::getTehTypeList(),
			'relay' => Brands::getRelayList(),
			'harness' => Json::encode(Brands::getEquipmentList()),
			'result' => $selectItems,
			'voltage' => Brands::getVoltage(),
			'uploadForm' => $uploadForm,
		]);
    }

	public function checkMainDevices($model)//Буфферный action. TODO:рассмотреть и удалить
	{
		$bdModel = Brands::findOne($model->id);

		$mainDevices = MainDevices::find()->where(['and',['model' => $bdModel->model, 'model_id' => $bdModel->id]])->all();
		foreach($mainDevices as $item){
			$item->model = $model->model;
			$item->save();
		}
	}
	
	//Update бренда
    public function actionUpdate($id)
    {
		$uploadForm = new ImageUploadSingle();
		$model = $this->findModel($id);
		if($model && $model->pseudonym){
			$model->pseudonym = unserialize($model->pseudonym);
			$model->pseudonym = implode($model->pseudonym);
		}
		if ($model->load(Yii::$app->request->post())){
			//Загрузка картинки
			$uploadForm->imageFile = UploadedFile::getInstance($uploadForm, 'imageFile');//Загрузка картинки	
			if($uploadForm->imageFile){
				$resultLoading = $uploadForm->upload();
				$imageRecord = new Images();
				$imageRecord->path = $resultLoading['path'];
				$imageRecord->name = $resultLoading['fileName'];
				$imageRecord->owner_id = $id;
				$imageRecord->owner_type = 'brand';
				$imageRecord->type = $resultLoading['type'];
				$image = Images::find()->where(['and', ['owner_id' => $id, 'owner_type' => 'brand']])->one();
				if($image){
					$image->delete();
				}
				$imageRecord->save();
			}					
			//---------------------
			setlocale(LC_ALL, 'ru_RU.CP1251');//Для работы с русским
			$model->brand = strtoupper($model->brand);
			$model->model = strtoupper($model->model);
			$model->series = strtoupper($model->series);
			$this->checkMainDevices($model);
			if($model->pseudonym && strlen(trim($model->pseudonym, " ")) > 0){			
				$model->pseudonym = serialize(explode("\r", $model->pseudonym));
			}else{
				$model->pseudonym = NULL;
			}
			
			$model->save();

			if($model->getErrors()){//Проверка на наличие ошибок в повторении псевдонимов
				$message = '';
				foreach($model->getErrors() as $k=>$s){
					$message.= $k.', ';
				}
				$model->pseudonym = unserialize($model->pseudonym);
				$model->pseudonym = implode($model->pseudonym);				
				Yii::$app->getSession()->setFlash('error', 'Уже задействованы псевдонимы: '.$message);
			}
			else{				
				if($model && $model->pseudonym){
					$model->pseudonym = unserialize($model->pseudonym);
					$model->pseudonym = implode($model->pseudonym);
				}
			}
		}
		
		$selectItems = [];
		$selectItems['types'] = Brands::getEquipmentTypes();
		if($model)
		{			
			$configs = BrandsConfig::find()->where(['owner_id' => $model->id, 'deleted' => 0, 'owner_type' => 'configuration'])->all();
			$j = 0;
			foreach($configs as $conf){
				$selectItems['configs'][$j]['id'] = $conf->id;
				$selectItems['configs'][$j]['name'] = $conf->name;
				$selectItems['configs'][$j]['checked'] = $conf->checked;
				$j++;
			}
		}
		
		$image = Images::find()->where(['and', ['owner_id' => $id, 'owner_type' => 'brand']])->one();

		return $this->render('update', [
			'model' => $model,
			'license' => Brands::getLicensesList(),
			'type' => Brands::getTehTypeList(),
			'relay' => Brands::getRelayList(),
			'harness' => Json::encode(Brands::getEquipmentList()),
			'result' => $selectItems,
			'voltage' => Brands::getVoltage(),
			'uploadForm' => $uploadForm,
			'image' => $image,
		]);
    }

	//Удаление(пока не используется)
    public function actionDelete($id)
    {
		$model = $this->findModel($id);
		$model->deleted = 1;
		$model->save();
		return $this->redirect(['index']);
    }
	
	//Копирование бренда
    public function actionCopy($id)
    {
		$parent = $this->findModel($id);
		$model = new Brands();
		$model->brand = $parent->brand;
		$model->model = $parent->model;
		$model->license = $parent->license;
		$model->type = $parent->type;
		$model->release_date = $parent->release_date;
		$model->voltage = $parent->voltage;
		$model->save();
		$configs = BrandsConfig::find()->where(['owner_id' => $id, 'deleted' => 0, 'owner_type' => 'configuration'])->all();
		foreach($configs as $item){
			$confNew = new BrandsConfig();
			$confNew->name = $item->name;
			$confNew->equipment_ids = $item->equipment_ids;
			$confNew->comments = $item->comments;
			$confNew->owner_id = $model->id;
			$confNew->owner_type = 'configuration';
			$confNew->save();
		}
		return $this->redirect(['update', 'id' => $model->id]);
    }
	
	/**
	* Поиск Бренда по id
	*/
	protected function findModel($id)
    {
        if (($model = Brands::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
