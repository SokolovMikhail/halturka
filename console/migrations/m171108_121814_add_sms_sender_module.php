<?php

use yii\db\Migration;

class m171108_121814_add_sms_sender_module extends Migration
{
    public function up()
    {
        $moduleExist = (new \yii\db\Query())
            ->select('id')
            ->from('module')
            ->where(['id' => 'smssender'])
            ->all();
        
        if(!$moduleExist){
            $this->insert('module', [
                'id' => 'smssender',
                'source' => 'frontend\modules\smssender\SmsSender',
                'depth' => 1,
                'active' => 0,
                'permit' => NULL,
            ]);
        }
    }

    public function down()
    {
        echo "m171108_121814_add_sms_sender_module cannot be reverted.\n";

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
