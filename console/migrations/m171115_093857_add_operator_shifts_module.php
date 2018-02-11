<?php

use yii\db\Migration;

class m171115_093857_add_operator_shifts_module extends Migration
{
    public function up()
    {
		$moduleExist = (new \yii\db\Query())
			->select('id')
			->from('module')
			->where(['id' => 'operatorShifts'])
			->all();
		
		if(!$moduleExist){
			$this->insert('module', [
				'id' => 'operatorShifts',
				'source' => 'frontend\modules\operatorShifts\OperatorShifts',
				'depth' => 1,
				'active' => 0,
				'permit' => NULL
			]);
		}
    }

    public function down()
    {
        echo "m171115_093857_add_operator_shifts_module cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
