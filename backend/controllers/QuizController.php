<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use common\models\Topic;
use common\models\Quiz;

/**
 * Topic controller
 */
class QuizController extends Controller
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
                        'actions' => ['create', 'update', 'delete', 'index'],
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

    public function actionCreate($topicId)
    {
		$model = new Quiz();
		$model->topic_id = $topicId;
		
		if ($model->load(Yii::$app->request->post())) {			
			$model->save();
			return $this->redirect(['/quiz/index/?topicId='.$topicId]);
		}
		
        return $this->render('create', [
			'model' => $model
		]);
    }

	
    public function actionUpdate($id)
    {
		$model = Quiz::findOne($id);
		
		if ($model->load(Yii::$app->request->post())) {			
			$model->save();
			return $this->redirect(['/quiz/index/?topicId='.$model->topic_id ]);
		}
		
        return $this->render('update', [
			'model' => $model
		]);
    }	
	
    public function actionIndex($topicId)
    {
		$topic = Topic::findOne($topicId);
		$quizes = Quiz::find()->where(['topic_id' => $topicId])->asArray()->all();
        return $this->render('index', [
			'quizes' => $quizes,
			'topicId' => $topicId,
			'topic' => $topic
		]);
    }

    public function actionDelete($id)
    {
		$topic = Quiz::findOne($id);
		if($topic->delete()){
			return true;
		}else{
			return false;
		}
    }	
}
