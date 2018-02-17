<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "question".
 *
 * @property integer $id
 * @property integer $quiz_id
 * @property string $text_native
 * @property string $text_doc
 * @property string $type
 * @property integer $order_number
 */
class Question extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'question';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['quiz_id', 'order_number'], 'integer'],
            [['text_native', 'text_doc', 'type'], 'string', 'max' => 4096],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'quiz_id' => 'Quiz ID',
            'text_native' => 'Текст для пользователя',
            'text_doc' => 'Текст для вставки в документ',
            'type' => 'Тип ответа',
            'order_number' => 'Порядковый номер',
        ];
    }
	

    /**
     * @inheritdoc
     */
    public static function getQuestionTypes()
    {
		return [0 => 'Варианты ответа', 10 => 'Открытый ответ'];
	}		
}
