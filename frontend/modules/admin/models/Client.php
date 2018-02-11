<?php

namespace app\modules\admin\models;


class Client extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'clients';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'],'safe'],
			[['name'],'required',  'message'=>'Поле не может быть пустым.'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'name' => 'Название клиента',
        ];
    }

	public static function getDb()
    {
        // use the "db_main" application component
        return \Yii::$app->db_main;
    } 

	public static function getAllClients()
	{
		$clients = Client::find()->all();
		$result = [];
		$result[0] = '';
		$i = 1;
		foreach($clients as $item){
			$result[$i] = $item->name;
			$i++;
		}
		return $result;
	}

}
