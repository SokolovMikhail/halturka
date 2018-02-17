<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use common\models\Topic;
use common\models\Quiz;
use common\models\Question;

/**
 * Topic controller
 */
class QuestionController extends Controller
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

    public function actionCreate($quizId)
    {
		$model = new Question();
		$model->quiz_id = $quizId;
		
		if ($model->load(Yii::$app->request->post())) {			
			$model->save();
			return $this->redirect(['/question/?quizId='.$quizId]);
		}
		
        return $this->render('create', [
			'model' => $model
		]);
    }

	
    public function actionUpdate($id)
    {
		$model = Question::findOne($id);
		
		if ($model->load(Yii::$app->request->post())) {			
			$model->save();
			return $this->redirect(['/question/index/?quizId='.$model->quiz_id]);
		}
		
        return $this->render('update', [
			'model' => $model
		]);
    }	
	
    public function actionIndex($quizId)
    {
		$questions = Question::find()->where(['quiz_id' => $quizId])->asArray()->orderBy('order_number ASC')->all();
		
		// foreach($questions as $item){
			// $item['native_text'] = mb_substr($item['native_text'], 0, );
		// }
		
		$quiz = Quiz::findOne($quizId);
        return $this->render('index', [
			'questions' => $questions,
			'quizId' => $quizId,
			'topicId' => $quiz->topic_id 
		]);
    }	
}
