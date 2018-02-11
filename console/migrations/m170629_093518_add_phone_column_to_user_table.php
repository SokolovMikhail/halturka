<?php

use yii\db\Migration;

/**
 * Handles adding phone to table `user`.
 */
class m170629_093518_add_phone_column_to_user_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
		$this->addColumn('user', 'phone', 'VARCHAR(200)');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
		$this->dropColumn('user', 'phone');
    }
}
