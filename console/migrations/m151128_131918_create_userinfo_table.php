<?php

use yii\db\Schema;
use yii\db\Migration;

class m151128_131918_create_userinfo_table extends Migration
{
    public function up()
    {
        $sql = "CREATE TABLE `userInfo` (".
                "`user_id` int(11) NOT NULL,".
                "`user_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,".
                "`user_surname` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,".
                "`user_birthdate` varchar(10) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL".
                ") ENGINE=InnoDB DEFAULT CHARSET=utf8";
        $this->execute($sql);
    }

    public function down()
    {
        echo "m151128_131918_create_userinfo_table cannot be reverted.\n";

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
