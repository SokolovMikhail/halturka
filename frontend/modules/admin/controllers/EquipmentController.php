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
use frontend\models\Equipment;
use yii\data\Pagination;
use app\modules\support\models\Images;
use app\modules\admin\models\ImageUploadSingle;
use yii\web\UploadedFile;

/**
 * EquipmentController implements the CRUD actions.
 */
class EquipmentController extends Controller
{
	public $layout='/../../views/layouts/account.php';


    public function behaviors()
    {
        return [
			'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'update','get-access-list',],
                'rules' => [
                    [
                        'actions' => ['index', 'update', 'create', 'delete',],
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
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }


    public function actionIndex($statusFilter=false)
    {
		//Пагинация
		$statusesList = [];
		if($statusFilter && $statusFilter != 0)
		{
			$statusesList = ['current' => $statusFilter];
			$statusesList += ['availbale' => EquipmentController::GetStatusesFilter()];				
			$query = Equipment::find()->where(['type' => $statusFilter]);
			$countQuery = clone $query;
			$pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 50]);
			$pages->pageSizeParam = false;
			//--------------------------------------------------------------------------------
			$result = ['items' => $query->offset($pages->offset)
				->limit($pages->limit)
				->orderBy('sorting ASC')
				->all()];
		}
		else
		{
			$statusesList = ['current' => 0];
			$statusesList += ['availbale' => EquipmentController::GetStatusesFilter()];			
			$query = Equipment::find();
			$countQuery = clone $query;
			$pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 50]);
			$pages->pageSizeParam = false;
			//--------------------------------------------------------------------------------
			$result = ['items' => $query->offset($pages->offset)
				->limit($pages->limit)
				->orderBy('sorting ASC')
				->all()];
		}

		$result['images'] = [];
		foreach($result['items'] as $item){
			$image = Images::find()->where(['and', ['owner_id' => $item->id, 'owner_type' => 'equipment']])->one();
				$result['images'][$item->id] = $image;
		}

// var_dump($result['images']);
// exit;		
		return $this->render('index', [
			'result' => $result, 'pagenator' => $pages, 'types' => Equipment::getTypeList(), 'statuses_list' => $statusesList
		]);
    }

	//Все типы оборудования
	private static function GetStatusesFilter()
	{
		return [
				['id' => 0, 'name' => 'Все'],
				['id' => 1, 'name' => '10P'],
				['id' => 2, 'name' => '12P'],
				['id' => 3, 'name' => '6P'],
				['id' => 4, 'name' => 'Power'],
				['id' => 5, 'name' => 'Релейный модуль'],
				['id' => 6, 'name' => 'Терминал'],
				['id' => 7, 'name' => 'Кнопка'],
				['id' => 8, 'name' => 'Зуммер'],
				['id' => 9, 'name' => 'Доп. оборудование'],
		];
	}
	
	
    public function actionCreate()
    {
		$model = new Equipment();
		$uploadForm = new ImageUploadSingle();
		
		if ($model->load(Yii::$app->request->post())) {


			
			if($model->save())
			{
				$uploadForm->imageFile = UploadedFile::getInstance($uploadForm, 'imageFile');//Загрузка картинки	
				if($uploadForm->imageFile){
					$resultLoading = $uploadForm->upload();
					$imageRecord = new Images();
					$imageRecord->path = $resultLoading['path'];
					$imageRecord->name = $resultLoading['fileName'];
					$imageRecord->owner_id = $model->id;
					$imageRecord->owner_type = 'equipment';
					$imageRecord->type = $resultLoading['type'];
					$image = Images::find()->where(['and', ['owner_id' => $model->id, 'owner_type' => 'equipment']])->one();
					if($image){
						$image->delete();
					}
					$imageRecord->save();
				}				
				return $this->redirect(['index']);
			}

		}

		return $this->render('create', [
			'model' => $model, 'types' => $model::getTypeList(),
			'uploadForm' => $uploadForm,
		]);
    }

    public function actionUpdate($id)
    {
		$model = $this->findModel($id);
		$uploadForm = new ImageUploadSingle();
		
		if ($model->load(Yii::$app->request->post())) {
			$uploadForm->imageFile = UploadedFile::getInstance($uploadForm, 'imageFile');//Загрузка картинки	
			if($uploadForm->imageFile){
				$resultLoading = $uploadForm->upload();
				if($resultLoading){
					$imageRecord = new Images();
					$imageRecord->path = $resultLoading['path'];
					$imageRecord->name = $resultLoading['fileName'];
					$imageRecord->owner_id = $id;
					$imageRecord->owner_type = 'equipment';
					$imageRecord->type = $resultLoading['type'];
					$image = Images::find()->where(['and', ['owner_id' => $id, 'owner_type' => 'equipment']])->one();
					if($image){
						$image->delete();
					}
					$imageRecord->save();
					$model->save();
					return $this->redirect(['index']);					
				}
				else{
					Yii::$app->getSession()->setFlash('error', 'Не удалось подгрузить картинку. Не тот формат?');
					$model->save();
				}
			}else{
				$model->save();
			}

		}
		$image = Images::find()->where(['and', ['owner_id' => $id, 'owner_type' => 'equipment']])->one();
		return $this->render('update', [
			'model' => $model,
			'types' => $model::getTypeList(),
			'uploadForm' => $uploadForm,
			'image' => $image,
		]);
    }

    public function actionDelete($id)
    {
		$model = $this->findModel($id);
		$model->delete();
		return $this->redirect(['index']);
    }
	
	/**
	* Поиск модели по id
	*/
	protected function findModel($id)
    {
        if (($model = Equipment::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }






}
