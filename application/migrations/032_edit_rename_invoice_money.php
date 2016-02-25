<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Migration_Edit_rename_invoice_money extends CI_Migration {
  public function up () {
    $this->db->query (
      "ALTER TABLE `invoices` CHANGE `money` `all_money` int(11) unsigned NOT NULL DEFAULT 0 COMMENT '總金額'"
    );
  }
  public function down () {
    $this->db->query (
      "ALTER TABLE `invoices` CHANGE `all_money` `money` int(11) unsigned NOT NULL DEFAULT 0 COMMENT '總金額'"
    );
  }
}