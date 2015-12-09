<?php

use yii\db\Schema;
use yii\db\Migration;

class m151209_103836_table_follower_init extends Migration
{

    public function up()
    {
        $sql = "CREATE TABLE `follower` (" .
                " `user_id` int(11) NOT NULL," .
                " `follower_id` int(11) NOT NULL," .
                " PRIMARY KEY (`user_id`,`follower_id`)," .
                " KEY `follower_id` (`follower_id`)," .
                " CONSTRAINT `follower_ibfk_2` FOREIGN KEY (`follower_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION," .
                " CONSTRAINT `follower_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION" .
                ") ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin";
        $this->execute($sql);
    }

    public function down()
    {
        $sql = "TRUNCATE `follower`";
        $this->execute($sql);
        $sql = "DROP TABLE `follower`";
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
