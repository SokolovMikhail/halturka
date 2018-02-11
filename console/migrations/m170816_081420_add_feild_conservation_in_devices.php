<?php

use yii\db\Migration;

class m170816_081420_add_feild_conservation_in_devices extends Migration
{
    public function up()
    {
		$this->addColumn('devices', 'conservation', $this->smallInteger(1)->defaultValue(0));
    }

    public function down()
    {
        echo "m170816_081420_add_feild_conservation_in_devices cannot be reverted.\n";

        return false;
    }
}
