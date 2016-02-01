<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Migration_Add_works_user_id extends CI_Migration {
  public function up () {
    $this->db->query (
      "ALTER TABLE `works` ADD `user_id` int(11) unsigned NOT NULL DEFAULT 0 COMMENT 'User ID(作者)' AFTER `id`;"
    );
    $this->db->query (
      "UPDATE `works` SET `user_id`=2;"
    );
  }
  public function down () {
    $this->db->query (
      "ALTER TABLE `works` DROP COLUMN `user_id`;"
    );
  }
}