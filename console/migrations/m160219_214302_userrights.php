<?php

use yii\db\Migration;

class m160219_214302_userrights extends Migration
{

    public function up()
    {
        $sql = <<< KONIEC
TRUNCATE TABLE `auth_assignment`;

INSERT INTO `auth_assignment` (`item_name`, `user_id`, `created_at`) VALUES
('admin', 2, NULL),
('SuperAdmin', 1, NULL),
('user', 3, NULL),
('user', 4, NULL),
('user', 5, NULL);
KONIEC;
        $this->execute($sql);
    }

    public function down()
    {
        echo "m160219_214302_userrights cannot be reverted.\n";

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
