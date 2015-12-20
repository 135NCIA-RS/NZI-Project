<?php

use yii\db\Schema;
use yii\db\Migration;

class m151220_172838_edit_relationship_primary_key extends Migration
{
    public function up()
    {
        $sql = "ALTER TABLE `relationship` DROP PRIMARY KEY, ADD PRIMARY KEY( `user1_id`, `user2_id`, `relation_type`);";
        $this->execute($sql);
    }

    public function down()
    {
        echo "m151220_172838_edit_relationship_primary_key cannot be reverted.\n";

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
