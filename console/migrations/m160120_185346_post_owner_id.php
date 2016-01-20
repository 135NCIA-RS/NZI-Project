<?php

use yii\db\Schema;
use yii\db\Migration;

class m160120_185346_post_owner_id extends Migration
{
    public function up()
    {
        $sql = "ALTER TABLE `post` ADD `owner_id` INT NULL AFTER `user_id`;";
        $this->execute($sql);
        $sql = "ALTER TABLE `post` ADD INDEX(`owner_id`);";
        $this->execute($sql);
        $sql = "ALTER TABLE `post` ADD FOREIGN KEY (`owner_id`) REFERENCES `projectdb`.`user`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;\n"
    . "";
        $this->execute($sql);
    }

    public function down()
    {
        echo "m160120_185346_post_owner_id cannot be reverted.\n";

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
