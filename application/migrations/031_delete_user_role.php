<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Migration_Delete_user_role extends CI_Migration {
  public function up () {
    $this->db->query (
      "ALTER TABLE `users` DROP COLUMN `role`;"
    );
  }
  public function down () {
    $this->db->query (
      "ALTER TABLE `users` ADD `role` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'login' COMMENT '角色' AFTER `email`;"
    );
  }
}