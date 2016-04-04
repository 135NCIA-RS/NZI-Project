<?php

use yii\db\Migration;

class m160404_083512_scores_fix extends Migration
{
    public function up()
    {
         $sql = <<< KONIEC
ALTER TABLE `scores` CHANGE `score_type` `score_type` INT(11) NOT NULL;
KONIEC;
         $this->execute($sql);
    }

    public function down()
    {
        echo "m160404_083512_scores_fix cannot be reverted.\n";

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
