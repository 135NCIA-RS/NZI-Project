<?php

use yii\db\Schema;
use yii\db\Migration;

class m151214_142804_post_date_to_datetime2 extends Migration
{
    public function up()
    {
        $sql = "ALTER TABLE `post` CHANGE `post_date` `post_date` DATETIME NOT NULL;";
        $this->execute($sql);
    }

    public function down()
    {
        echo "m151214_142804_post_date_to_datetime2 cannot be reverted.\n";

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
