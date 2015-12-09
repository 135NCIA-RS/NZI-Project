<?php

use yii\db\Schema;
use yii\db\Migration;

class m151209_103826_table_relationship_init extends Migration
{
    public function up()
    {
        $sql = "CREATE TABLE `relationship` (".
                " `user1_id` int(11) NOT NULL,".
                " `user2_id` int(11) NOT NULL,".
                " `relation_type` varchar(20) COLLATE utf8_bin NOT NULL,".
                " PRIMARY KEY (`user1_id`,`user2_id`),".
                " KEY `relation_type` (`relation_type`),".
                " KEY `user2_id` (`user2_id`),".
                " CONSTRAINT `relationship_ibfk_2` FOREIGN KEY (`user2_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,".
                " CONSTRAINT `relationship_ibfk_1` FOREIGN KEY (`user1_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION".
                ") ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin";
        $this->execute($sql);
    }

    public function down()
    {
        $sql = "TRUNCATE `relationship`";
        $this->execute($sql);
        $sql = "DROP TABLE `relationship`";
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
