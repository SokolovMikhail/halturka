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
class AnswerController extends Controller
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

    public function actionCreate($questionId)
    {
		$model = new Answer();
		
		$quiz = Question::findOne($questionId);
		
		$model->question_id = $quiz->id;
		
		if ($model->load(Yii::$app->request->post())) {			
			$model->save();
			return $this->redirect(['/question/update/?id='.$model->question_id]);
		}
		
        return $this->render('create', [
			'model' => $model
		]);
    }

	
    public function actionUpdate($id)
    {
		$model = Answer::findOne($id);
		
		if ($model->load(Yii::$app->request->post())) {			
			$model->save();
			return $this->redirect(['/question/update/?id='.$model->question_id]);
		}
		
        return $this->render('update', [
			'model' => $model
		]);
    }		
	
    public function actionDelete($id)
    {
		$topic = Answer::findOne($id);
		if($topic->delete()){
			return true;
		}else{
			return false;
		}
    }	
}
