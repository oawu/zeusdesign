<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Migration_Add_invoice_destroy_user_id extends CI_Migration {
  public function up () {
    $this->db->query (
      "ALTER TABLE `invoices` ADD `destroy_user_id` int(11) unsigned DEFAULT NULL COMMENT '刪除此筆的 User ID(作者)' AFTER `user_id`;"
    );
  }
  public function down () {
    $this->db->query (
      "ALTER TABLE `invoices` DROP COLUMN `destroy_user_id`;"
    );
  }
}