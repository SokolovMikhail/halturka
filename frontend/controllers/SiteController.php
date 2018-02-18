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
use common\models\Topic;
use common\models\TopicForm;
use common\models\Quiz;

/**
 * Site controller
 */
class SiteController extends Controller
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


	public function actionIndex()
    {
		$model = new TopicForm();
		if ($model->load(Yii::$app->request->post())) {
			return $this->redirect(['/site/opros/?topicId='.$model->choice]);
		}
		$topics = Topic::find()->asArray()->all();
        return $this->render('index', [
			'topics' => $topics
			]);
    }
	

	public function actionAbout()
    {
        return $this->render('about');
    }
	
	
	public function actionOpros($topicId) 
	{   
		$model = new TopicForm();
		if ($model->load(Yii::$app->request->post())) {
			return $this->redirect(['/process/index/?quizId='.$model->choice]);
		}		
		$quizs = Quiz::find()->where(['topic_id' => $topicId])->asArray()->all();
        return $this->render('questions', [
			'quiz' => $quizs 
			]);
	}
	
		
    public function actionSignup()
    {
        echo 'Signup';
		// $model = new SignupForm();
        // if ($model->load(Yii::$app->request->post())) {
            // if ($user = $model->signup()) {
                // if (Yii::$app->getUser()->login($user)) {
                    // return $this->goHome();
                // }
            // }
        // }

        // return $this->render('signup', [
            // 'model' => $model,
        // ]);
    }

    public function actionRequestPasswordReset()
    {
        echo 'RequestPasswordReset';
        // $model = new PasswordResetRequestForm();
        // if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            // if ($model->sendEmail()) {
                // Yii::$app->getSession()->setFlash('success', 'Check your email for further instructions.');

                // return $this->goHome();
            // } else {
                // Yii::$app->getSession()->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
            // }
        // }

        // return $this->render('requestPasswordResetToken', [
            // 'model' => $model,
        // ]);
    // }

    // public function actionResetPassword($token)
    // {
        // try {
            // $model = new ResetPasswordForm($token);
        // } catch (InvalidParamException $e) {
            // throw new BadRequestHttpException($e->getMessage());
        // }

        // if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            // Yii::$app->getSession()->setFlash('success', 'New password was saved.');

            // return $this->goHome();
        // }

        // return $this->render('resetPassword', [
            // 'model' => $model,
        // ]);
    }
}
