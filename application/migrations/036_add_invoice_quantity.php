<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Migration_Add_invoice_quantity extends CI_Migration {
  public function up () {
    $this->db->query (
      "ALTER TABLE `invoices` ADD `quantity` int(11) unsigned DEFAULT 0 COMMENT '數量' AFTER `contact`;"
    );
  }
  public function down () {
    $this->db->query (
      "ALTER TABLE `invoices` DROP COLUMN `quantity`;"
    );
  }
}