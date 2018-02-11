<?php

use yii\db\Migration;

class m171123_113349_add_conservaion_and_service_attributes_in_company_module_logs extends Migration
{
    public function up()
    {
        if ($this->db->getTableSchema('storage_log', true) !== null){
            $this->addColumn('storage_log', 'conservation', $this->integer()->defaultValue(0));
            $this->addColumn('storage_log', 'service', $this->integer()->defaultValue(0));
        }

        if ($this->db->getTableSchema('storage_mtd_log', true) !== null){
            $this->addColumn('storage_mtd_log', 'conservation', $this->integer()->defaultValue(0));
            $this->addColumn('storage_mtd_log', 'service', $this->integer()->defaultValue(0));
        }
    }

    public function down()
    {
        echo "m171123_113349_add_conservaion_and_service_attributes_in_company_module_logs cannot be reverted.\n";

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
