<?php

use yii\db\Migration;

class m160503_131417_uinfo extends Migration
{
    public function up()
    {
    $sql = <<< KONIEC
ALTER TABLE `userInfo` CHANGE `user_education` `user_education` VARCHAR(256) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT '', CHANGE `user_city` `user_city` VARCHAR(256) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT '', CHANGE `user_about` `user_about` VARCHAR(1024) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT '';
KONIEC;
        $this->execute($sql);
    }

    public function down()
    {
        echo "m160503_131417_uinfo cannot be reverted.\n";

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
