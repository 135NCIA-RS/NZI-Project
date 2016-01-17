<?php

use yii\db\Schema;
use yii\db\Migration;

class m160117_152452_create_request_table extends Migration
{

    public function up()
    {
        $sql = "CREATE TABLE `request` (" .
                "`req_id` int(255) NOT NULL," .
                "`user1_id` int(255) NOT NULL," .
                "`user2_id` int(255) NOT NULL," .
                "`req_type` varchar(255) NOT NULL," .
                "`date` datetime NOT NULL," .
                "PRIMARY KEY (`req_id`)" .
                ") ENGINE=InnoDB DEFAULT CHARSET=utf8" .
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
