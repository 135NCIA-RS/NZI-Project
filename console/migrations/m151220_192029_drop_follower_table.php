<?php

use yii\db\Schema;
use yii\db\Migration;

class m151220_192029_drop_follower_table extends Migration
{
    public function up()
    {
        $sql = "DROP TABLE `follower`";
        $this->execute($sql);
    }

    public function down()
    {
        echo "m151220_192029_drop_follower_table cannot be reverted.\n";

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
