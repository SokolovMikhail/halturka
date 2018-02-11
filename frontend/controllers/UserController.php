<?php

namespace frontend\controllers;

use Yii;
use common\models\User;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use frontend\models\helpers\ViewHelper;
use frontend\models\forms\UserForm;



class UserController extends Controller
{
	public $layout='account.php';
	
	
	
    public function behaviors()
    {
        return [
			'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['superadmin'],
                    ],
                ],
				'denyCallback' => function ($rule, $action) {
					return $this->goHome();
				}
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

	
	
    public function actionIndex()
    {
		$items = ArrayHelper::index(User::find()->OrderBy('username ASC')->asArray()->all(), 'id');
		$assignments = (new \yii\db\Query())
			->select([
				'a.user_id',
				'i.name',
				'i.description',
				'i.type',
			])
			->from('auth_assignment a')
			->innerJoin('auth_item i', 'a.item_name=i.name')
			->all();
		
		foreach($assignments as $a){
			if(isset($items[$a['user_id']])){
				$items[$a['user_id']]['assignments'][$a['type']][$a['name']] = $a['description'];
			}
		}

		return $this->render('index', [
			'items'	=> $items,
		]);
    }

	
	
    public function actionCreate()
    {
        $model = new UserForm();
		$model->scenario = 'create';
		
        if ($model->load(Yii::$app->request->post()) && $user = $model->create()) {
            return $this->redirect(['update', 'id' => $user->id]);
        } else {
			$result = $this->getFormParams($model);
            return $this->render('create', ['model' => $model, 'result' => $result]);
        }
    }

	
	
    public function actionUpdate($id)
    {
        $model = new UserForm();
		$model->getUser($id);
        if ($model->load(Yii::$app->request->post())) {
			if(!isset(Yii::$app->request->post()['UserForm']['userStoragesArray'])){
				$model->userStoragesArray = [];
			}
			$model->update($id);
			$model->password='';
			$model->passwordRepeat='';
        }
		$result = $this->getFormParams($model);
		$notifyTabModules = [
			'mailingreports' => [
				'main/get-user-reports-list' => ['userId' => $id],
			],
			'smssender' => [
				'main/get-user-reports-list' => ['userId' => $id],
			]			
		];
		
		$result += [
			'notifyTabModules' => Yii::$app->moduleManager->runActions($notifyTabModules),
		];
		return $this->render('update', ['model' => $model, 'result' => $result]);
    }

	
	
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

	
	
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	
	
	
	private function getFormParams($model){
		$result = [
			'rootStorages'	=> ArrayHelper::map(Yii::$app->storagesData->find()->onlyActive()->onlyAvailable()->rootTypes()->asArray(), 'id', 'name'),
			'storagesTree'	=> ViewHelper::storagesTreeBuilder(
				Yii::$app->storagesData->find()->onlyActive(false)->allTypes()->asTree(), 
				0, 
				'UserForm[userStoragesArray]', 
				$model->userStoragesArray, 
				$type='input'
			),
			'roles'			=> ArrayHelper::map(Yii::$app->authManager->roles, 'name', 'description'),
			'permissions'	=> ArrayHelper::map(Yii::$app->authManager->permissions, 'name', 'description'),
		];
		return $result;
	}
}
