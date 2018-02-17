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
		$questions = Question::find()->where(['quiz_id' => $quizId])->orderBy('order_number ASC')->asArray()->all();
		
		$orderNumber = 1;
		if(count($questions)){
			$orderNumber = $questions[count($questions) - 1]['order_number'] + 1;
		}
			
		$model = new Question();
		
		$quiz = Quiz::findOne($quizId);
		$model->quiz_id = $quiz->id;
		$model->order_number = $orderNumber;
		
		if ($model->load(Yii::$app->request->post())) {			
			$model->save();
			return $this->redirect(['/question/update/?id='.$model->id]);
		}
		
        return $this->render('create', [
			'model' => $model
		]);
    }

	
    public function actionUpdate($id)
    {
		$model = Question::findOne($id);
		
		$answers = Answer::find()->where(['question_id' => $model->id])->asArray()->orderBy('sort ASC')->all();
		
		
		
		if ($model->load(Yii::$app->request->post())) {			
			$model->save();
			return $this->redirect(['/question/index/?quizId='.$model->quiz_id]);
		}
		
        return $this->render('update', [
			'model' => $model,
			'answers' => $answers
		]);
    }	
	
    public function actionIndex($quizId)
    {
		$questions = Question::find()->where(['quiz_id' => $quizId])->asArray()->orderBy('order_number ASC')->all();
		
		foreach($questions as $key=>$item){
			if(strlen($item['text_native']) > 256){
				$questions[$key]['text_native'] = mb_substr($item['text_native'], 0, 256) . '...';
			}
		}
		
		$quiz = Quiz::findOne($quizId);
		
        return $this->render('index', [
			'questions' => $questions,
			'quizId' => $quiz->id,
			'topicId' => $quiz->topic_id,
			'quiz' => $quiz
		]);
    }	
	
    public function actionDelete($id)
    {
		$topic = Question::findOne($id);
		if($topic->delete()){
			return true;
		}else{
			return false;
		}
    }	
}
