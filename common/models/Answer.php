<?php

namespace common\models;

use Yii;

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
            'sort' => 'Sort',
            'text_native' => 'Text Native',
            'text_doc' => 'Text Doc',
            'quiz_redirect_id' => 'Quiz Redirect ID',
        ];
    }
}
