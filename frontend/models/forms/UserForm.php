<?php
namespace frontend\models\forms;

use Yii;
use common\models\User;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use frontend\models\UserStorage;

class UserForm extends Model
{
    public $id;
    public $username;
    public $name;
    public $email;
	public $phone;
    public $password;
    public $passwordRepeat;
	public $mainRole;
	public $storage_id;
	public $user_storages_array;
	public $permissions;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'filter', 'filter' => 'trim'],
            [['username', 'name'], 'required', 'message' => 'Поле не может быть пустым'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Такой логин уже занят.', 'on' => 'create'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required', 'message' => 'Поле не может быть пустым'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Такой email уже занят.', 'on' => 'create'],

            ['password', 'required', 'on' => 'create', 'message' => 'Поле не может быть пустым'],
            ['password', 'string', 'min' => 6, 'message' => 'Минимальная длина пароля 6 символов'],
			
			['passwordRepeat', 'required', 'on' => 'create', 'message' => 'Поле не может быть пустым'],
            ['passwordRepeat', 'string', 'min' => 6, 'message' => 'Минимальная длина пароля 6 символов'],
			['passwordRepeat', 'compare', 'compareAttribute' => 'password', 'message' => 'Пароли не совпадают'],
			
            ['phone', 'filter', 'filter' => 'trim'],
            // ['phone', 'required', 'message' => 'Поле не может быть пустым'],
            ['phone', 'string'],			
			
			['mainRole', 'string'],
			
			['storage_id', 'integer'],
			[['userStoragesArray', 'permissions'], 'safe'],
        ];
    }
	
	public function attributeLabels()
    {
        return [
			'username'			=> 'Логин',
			'name'				=> 'ФИО',
			'email' 			=> 'E-mail',
			'password'			=> 'Пароль',
			'passwordRepeat'	=> 'Подтверждение пароля',
			'mainRole'			=> 'Роль пользователя',
			'storage_id'		=> 'Отдел по умолчанию',
			'permissions'		=> 'Права пользователя',
			'phone'				=> 'Сотовый телефон'
        ];
    }
	
	public function getUser($id){
		$this->id = $id;
		$user = User::findOne($id);
		$this->username = $user->username;
		$this->name = $user->name;
		$this->email = $user->email;
		$this->storage_id = $user->storage_id;
		$this->phone = $user->phone;
		$this->mainRole = $user->getRole();
		$this->permissions =$user->getPermissions();
	}

    public function create()
    {
        if ($this->validate()) {
            $user = new User();
            $user->username = $this->username;
            $user->name = $this->name;
            $user->email = $this->email;
			$user->phone = $this->phone;
            $user->storage_id = $this->storage_id;
            $user->setPassword($this->password);
            $user->generateAuthKey();
            $user->save();
			$this->refreshUserRoles($user->id);
			$this->refreshUserStorages($user->id);
            return $user;
        }

        return null;
    }
	
	public function update($id)
    {
        if ($this->validate($id)) {
            $user = User::findOne($id);
            $user->username = $this->username;
			$user->name = $this->name;
            $user->email = $this->email;
			$user->storage_id = $this->storage_id;
			$user->phone = $this->phone;
			if (!empty($this->password)){
				$user->setPassword($this->password);
				$user->generateAuthKey();
			}
            $user->save();
			$this->refreshUserRoles($id);
			$this->refreshUserStorages($id);
            return $user;
        }

        return null;
    }
	
	
	/**
     * Связаные данные
     */
	public function getUserStorages()
    {
		if(isset($this->id)){
			return UserStorage::find()->where(['user_id' => $this->id])->all();
		}
		else{
			return [];
		}
    }
	
	
	/**
     * Склады, доступные пользователю
     */
	public function getUserStoragesArray()
	{
		if ($this->user_storages_array === null){
			$this->user_storages_array = ArrayHelper::map($this->userStorages, 'id', 'storage_id');
		}
		return $this->user_storages_array;
	}
	
	public function setUserStoragesArray($value)
    {
        $this->user_storages_array = $value; 
    }
	
	private function refreshUserStorages($id){
		$resavedItems = [];
		$this->user_storages_array = is_array($this->user_storages_array) ? $this->user_storages_array : []; 
		if(!in_array($this->storage_id, $this->user_storages_array)){
			$this->user_storages_array[] = $this->storage_id;
		}
		$oldItems = $this->userStorages;
		$newItems = $this->user_storages_array;
		if(is_array($newItems)){
			foreach($oldItems as $oldItem){
				if(!in_array($oldItem->storage_id, $newItems)){
					UserStorage::deleteAll(['and',['user_id'=>$id],['storage_id'=>$oldItem->storage_id]]);
				}
				$resavedItems[] = $oldItem->storage_id;
			}
			foreach($newItems as $newItem){
				if(!in_array($newItem, $resavedItems)){
					$access = new UserStorage;
					$access->user_id = $id;
					$access->storage_id = $newItem;
					$access->save();
				}
			}
		}
		else{
			UserStorage::deleteAll(['user_id'=>$id]);
		}
	}
	
	
	/**
     * Роль и права пользователя
     */
	private function refreshUserRoles($id){
		Yii::$app->authManager->revokeAll($id);
		$userRole = Yii::$app->authManager->getRole($this->mainRole);
		Yii::$app->authManager->assign($userRole, $id);
		if($this->permissions){
			foreach($this->permissions as $permission){
				$userPermission = Yii::$app->authManager->getPermission($permission);
				Yii::$app->authManager->assign($userPermission, $id);
			}
		}
	}
	
}
