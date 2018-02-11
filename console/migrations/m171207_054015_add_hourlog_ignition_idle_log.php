<?php

use yii\db\Migration;

class m171207_054015_add_hourlog_ignition_idle_log extends Migration
{
    public function up()
    {
		$this->addColumn('hour_log', 'ignition_log', 'BLOB');
		$this->addColumn('hour_log', 'idle_log', 'BLOB');
    }

    public function down()
    {
        echo "m171207_054015_add_hourlog_ignition_idle_log cannot be reverted.\n";

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
