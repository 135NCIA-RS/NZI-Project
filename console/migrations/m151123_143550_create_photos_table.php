<?php

use yii\db\Schema;
use yii\db\Migration;

class m151123_143550_create_photos_table extends Migration
{
    public function up()
    {
        $sql = "CREATE TABLE `photo` (".
                " `user_id` int(11) NOT NULL,".
                " `filename` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT 'Photo''s filename',".
                " `type` varchar(20) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT 'Photo''s type',".
                " CONSTRAINT `photo_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION".
                ") ENGINE=InnoDB DEFAULT CHARSET=utf8";
        $this->execute($sql);
    }

    public function down()
    {
        echo "m151123_143550_create_photos_table cannot be reverted.\n";
        
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
