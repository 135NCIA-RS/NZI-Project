<?php

use yii\db\Schema;
use yii\db\Migration;

class m151220_123049_change_table_comment extends Migration
{
    public function up()
    {
        $sql = "ALTER TABLE `comment` ADD `comment_text` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL, ADD `comment_date` DATETIME NOT NULL;";
        $this->execute($sql);
    }

    public function down()
    {
        echo "m151220_123049_change_table_comment cannot be reverted.\n";

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
