<?php

use yii\db\Schema;
use yii\db\Migration;

class m151207_172613_recreate_userinfo_table extends Migration
{
    public function up()
    {
        $sql = "TRUNCATE `userInfo`";
        $this->execute($sql);
        $sql = "DROP TABLE `userInfo`";
        $this->execute($sql);
        $sql = "CREATE TABLE `userInfo` (".
                " `user_id` int(11) NOT NULL,".
                " `user_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',".
                " `user_surname` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',".
                " `user_birthdate` varchar(10) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',".
                " `user_education` varchar(256) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT '',".
                " `user_city` varchar(256) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT '',".
                " `user_about` varchar(1024) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT '',".
                " PRIMARY KEY (`user_id`),".
                " CONSTRAINT `userInfo_ibfk_1`".
                " FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION".
                ") ENGINE=InnoDB DEFAULT CHARSET=utf8";
        $this->execute($sql);
    }

    public function down()
    {
        echo "m151207_172613_recreate_userinfo_table cannot be reverted.\n";

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
