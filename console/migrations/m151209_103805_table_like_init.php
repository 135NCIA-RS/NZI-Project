<?php

use yii\db\Schema;
use yii\db\Migration;

class m151209_103805_table_like_init extends Migration
{
    public function up()
    {
        $sql="CREATE TABLE `like` (".
                " `like_id` int(11) NOT NULL,".
                " `user_id` int(11) NOT NULL,".
                " `post_id` int(11) NOT NULL,".
                " PRIMARY KEY (`like_id`),".
                " KEY `user_id` (`user_id`),".
                " KEY `post_id` (`post_id`),".
                " CONSTRAINT `like_ibfk_2` FOREIGN KEY (`post_id`) REFERENCES `post` (`post_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,".
                " CONSTRAINT `like_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION".
                ") ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin";
        
        $this->execute($sql);
    }

    public function down()
    {
        echo "m151209_103805_table_like_init cannot be reverted.\n";
        $sql = "TRUNCATE `like`";
        $this->execute($sql);
        $sql = "DROP TABLE `like`";
        return true;
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
