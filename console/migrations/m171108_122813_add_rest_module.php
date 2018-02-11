<?php

use yii\db\Migration;

class m171108_122813_add_rest_module extends Migration
{
    public function up()
    {
        $moduleExist = (new \yii\db\Query())
            ->select('id')
            ->from('module')
            ->where(['id' => 'rest'])
            ->all();
        
        if(!$moduleExist){
            $this->insert('module', [
                'id' => 'rest',
                'source' => 'frontend\modules\rest\Rest',
                'depth' => 1,
                'active' => 1,
                'permit' => NULL,
            ]);
        }
    }

    public function down()
    {
        echo "m171108_122813_add_rest_module cannot be reverted.\n";

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
