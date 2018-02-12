<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "quiz_result".
 *
 * @property integer $id
 * @property string $token_id
 * @property integer $quiz_id
 * @property resource $answers
 */
class QuizResult extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'quiz_result';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['quiz_id'], 'integer'],
            [['answers'], 'string'],
            [['token_id'], 'string', 'max' => 2048],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'token_id' => 'Token ID',
            'quiz_id' => 'Quiz ID',
            'answers' => 'Answers',
        ];
    }
}
