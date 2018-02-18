<?php
namespace common\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class TopicForm extends Model
{
    public $choice;
	
	public function rules()
    {
        return [
			[['choice'],'safe'],
		];
    }	
}
