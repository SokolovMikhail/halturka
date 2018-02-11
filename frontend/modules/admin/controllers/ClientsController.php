<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\modules\admin\models\Client;


/**
 * ClientsController implements the CRUD actions.
 */
class ClientsController extends Controller
{
	public $layout='/../../views/layouts/account.php';


    public function behaviors()
    {
        return [
			'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'update', 'create'],
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

	//Списко всех клиентов
    public function actionIndex()
    {
		$result['items'] = Client::find()->all();
		
		return $this->render('index', [
			'result' => $result,
		]);
    }


	//Создание клиента
    public function actionCreate()
    {
		$model = new Client();
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


	
	//Update клиента
    public function actionUpdate($id)
    {
		$model = Client::findOne($id);
		
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

}
