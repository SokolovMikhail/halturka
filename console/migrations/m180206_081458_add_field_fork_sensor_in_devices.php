<?php

use yii\db\Migration;

class m180206_081458_add_field_fork_sensor_in_devices extends Migration
{
    public function up()
    {
        $this->addColumn('devices', 'fork_sensor', $this->smallInteger(1)->defaultValue(0));
    }

    public function down()
    {
        echo "m180206_081458_add_field_fork_sensor_in_devices cannot be reverted.\n";

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
