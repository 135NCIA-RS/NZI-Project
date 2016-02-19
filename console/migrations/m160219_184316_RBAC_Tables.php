<?php

use yii\db\Schema;
use yii\db\Migration;

class m160219_184316_RBAC_Tables extends Migration
{

    public function up()
    {
        $sql = <<< KONIEC

CREATE TABLE `auth_rule` (
 `name` varchar(64) NOT NULL,
 `data` text,
 `created_at` int(11) DEFAULT NULL,
 `updated_at` int(11) DEFAULT NULL,
 PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `auth_item` (
 `name` varchar(64) NOT NULL,
 `type` int(11) NOT NULL,
 `description` text,
 `rule_name` varchar(64) DEFAULT NULL,
 `data` text,
 `created_at` int(11) DEFAULT NULL,
 `updated_at` int(11) DEFAULT NULL,
 PRIMARY KEY (`name`),
 KEY `rule_name` (`rule_name`),
 KEY `type` (`type`),
 CONSTRAINT `auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `auth_item_child` (
 `parent` varchar(64) NOT NULL,
 `child` varchar(64) NOT NULL,
 PRIMARY KEY (`parent`,`child`),
 KEY `child` (`child`),
 CONSTRAINT `auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
 CONSTRAINT `auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `auth_assignment` (
 `item_name` varchar(64) NOT NULL,
 `user_id` int(64) NOT NULL,
 `created_at` int(11) DEFAULT NULL,
 PRIMARY KEY (`item_name`,`user_id`),
 KEY `user_id` (`user_id`),
 CONSTRAINT `auth_assignment_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
 CONSTRAINT `auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

REPLACE INTO `auth_item` (`name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) VALUES
('admin', 1, 'Administrative rights', NULL, NULL, NULL, NULL),
('ban-user', 2, 'right to ban non admin users', NULL, NULL, NULL, NULL),
('comment-all', 2, 'All rights connected to comments', NULL, NULL, NULL, NULL),
('comment-create', 2, NULL, NULL, NULL, NULL, NULL),
('comment-edit', 2, NULL, NULL, NULL, NULL, NULL),
('comment-edit-own', 2, NULL, NULL, NULL, NULL, NULL),
('comment-remove', 2, NULL, NULL, NULL, NULL, NULL),
('comment-remove-own', 2, NULL, NULL, NULL, NULL, NULL),
('init-shutdown', 2, 'right to disable InTouch', NULL, NULL, NULL, NULL),
('manage-admins', 1, 'Assign/Revoke user to be an admin ', NULL, NULL, NULL, NULL),
('post-all', 2, 'Access to create, edit and remove ALL posts', NULL, NULL, NULL, NULL),
('post-create', 2, NULL, NULL, NULL, NULL, NULL),
('post-create-own', 2, 'Allows user to create a post ON HIS OWN profile', NULL, NULL, NULL, NULL),
('post-edit', 2, NULL, NULL, NULL, NULL, NULL),
('post-edit-own', 2, NULL, NULL, NULL, NULL, NULL),
('post-remove', 2, NULL, NULL, NULL, NULL, NULL),
('post-remove-own', 2, NULL, NULL, NULL, NULL, NULL),
('relations-all', 2, 'Rights to manage all relations', NULL, NULL, NULL, NULL),
('relations-block', 2, 'Can block another user', NULL, NULL, NULL, NULL),
('relations-follow', 2, 'Can follow another user', NULL, NULL, NULL, NULL),
('relations-friend', 2, 'Can invite another user to be an friend', NULL, NULL, NULL, NULL),
('relations-manage', 2, 'Manage users relations (Admin mode)', NULL, NULL, NULL, NULL),
('relations-manage-own', 2, 'Manage own relations (general)', NULL, NULL, NULL, NULL),
('search-use', 2, 'Right to use search box', NULL, NULL, NULL, NULL),
('SuperAdmin', 1, 'Global Administrator', NULL, NULL, NULL, NULL),
('user', 1, 'Default user role', NULL, NULL, NULL, NULL);
REPLACE INTO `auth_item_child` (`parent`, `child`) VALUES
('SuperAdmin', 'admin'),
('admin', 'ban-user'),
('admin', 'comment-all'),
('comment-all', 'comment-create'),
('user', 'comment-create'),
('comment-all', 'comment-edit'),
('comment-edit', 'comment-edit-own'),
('user', 'comment-edit-own'),
('comment-all', 'comment-remove'),
('comment-remove', 'comment-remove-own'),
('user', 'comment-remove-own'),
('SuperAdmin', 'init-shutdown'),
('SuperAdmin', 'manage-admins'),
('admin', 'post-all'),
('post-all', 'post-create'),
('post-create', 'post-create-own'),
('user', 'post-create-own'),
('post-all', 'post-edit'),
('post-edit', 'post-edit-own'),
('user', 'post-edit-own'),
('post-all', 'post-remove'),
('post-remove', 'post-remove-own'),
('user', 'post-remove-own'),
('user', 'relations-all'),
('relations-all', 'relations-block'),
('relations-all', 'relations-follow'),
('relations-all', 'relations-friend'),
('admin', 'relations-manage'),
('relations-manage', 'relations-manage-own'),
('user', 'relations-manage-own'),
('user', 'search-use'),
('admin', 'user');
REPLACE INTO `auth_assignment` (`item_name`, `user_id`, `created_at`) VALUES
('SuperAdmin', 1, NULL);
KONIEC;
        $this->execute($sql);
    }

    public function down()
    {
        echo "m160219_184316_RBAC_Tables cannot be reverted.\n";

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
