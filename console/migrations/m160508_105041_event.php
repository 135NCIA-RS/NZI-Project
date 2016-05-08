<?php

use yii\db\Migration;

class m160508_105041_event extends Migration
{
    public function up()
    {
        $sql = <<< KONIEC
CREATE TABLE `Event` (
 `event_id` int(11) NOT NULL AUTO_INCREMENT,
 `event_type` int(11) NOT NULL,
 `event_owner` int(11) NOT NULL,
 `event_user_connected` int(11) DEFAULT NULL,
 `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
 `event_data_connected` varchar(512) COLLATE utf8_bin DEFAULT '[]',
 PRIMARY KEY (`event_id`),
 KEY `event_owner` (`event_owner`),
 KEY `event_user_connected` (`event_user_connected`),
 CONSTRAINT `Event_ibfk_1` FOREIGN KEY (`event_owner`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
 CONSTRAINT `Event_ibfk_2` FOREIGN KEY (`event_user_connected`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COLLATE=utf8_bin
KONIEC;
        $this->execute($sql);
    }

    public function down()
    {
        echo "m160508_105041_event cannot be reverted.\n";

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
