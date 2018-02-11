<?php

use yii\db\Migration;

class m171124_082034_add_column_archive_in_conservation_periods extends Migration
{
    public function up()
    {
        $this->addColumn('conservation_periods', 'archive', $this->smallInteger(1)->defaultValue(0));
    }

    public function down()
    {
        echo "m171124_082034_add_column_archive_in_conservation_periods cannot be reverted.\n";

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
