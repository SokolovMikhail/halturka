<?php
namespace common\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class AnswerForm extends Model
{
    public $answer;
	
	public function rules()
    {
        return [
			[['answer'],'safe'],
		];
    }	
}
