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
            'text_native' => 'Text Native',
            'text_doc' => 'Text Doc',
            'type' => 'Type',
            'order_number' => 'Order Number',
        ];
    }
}
