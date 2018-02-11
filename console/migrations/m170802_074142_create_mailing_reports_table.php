<?php

use yii\db\Migration;

/**
 * Handles the creation of table `mailing_reports`.
 */
class m170802_074142_create_mailing_reports_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('mailing_reports', [
            'id' => $this->primaryKey(),
			'user_id' => $this->integer(),
			'module_id' => 'VARCHAR(255)',
			'report_id' => 'VARCHAR(255)',
			'period' => $this->integer(),
			'last_sending' => 'DATETIME',
			'storage_id' => $this->integer(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('mailing_reports');
    }
}
