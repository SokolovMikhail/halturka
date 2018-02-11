<?php

use yii\db\Migration;

class m171110_091757_speed_sensor_attribute extends Migration
{
    public function up()
    {
		$this->addColumn('devices', 'speed_sensor', $this->smallInteger(1)->defaultValue(0));
    }

    public function down()
    {
        echo "m171110_091757_speed_sensor_attribute cannot be reverted.\n";

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
