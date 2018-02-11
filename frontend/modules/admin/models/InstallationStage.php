<?php

namespace app\modules\admin\models;


class InstallationStage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'installation_stage';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['text', 'attachemnt_id', 'config_id', 'sort'],'safe'],
			['sort', 'default', 'value' => 0],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'text' => 'Комментарий',
			'sort' => 'Порядковый номер',
        ];
    }

	public static function getDb()
    {
        // use the "db_main" application component
        return \Yii::$app->db_main;
    } 

	public static function getStagesList()
	{
		return [
			0 => 'Подключение релейника',
			1 => 'Подключение считывателя',
			2 => 'Общий вид',
			3 => 'Расположение Терминала',
			4 => 'Расположение Релейного модуля',
			5 => 'Точка подключения Минуса',
			6 => 'Подключение замка зажигания',
			7 => 'Расположение считывателя',
			8 => 'Расположение антенны',
			9 => 'Подключение CAN',
			10 => 'Датчик скорости',
		];
	}
}
