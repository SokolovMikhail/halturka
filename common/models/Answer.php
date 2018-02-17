<?php

namespace common\models;

use Yii;
use common\models\Quiz;

/**
 * This is the model class for table "answer".
 *
 * @property integer $id
 * @property integer $question_id
 * @property integer $sort
 * @property string $text_native
 * @property string $text_doc
 * @property string $quiz_redirect_id
 */
class Answer extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'answer';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['question_id', 'sort'], 'integer'],
            [['text_native', 'text_doc', 'quiz_redirect_id'], 'string', 'max' => 4096],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'question_id' => 'Question ID',
            'sort' => 'Порядковый номер',
            'text_native' => 'Текст для пользователя',
            'text_doc' => 'Текст для вставки документ',
            'quiz_redirect_id' => 'Редирект на другой опрос',
        ];
    }
	
	
	public static function getQuizList()
	{
		$items = Quiz::find()->asArray()->all();
		
		$result = [];
		$result[0] = 'Переход не требуется';
		
		$i = 1;
		foreach($items as $item){
			$result[$item['id']] = $item['name'];
			$i++;
		}
		
		return $result;
	}
}
