<?php

use yii\db\Migration;

class m171108_095746_create_speed_hour_log extends Migration
{
    public function up()
    {
        $this->createTable('speed_hour_log', [
            'id' => $this->primaryKey(),
			'operator_id' => $this->integer(),
			'device_id' => $this->integer(),
			'log_date' => 'DATETIME',
			'av_speed' => $this->integer(),
			'max_speed' => $this->integer(),
			'distance' => $this->integer(),
			'speed_log' => 'BLOB',
			'distribution' => 'BLOB',
			'archive' => $this->integer(),
        ]);
    }

    public function down()
    {
        echo "m171108_095746_create_speed_hour_log cannot be reverted.\n";

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
