<?php

use yii\db\Migration;

class m171011_073730_add_mailing_reports_module extends Migration
{
    public function up()
    {
		$moduleExist = (new \yii\db\Query())
			->select('id')
			->from('module')
			->where(['id' => 'mailingreports'])
			->all();
		
		if(!$moduleExist){
			$this->insert('module', [
				'id' => 'mailingreports',
				'source' => 'frontend\modules\mailingreports\MailingReports',
				'depth' => 1,
				'active' => 1,
				'permit' => NULL,
			]);
		}
    }

    public function down()
    {
        echo "m171011_073730_add_mailing_reports_module cannot be reverted.\n";

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
