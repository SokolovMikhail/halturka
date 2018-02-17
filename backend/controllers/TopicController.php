<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use common\models\Topic;

/**
 * Topic controller
 */
class TopicController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['create', 'update', 'delete'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionCreate()
    {
		$model = new Topic();
		
		if ($model->load(Yii::$app->request->post())) {			
			$model->save();
			return $this->redirect(['/']);
		}
		
        return $this->render('create', [
			'model' => $model
		]);
    }

	
    public function actionUpdate($id)
    {
		$model = Topic::findOne($id);
		
		if ($model->load(Yii::$app->request->post())) {			
			$model->save();
		}
		
        return $this->render('update', [
			'model' => $model
		]);
    }


    public function actionDelete($id)
    {
		$topic = Topic::findOne($id);
		if($topic->delete()){
			return true;
		}else{
			return false;
		}
    }	
}
