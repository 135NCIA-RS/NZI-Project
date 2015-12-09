<?php

use yii\db\Schema;
use yii\db\Migration;

class m151209_103756_table_post_init extends Migration
{
    public function up()
    {
        $sql = "CREATE TABLE `post` (".
                " `post_id` int(11) NOT NULL,".
                " `user_id` int(11) NOT NULL,".
                " `post_type` varchar(20) COLLATE utf8_bin NOT NULL,".
                " `post_text` varchar(2048) COLLATE utf8_bin NOT NULL,".
                " `post_ref` int(11) NOT NULL DEFAULT '-1',".
                " `post_visibility` varchar(20) COLLATE utf8_bin NOT NULL DEFAULT 'visible',".
                " `post_date` date NOT NULL,".
                " `post_editdate` date DEFAULT NULL,".
                " `post_additionaltext` varchar(512) COLLATE utf8_bin NOT NULL DEFAULT '',".
                " PRIMARY KEY (`post_id`), KEY `user_id` (`user_id`), KEY `post_ref` (`post_ref`),".
                " CONSTRAINT `post_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION".
                ") ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin";
        
        $this->execute($sql);
    }

    public function down()
    {
        //echo "m151209_103756_table_post_init cannot be reverted.\n";
        $sql = "TRUNCATE `post`";
        $this->execute($sql);
        $sql = "DROP TABLE `post`";
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
