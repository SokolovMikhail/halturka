<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `sms_subscribe`.
 */
class m170629_105002_create_sms_subscribe_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('sms_subscribe', [
            'id' => $this->primaryKey(),
			'user_id' => $this->integer(),
			'interval' => 'BLOB',
			'distribution' => 'VARCHAR(200)'
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('sms_subscribe');
    }
}
