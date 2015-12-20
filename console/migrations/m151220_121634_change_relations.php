<?php

use yii\db\Schema;
use yii\db\Migration;

class m151220_121634_change_relations extends Migration
{
    public function up()
    {
        $sql = "ALTER TABLE `comment` DROP FOREIGN KEY `comment_ibfk_1`;\n"
    . "ALTER TABLE `comment` ADD CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `projectdb`.`post`(`post_id`) ON DELETE CASCADE ON UPDATE CASCADE;\n"
    . "ALTER TABLE `comment` DROP FOREIGN KEY `comment_ibfk_2`;\n"
    . "ALTER TABLE `comment` ADD CONSTRAINT `comment_ibfk_2` FOREIGN KEY (`author_id`) REFERENCES `projectdb`.`user`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;\n"
    . "";
        $this->execute($sql);
        
        $sql = "ALTER TABLE `follower` DROP FOREIGN KEY `follower_ibfk_1`;\n"
    . "ALTER TABLE `follower` ADD CONSTRAINT `follower_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `projectdb`.`user`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;\n"
    . "ALTER TABLE `follower` DROP FOREIGN KEY `follower_ibfk_2`;\n"
    . "ALTER TABLE `follower` ADD CONSTRAINT `follower_ibfk_2` FOREIGN KEY (`follower_id`) REFERENCES `projectdb`.`user`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;\n"
    . "";
        
        $this->execute($sql);
        
        $sql = "ALTER TABLE `like` DROP FOREIGN KEY `like_ibfk_1`;\n"
    . "ALTER TABLE `like` ADD CONSTRAINT `like_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `projectdb`.`user`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;\n"
    . "ALTER TABLE `like` DROP FOREIGN KEY `like_ibfk_2`;\n"
    . "ALTER TABLE `like` ADD CONSTRAINT `like_ibfk_2` FOREIGN KEY (`post_id`) REFERENCES `projectdb`.`post`(`post_id`) ON DELETE CASCADE ON UPDATE CASCADE;\n"
    . "";
        
        $this->execute($sql);
        
        $sql = "ALTER TABLE `photo` DROP FOREIGN KEY `photo_ibfk_1`;\n"
    . "ALTER TABLE `photo` ADD CONSTRAINT `photo_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `projectdb`.`user`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;\n"
    . "";
        
        $this->execute($sql);
        
        $sql = "ALTER TABLE `post` DROP FOREIGN KEY `post_ibfk_1`;\n"
    . "ALTER TABLE `post` ADD CONSTRAINT `post_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `projectdb`.`user`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;\n"
    . "";
        
        $this->execute($sql);
        
        $sql = "ALTER TABLE `relationship` DROP FOREIGN KEY `relationship_ibfk_1`;\n"
    . "ALTER TABLE `relationship` ADD CONSTRAINT `relationship_ibfk_1` FOREIGN KEY (`user1_id`) REFERENCES `projectdb`.`user`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;\n"
    . "ALTER TABLE `relationship` DROP FOREIGN KEY `relationship_ibfk_2`;\n"
    . "ALTER TABLE `relationship` ADD CONSTRAINT `relationship_ibfk_2` FOREIGN KEY (`user2_id`) REFERENCES `projectdb`.`user`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;\n"
    . "";
        
        $this->execute($sql);
        
        $sql = "ALTER TABLE `userInfo` DROP FOREIGN KEY `userInfo_ibfk_1`;\n"
    . "ALTER TABLE `userInfo` ADD CONSTRAINT `userInfo_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `projectdb`.`user`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;\n"
    . "";
        
        $this->execute($sql);
    }

    public function down()
    {
        echo "m151220_121634_change_relations cannot be reverted.\n";

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
