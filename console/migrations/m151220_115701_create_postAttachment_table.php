<?php

use yii\db\Schema;
use yii\db\Migration;

class m151220_115701_create_postAttachment_table extends Migration
{
    public function up()
    {
        $sql = "CREATE TABLE `post_attachment` (
 `attachment_id` int(11) NOT NULL AUTO_INCREMENT,
 `post_id` int(11) NOT NULL,
 `file` varchar(255) COLLATE utf8_bin NOT NULL,
 PRIMARY KEY (`attachment_id`),
 KEY `post_id` (`post_id`),
 CONSTRAINT `post_attachment_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `post` (`post_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_bin";
        
        $this->execute($sql);
    }

    public function down()
    {
        echo "m151220_115701_create_postAttachment_table cannot be reverted.\n";

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
