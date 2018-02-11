<?php

use yii\db\Migration;

class m170724_100358_add_analitics_role extends Migration
{
    public function up()
    {
		$roleExist = (new \yii\db\Query())
			->select('name')
			->from('auth_item')
			->where(['name' => 'viewAnalytics'])
			->all();
		
		if(!$roleExist){
			$this->insert('auth_item', [
				'name' => 'viewAnalytics',
				'type' => 2,
				'description' => 'Просмотр аналитических отчетов',
			]);
		}
    }

    public function down()
    {
        echo "m170724_100358_add_analitics_role cannot be reverted.\n";

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
