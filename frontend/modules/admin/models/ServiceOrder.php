<?php

namespace app\modules\admin\models;


class ServiceOrder extends \yii\db\ActiveRecord
{
	
	public $types;
	public $amounts;
	public $ids;
	public $prices;
	public $totalPrices;
	public $owners;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'service_order';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'type', 'amount', 'types', 'amounts', 'ids', 'price', 'total_price', 'prices', 'totalPrices', 'owners'],'safe'],
			[['type'],'required',  'message'=>'Поле не может быть пустым.'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'type' => 'Название услуги',
			'amount' => 'Количество',
			'price' => 'Цена',
			'total_price' => 'Суммарная стоимость'
        ];
    }

	public static function getDb()
    {
        // use the "db_main" application component
        return \Yii::$app->db_main;
    } 
	
}
