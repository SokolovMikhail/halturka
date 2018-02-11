<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\data\Pagination;
use yii\helpers\FileHelper;
use yii\helpers\Url;
use app\modules\admin\models\Service;

/**
 * HarnessController implements the CRUD actions.
 */
class ServiceController extends Controller
{
	public $layout='/../../views/layouts/account.php';


    public function behaviors()
    {
        return [
			'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
					'actions' => ['index', 'update', 'create', 'delete', 'get-form'],
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

	//Списко всех гарантий
    public function actionIndex()
    {
		$result['items'] = Service::find()->orderBy('sort ASC')->all();
		
		return $this->render('index', [
			'result' => $result,
		]);
    }	
	
	//Создание услуги
    public function actionCreate()
    {
		$model = new Service();
		if ($model->load(Yii::$app->request->post())) {
			if($model->save())
			{
				return $this->redirect(['index']);
			}

		}		
		return $this->render('create', [
			'model' => $model,
		]);
    }
	
	//Update услуги
    public function actionUpdate($id)
    {
		$model = Service::findOne($id);
		
		if ($model->load(Yii::$app->request->post())) {
			if($model->save())
			{
				return $this->redirect(['index']);
			}

		}		
		return $this->render('update', [
			'model' => $model,
		]);
    }
	
	//AJAX получение формы добавления услуги
	public function actionGetForm($type)
	{
		$services = Service::getList();
		
		return $this->renderPartial('service-form', [
			'services' => $services,
			'type' => $type,
		]);				
	}
}
