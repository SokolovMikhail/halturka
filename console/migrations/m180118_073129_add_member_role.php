<?php

use yii\db\Migration;

class m180118_073129_add_member_role extends Migration
{
    public function up()
    {
        $roleExist = (new \yii\db\Query())
            ->select('name')
            ->from('auth_item')
            ->where(['name' => 'member'])
            ->all();
        
        if(!$roleExist){
            $this->insert('auth_item', [
                'name' => 'member',
                'type' => 1,
                'description' => 'Базовый пользователь',
            ]);
        }
    }

    public function down()
    {
        echo "m180118_073129_add_member_role cannot be reverted.\n";

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
