<?php
namespace frontend\controllers;

use Yii;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use frontend\models\AppOptions;
use frontend\models\helpers\TimeHelper;
use common\models\TopicForm;
use common\models\Topic;
use common\models\Quiz;
use common\models\Question;
use common\models\Answer;
use common\models\AnswerForm;
/**
 * Site controller
 */
class QuizController extends Controller
{
	public $layout='main_base.php';
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup', 'upload'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
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
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }


	public function actionIndex($quizId, $questionId = false)
    {
		$quiz = Quiz::findOne($quizId);				
		if($questionId){
			$prevQuestion = Question::findOne($questionId);
			
			$questions = Question::find()->where(['>', 'order_number', $prevQuestion->order_number])->orderBy('order_number ASC')->asArray()->all();
			if(count($questions)){
				$question = $questions[0];
			}else{
echo '<pre>';
var_dump('Конец опроса');
exit;				
			}			
		}else{			
			$questions = Question::find()->where(['quiz_id' => $quiz->id])->orderBy('order_number ASC')->asArray()->all();
						
			$question = $questions[0];						
		}
		
		$model = new AnswerForm();		
		if ($model->load(Yii::$app->request->post())) {	
			$answer = Answer::findOne($model->answer);
			if($answer->quiz_redirect_id){
				return $this->redirect(['/quiz/index/?quizId='.$answer->quiz_redirect_id]);
			}else{
				return $this->redirect(['/quiz/index/?quizId='.$quiz->id.'&questionId='.$question['id']]);
			}
		}
		
		$answers = Answer::find()->where(['question_id' => $question['id']])->asArray()->all();
		return $this->render('quiz', [
			'answers' => $answers,
			'question' => $question 
		]);

    }
}
