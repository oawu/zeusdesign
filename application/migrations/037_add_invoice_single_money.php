<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Migration_Add_invoice_single_money extends CI_Migration {
  public function up () {
    $this->db->query (
      "ALTER TABLE `invoices` ADD `single_money` int(11) unsigned DEFAULT 0 COMMENT '單價' AFTER `quantity`;"
    );
  }
  public function down () {
    $this->db->query (
      "ALTER TABLE `invoices` DROP COLUMN `single_money`;"
    );
  }
}