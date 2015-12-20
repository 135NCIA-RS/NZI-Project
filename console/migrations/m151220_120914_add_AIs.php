<?php

use yii\db\Schema;
use yii\db\Migration;

class m151220_120914_add_AIs extends Migration
{
    public function up()
    {
        $sql = "ALTER TABLE  `post` CHANGE  `post_id`  `post_id` INT( 11 ) NOT NULL AUTO_INCREMENT ;";
        $this->execute($sql);
        $sql = "ALTER TABLE `comment` CHANGE `comment_id` `comment_id` INT(11) NOT NULL AUTO_INCREMENT;";
        $this->execute($sql);
        $sql = "ALTER TABLE `like` CHANGE `like_id` `like_id` INT(11) NOT NULL AUTO_INCREMENT;";
        $this->execute($sql);
        
    }

    public function down()
    {
        echo "m151220_120914_add_AIs cannot be reverted.\n";

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
