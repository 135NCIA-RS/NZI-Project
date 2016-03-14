<?php

use yii\db\Migration;

class m160314_080544_scores_db extends Migration
{
    public function up()
    {
$sql = <<< KONIEC
SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE IF NOT EXISTS `scores` (
  `score_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `element_id` int(11) NOT NULL,
  `element_type` int(11) NOT NULL,
  PRIMARY KEY (`score_id`),
  KEY `user_id` (`user_id`),
  KEY `element_type` (`element_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `score_elements` (
  `elem_id` int(11) NOT NULL AUTO_INCREMENT,
  `elem_name` varchar(50) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`elem_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=3 ;

REPLACE INTO `score_elements` (`elem_id`, `elem_name`) VALUES
(1, 'post'),
(2, 'post-comment');

CREATE TABLE IF NOT EXISTS `score_types` (
  `score_id` int(13) NOT NULL AUTO_INCREMENT,
  `score_name` varchar(50) COLLATE utf8_bin NOT NULL,
  `score_enable` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`score_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=3 ;

REPLACE INTO `score_types` (`score_id`, `score_name`, `score_enable`) VALUES
(1, 'like', 1),
(2, 'unlike', 0);


ALTER TABLE `scores`
  ADD CONSTRAINT `scores_ibfk_3` FOREIGN KEY (`element_type`) REFERENCES `score_elements` (`elem_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `scores_ibfk_1` FOREIGN KEY (`score_id`) REFERENCES `score_types` (`score_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `scores_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
SET FOREIGN_KEY_CHECKS=1;
KONIEC;

        $this->execute($sql);
    }

    public function down()
    {
        echo "m160314_080544_scores_db cannot be reverted.\n";

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
