<?php

namespace app\modules\admin\models;


class Service extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'services';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'sort', 'default_price'],'safe'],
			[['name'],'required',  'message'=>'Поле не может быть пустым.'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'name' => 'Название услуги',
			'sort' => 'Сортировка',
			'default_price' => 'Цена по умолчанию'
        ];
    }

	public static function getDb()
    {
        // use the "db_main" application component
        return \Yii::$app->db_main;
    } 
	
	public static function getList()
	{
		$result = [];
		$result[0] = '';
		$services = Service::find()->all();
		foreach($services as $item){
			$result[$item->id] = $item->name;
		}
		return $result;
	}
	
	public static function getPriceList()
	{
		$result = [];
		$services = Service::find()->all();
		foreach($services as $item){
			$result[$item->id] = $item->default_price ? $item->default_price : 0;
		}
		return $result;
	}	
}
