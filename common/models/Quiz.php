<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "quiz".
 *
 * @property integer $id
 * @property integer $topic_id
 * @property string $name
 * @property string $description
 */
class Quiz extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'quiz';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['topic_id'], 'integer'],
            [['name', 'template_name'], 'string', 'max' => 512],
            [['description'], 'string', 'max' => 2048],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'topic_id' => 'Topic ID',
            'name' => 'Название',
            'description' => 'Описание',
			'template_name' => 'Название используемого шаблона'
        ];
    }
	
}
