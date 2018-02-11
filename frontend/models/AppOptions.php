<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "app_options".
 *
 * @property string $id
 * @property string $option_name
 * @property string $option_value
 */
class AppOptions extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'app_options';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['option_name', 'option_value'], 'required'],
            [['option_value'], 'string'],
            [['option_name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'option_name' => 'Option Name',
            'option_value' => 'Option Value',
        ];
    }
	
	
	/**
     * Сохранение/обновление опции
     */
	public static function saveOption($name, $value){
		$option = AppOptions::find()->where(['option_name'=>$name])->one();
		if($option){
			$option->option_value = $value;
			$option->save();
		}
		else{
			$option = new AppOptions;
			$option->option_name = $name;
			$option->option_value = $value;
			$option->save();
		}
	}
	
	
	/**
     * Получение булевой опции
     */
	public static function can($optionName){
		$value = 0;
		$option = AppOptions::find()->where(['option_name'=>$optionName])->one();
		if($option){
			$value = $option->option_value+0;
		}
		return $value;
	}
	
	
	
	/**
     * Получение опции по имени
     */
	public static function getOption($optionName){
		$value = false;
		$option = AppOptions::find()->where(['option_name'=>$optionName])->one();
		if($option){
			$value = $option->option_value;
		}
		return $value;
	}
	
}
