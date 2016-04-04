<?php

use yii\db\Migration;

class m160404_075203_scores2 extends Migration
{
    public function up()
    {
        $sql = "ALTER TABLE `scores` ADD `score_type` VARCHAR(25) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL AFTER `score_id`;";
        $this->execute($sql);

        $sql = "ALTER TABLE `scores` DROP FOREIGN KEY `scores_ibfk_1`;\n"
               . "";
        $this->execute($sql);

        $sql = "ALTER TABLE `scores` DROP FOREIGN KEY `scores_ibfk_3`;\n"
               . "";
        $this->execute($sql);

        $sql = <<< KONIEC
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE `score_elements`;
DROP TABLE `score_types`;
SET FOREIGN_KEY_CHECKS = 1;
KONIEC;

	    $this->execute($sql);

    }

    public function down()
    {
        echo "m160404_075203_scores2 cannot be reverted.\n";

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
