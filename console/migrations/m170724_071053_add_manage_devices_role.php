<?php

use yii\db\Migration;

class m170724_071053_add_manage_devices_role extends Migration
{
    public function up()
    {
		$roleExist = (new \yii\db\Query())
			->select('name')
			->from('auth_item')
			->where(['name' => 'manageDevices'])
			->all();
		
		if(!$roleExist){
			$this->insert('auth_item', [
				'name' => 'manageDevices',
				'type' => 2,
				'description' => 'Создание и удаление техники',
			]);
		}
    }

    public function down()
    {
        echo "m170724_071053_add_manage_devices_role cannot be reverted.\n";

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
