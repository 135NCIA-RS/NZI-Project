<?php

use yii\db\Migration;

class m160508_112621_nullUser extends Migration
{
    public function up()
    {
        $sql = "INSERT INTO `user` (`id`, `username`, `auth_key`, `password_hash`, `password_reset_token`, `email`, `status`, `created_at`, `updated_at`) VALUES (0, 'Null Intouch User', 'null', 'null', 'null', 'null', 0, 0, 0);";
        $this->execute($sql);
	    $t = \common\models\User::findOne(['username' => 'Null Intouch User']);
	    $t->id = 0;
	    return $t->save();
    }

    public function down()
    {
        echo "m160508_112621_nullUser cannot be reverted.\n";

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
