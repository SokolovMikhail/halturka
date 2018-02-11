<?php
namespace frontend\models\forms;

use common\models\User;
use yii\base\Model;
use Yii;
use frontend\models\AppOptions;

class OptionsForm extends Model
{
	public $useWorkStatistic;
	public $company_name;
	public $maintenance;
	
	public function rules()
    {
        return [
			[['useWorkStatistic', 'company_name'], 'required', 'message' => 'Поле не может быть пустым'],
            [['useWorkStatistic'], 'integer', 'message' => 'Допустимо только положительное целое число'],
			[['maintenance'],'safe'],
		];
    }
	
	public function attributeLabels()
    {
        return [
			'useWorkStatistic'	=> 'Показывать статистку по работе вилами',
			'company_name'		=> 'Название компании',
			'maintenance'	=> 'Дата окончания работ на сервере(закарывает доступ всем, кроме суперадмина)'
        ];
    }
	
	public function getOptions()
    {
		$fields = $this->attributeLabels();
		foreach($fields as $k=>$v){
			$option = AppOptions::find()->where(['option_name'=>$k])->one();
			if($option){
				$this->$k = $option->option_value;
			}
			else{
				$this->$k = null;
			}
		}
		
	}
	
	public function saveOptions()
    {
        if ($this->validate()) {
			$fields = $this->attributeLabels();
			foreach($fields as $k=>$v){
				AppOptions::saveOption($k, $this->$k);
			}
			
			return true;
        }
        return null;
    }
}