<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Migration_Add_work_destroy_user_id extends CI_Migration {
  public function up () {
    $this->db->query (
      "ALTER TABLE `works` ADD `destroy_user_id` int(11) unsigned DEFAULT NULL COMMENT '刪除此筆的 User ID(作者)' AFTER `pv`;"
    );
  }
  public function down () {
    $this->db->query (
      "ALTER TABLE `works` DROP COLUMN `destroy_user_id`;"
    );
  }
}