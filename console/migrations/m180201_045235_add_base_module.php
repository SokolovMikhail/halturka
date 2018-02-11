<?php

use yii\db\Migration;

class m180201_045235_add_base_module extends Migration
{
    public function up()
    {
        $moduleExist = (new \yii\db\Query())
            ->select('id')
            ->from('module')
            ->where(['id' => 'base'])
            ->all();
        
        if(!$moduleExist){
            $this->insert('module', [
                'id' => 'base',
                'source' => 'frontend\modules\base\Base',
                'depth' => 1,
                'active' => 1,
                'permit' => NULL
            ]);
        }
    }

    public function down()
    {
        echo "m180201_045235_add_base_module cannot be reverted.\n";

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
