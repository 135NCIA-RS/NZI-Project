<?php

use yii\db\Migration;

class m160522_182922_tokens extends Migration
{
    public function up()
    {
        $sql = <<< KONIEC
CREATE TABLE `userTokens` (
 `token_id` int(11) NOT NULL AUTO_INCREMENT,
 `user_id` int(11) NOT NULL,
 `token` varchar(256) COLLATE utf8_bin NOT NULL,
 `token_type` smallint(6) NOT NULL,
 `token_expirable` tinyint(1) NOT NULL DEFAULT '0',
 `token_expiration_date` datetime DEFAULT NULL,
 PRIMARY KEY (`token_id`),
 KEY `user_id` (`user_id`),
 CONSTRAINT `userTokens_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin
KONIEC;
        $this->execute($sql);
    }

    public function down()
    {
        echo "m160522_182922_tokens cannot be reverted.\n";

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
