<?php

use yii\db\Migration;

class m170711_123807_add_new_role extends Migration
{
    public function up()
    {
		$roleExist = (new \yii\db\Query())
			->select('name')
			->from('auth_item')
			->where(['name' => 'deviceServer'])
			->all();
		
		if(!$roleExist){
			$this->insert('auth_item', [
				'name' => 'deviceServer',
				'type' => 2,
				'description' => 'Сервер устройств',
			]);
		}
    }

    public function down()
    {
        echo "m170711_123807_add_new_role cannot be reverted.\n";

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
