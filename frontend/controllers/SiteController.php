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

/**
 * Site controller
 */
class SiteController extends Controller
{
	public $layout='account.php';
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
		$topics = Topic::find()->asArray()->all();
        return $this->render('index', [
			'topics' => $topics
			]);
    }
	

	public function actionAbout()
    {
        return $this->render('about');
    }
	
	
	public function actionMaintenance()
    {
		$option = AppOptions::find()->where(['option_name' => 'maintenance'])->one();
		if($option && $option->option_value){
			$now = TimeHelper::createUtcDateTime();//Сейчас
			$now->setTimezone(new \DateTimeZone('Europe/Moscow'));//Перевод к Московскому времени
			$maintenanceDate = new \DateTime($option->option_value, new \DateTimeZone('Europe/Moscow'));

			if($now < $maintenanceDate && !(in_array('superadmin', array_keys(Yii::$app->authManager->getRolesByUser(Yii::$app->user->getId()))))){			
				return $this->render('maintenance', ['time' => $maintenanceDate->format('H:i:s d-m-Y')]);
				
			}else{
				return $this->redirect('/site/',302);
			}
		}
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
