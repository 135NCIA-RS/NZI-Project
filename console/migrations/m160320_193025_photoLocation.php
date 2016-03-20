<?php

use yii\db\Migration;

class m160320_193025_photoLocation extends Migration
{
    public function up()
    {
        $sql = <<< KONIEC
ALTER TABLE  `photo` ADD  `image_loc` VARCHAR( 25 ) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT  'ImgMediaLoc';
KONIEC;
        $this->execute($sql);
    }

    public function down()
    {
        echo "m160320_193025_photoLocation cannot be reverted.\n";

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
