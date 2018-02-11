<?php

namespace frontend\models\entities;

use Yii;

class Module extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'module';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'source', 'depth', 'active'], 'required'],
            [['depth', 'active'], 'integer'],
            [['id', 'source'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'source' => 'source',
            'depth' => 'depth',
            'active' => 'active',
        ];
    }
}
