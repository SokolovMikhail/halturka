<?php

use yii\db\Migration;

class m171122_075930_add_deleted_attribute_in_devices_and_operators extends Migration
{
    public function up()
    {
        $this->addColumn('devices', 'deleted', $this->smallInteger(1)->defaultValue(0));
        $this->addColumn('operators', 'deleted', $this->smallInteger(1)->defaultValue(0));
    }

    public function down()
    {
        echo "m171122_075930_add_deleted_attribute_in_devices cannot be reverted.\n";

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
