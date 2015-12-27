<?php

use yii\db\Schema;
use yii\db\Migration;

class m151227_122158_shared_Accounts extends Migration
{
    public function up()
    {
        $sql = <<< SQL
SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE `user`;
TRUNCATE TABLE `comment`;
TRUNCATE TABLE `like`;
TRUNCATE TABLE `photo`;
TRUNCATE TABLE `post`;
TRUNCATE TABLE `post_attachment`;
TRUNCATE TABLE `relationship`;
TRUNCATE TABLE `userInfo`;
SET FOREIGN_KEY_CHECKS = 1;
SQL;
        $this->execute($sql);
        $a = '$2y$13$aOV4O9TiIYbiCW.g2Rw/UOIYKcpVzJhD0Ewn8MX10vyzpeE2YT4t6';
        $b = '$2y$13$73gT8iHNa5e80yDVTR48K.UhqwsObSLvfK0ksVwGuFIrQK.BI7zBy';
        $c = '$2y$13$04PIXfeZXho8ZnVkS/MwmuoP9WWYjLnwLJd5SkeF43AhCYe7E8j9i';
        $d = '$2y$13$qY26WoD15xURbeHmLEs1gugIcxCz98.wNLH8sun2JwVq1EMhEBMrq';
        $e = '$2y$13$px5QfXgQfaIgOP6p6E4ALODuH8VJyO9VZ0lccDautcZcVIb9CYEyO';
        $sql = <<< SQL
INSERT INTO `user` VALUES
(1, 'Macintoshx', 'rNJsvnsC66-eVb4YUM3gCVcNGwdtzvFF', '$a', NULL, 'mac@yii2.local', 10, 1451217894, 1451217910),
(2, 'przemek', 'TSjTtGRX4AnAltjuJYHY7H8OvzDGT2qT', '$b', NULL, 'przemek@yii2.local', 10, 1451217958, 1451217973),
(3, 'daro5g', 'Y88VaFzVlsNFEMYQHxHPnr5hOWyn0n3k', '$c', NULL, 'daro@yii2.local', 10, 1451217997, 1451218012),
(4, 'Mayumu', 'O-RZWpWJfjh-eYie2b1ULM7pbnNtqoWB', '$d', NULL, 'mayumu@yii2.local', 10, 1451218038, 1451218049),
(5, 'marta', 'fpXjuF_m7cAkSfy0qHVMZtsDeRhElIku', '$e', NULL, 'marta@yii2.local', 10, 1451218075, 1451218075);
SQL;
        $this->execute($sql);
        $sql = <<< SQL
INSERT INTO `userInfo` VALUES
(1, 'Greg', 'Lieske', '', '', '', ''),
(2, 'Przemek', 'Blokus', '', '', '', ''),
(3, 'Błażej', 'Darowski', '', '', '', ''),
(4, 'Paweł', 'Cyman', '', '', '', '');            
SQL;
        $this->execute($sql);   
        
        
        $sql = <<< SQL
INSERT INTO `photo` VALUES
(1, 'nerd.png', 'profile'),
(3, 'nerd.png', 'profile'),
(5, 'nerd.png', 'profile');
SQL;
        $this->execute($sql);
    }

    public function down()
    {
        echo "m151227_122158_shared_Accounts cannot be reverted.\n";

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
