<?php

use yii\db\Schema;
use yii\db\Migration;

class m151214_142608_post_date_to_datetime extends Migration
{
    public function up()
    {
        $sql = "ALTER TABLE `post` CHANGE `post_editdate` `post_editdate` DATETIME NULL DEFAULT NULL;";
        $this->execute($sql);
    }

    public function down()
    {
        echo "m151214_142608_post_date_to_datetime cannot be reverted.\n";

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
