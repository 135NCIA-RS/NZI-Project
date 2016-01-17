<?php

use yii\db\Schema;
use yii\db\Migration;

class m160117_152452_create_request_table extends Migration
{

    public function up()
    {
        $sql = "CREATE TABLE `request` (" .
                "`req_id` int(255) NOT NULL AUTO_INCREMENT," .
                "`user1_id` int(255) NOT NULL," .
                "`user2_id` int(255) NOT NULL," .
                "`req_type` varchar(255) NOT NULL," .
                "`date` datetime NOT NULL," .
                "PRIMARY KEY (`req_id`)," .
                "KEY `user1_id` (`user1_id`)," .
                "KEY `user2_id` (`user2_id`)," .
                "CONSTRAINT `request_ibfk_2` FOREIGN KEY (`user2_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE," .
                "CONSTRAINT `request_ibfk_1` FOREIGN KEY (`user1_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE" .
                ") ENGINE=InnoDB DEFAULT CHARSET=utf8";

        $this->execute($sql);
    }

    public function down()
    {
        $sql = "TRUNCATE `request`";
        $this->execute($sql);
        $sql = "DROP TABLE `request`";
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
