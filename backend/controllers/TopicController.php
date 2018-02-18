<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use common\models\Topic;
use common\models\Quiz;
use common\models\Question;
use common\models\Answer;
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
			$quizes = Quiz::find()->where(['topic_id' => $topic->id])->all();
			foreach($quizes as $item){
				$questions = Question::find()->where(['quiz_id' => $item->id])->all();
				foreach($questions as $qstn){
					$answers = Answer::find()->where(['question_id' => $qstn->id])->all();
					foreach($answers as $answ){
						$answ->delete();
					}
					$qstn->delete();
				}
				$item->delete();
			}
			return true;
		}else{
			return false;
		}
    }	
}
