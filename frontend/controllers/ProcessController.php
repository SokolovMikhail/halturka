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
use frontend\models\SendForm;
use yii\helpers\FileHelper;
/**
 * Site controller
 */
class ProcessController extends Controller
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
			$session = Yii::$app->session;
			
			$prevQuestion = Question::findOne($questionId);
			
			$questions = Question::find()->where(['>', 'order_number', $prevQuestion->order_number])->orderBy('order_number ASC')->asArray()->all();
			if(count($questions)){
				$question = $questions[0];
			}else{
				return $this->redirect(['/process/result/?quizId='.$quizId]);
			}			
		}else{
			//Создание токена и запись его в cookie, создание записи о ведении опроса
			$token = (new \DateTime())->getTimeStamp() + rand(0, 500000);
			
			$session = Yii::$app->session;
			$session->open();
			
			$session['token'] = [
				'value' => $token,
			];

			$session['results'] = [
				'questions' => [],
			];
			
			//--------------------------------------
			$questions = Question::find()->where(['quiz_id' => $quiz->id])->orderBy('order_number ASC')->asArray()->all();
						
			$question = $questions[0];						
		}
		
		$model = new AnswerForm();		
		if ($model->load(Yii::$app->request->post())) {
			$results = $session['results'];
			
			$results['questions'][$question['id']] = $model->answer;
			$session['results'] = [
				'questions' => $results['questions'],
			];			
			
			if($question['type'] == 0){
				$answer = Answer::findOne($model->answer);
				if($answer->quiz_redirect_id){
					return $this->redirect(['/process/index/?quizId='.$answer->quiz_redirect_id]);
				}else{
					return $this->redirect(['/process/index/?quizId='.$quiz->id.'&questionId='.$question['id']]);
				}				
			}else{
				return $this->redirect(['/process/index/?quizId='.$quiz->id.'&questionId='.$question['id']]);
			}
		}
		
		$answers = Answer::find()->where(['question_id' => $question['id']])->asArray()->all();
		return $this->render('quiz', [
			'answers' => $answers,
			'question' => $question 
		]);

    }
	
	
	public function actionResult($quizId)
	{
		$quiz = Quiz::findOne($quizId);
		$session = Yii::$app->session;
		$model = new SendForm();
		if ($model->load(Yii::$app->request->post())) {
			// $files = FileHelper::findFiles(Yii::getAlias('@backend').'\web\uploads',['recursive'=>FALSE, 'only'=>['*.doc','*.docx']]);
			$objPHPWord =  new \PhpOffice\PhpWord\PhpWord();
			$document = $objPHPWord->loadTemplate(Yii::getAlias('@backend').'\web\uploads/'.$quiz->template_name);
			$results = $session['results']['questions'];

			foreach($results as $questionId=>$answerId){
				$question = Question::findOne($questionId);
				$answer = Answer::findOne($answerId);
				$textToPaste = '';
				if($answer){
					$textToPaste = $answer->text_doc;
				}else{
					$textToPaste = $answerId;
				}				
				$document->setValue('question_'.$question->order_number, $textToPaste);
			}
			$newFileName = $session['token']['value'];
			$file = 'uploads/docs/'.$newFileName.'.docx';
			$document->saveAs($file);
			$this->sendToUser($model->email, $file);
			unlink($file);
			$session->close();
			$session->destroy();
			//Конец и переход на экран благодарности
		}	
		
		return $this->render('result', [
			'model' => $model,
		]);			
	}
	
	public static function sendToUser($addressTo, $attachement)
	{
		$mail = Yii::$app->mailer->compose()
		->setFrom(Yii::$app->mailer->getTransport()->getUsername())
		->setTo($addressTo)
		->setSubject('Тест')
		->setHtmlBody('<h1>Hello</h1>');
		$mail->attach($attachement);

		$mail->send();
	}
}
