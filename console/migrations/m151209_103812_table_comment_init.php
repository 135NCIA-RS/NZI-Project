<?php

use yii\db\Schema;
use yii\db\Migration;

class m151209_103812_table_comment_init extends Migration
{

    public function up()
    {
        $sql = "CREATE TABLE `comment` (
 `comment_id` int(11) NOT NULL,
 `post_id` int(11) NOT NULL,
 `author_id` int(11) NOT NULL,
 `comment_text` varchar(255) COLLATE utf8_bin NOT NULL,
 `comment_date` datetime NOT NULL,
 PRIMARY KEY (`comment_id`),
 KEY `post_id` (`post_id`,`author_id`),
 KEY `author_id` (`author_id`),
 CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `post` (`post_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
 CONSTRAINT `comment_ibfk_2` FOREIGN KEY (`author_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin";
        $this->execute($sql);
    }

    public function down()
    {
        //echo "m151209_103812_table_comment_init cannot be reverted.\n";
        $sql = "TRUNCATE `comment`";
        $this->execute($sql);
        $sql = "DROP TABLE `comment`";
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
