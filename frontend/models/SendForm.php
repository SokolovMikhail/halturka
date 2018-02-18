<?php
namespace frontend\models;

use Yii;
use yii\base\Model;

/**
 * SendForm form
 */
class SendForm extends Model
{
    public $email;
	
	public function rules()
    {
        return [
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required', 'message' => 'Поле не может быть пустым'],
            ['email', 'email', 'message' => 'Введите корректный адрес почты'],
		];
    }	
	
	public function attributeLabels()
    {
        return [
			'email' => 'E-mail',
        ];
    }	
}
