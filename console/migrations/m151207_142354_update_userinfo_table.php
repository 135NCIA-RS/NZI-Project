<?php

use yii\db\Schema;
use yii\db\Migration;

class m151207_142354_update_userinfo_table extends Migration
{
    public function up()
    {
        $sql = "ALTER TABLE  `userInfo` ADD  `user_education` VARCHAR( 256 ) ,
                 ADD  `user_city` VARCHAR( 256 ),
                 ADD  `user_about` VARCHAR( 1024 )";
        $this->execute($sql);
        $sql="ALTER TABLE `userInfo` ADD PRIMARY KEY(`user_id`)";
         $this->execute($sql);
    }

    public function down()
    {
        echo "m151207_142354_update_userinfo_table cannot be reverted.\n";

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
