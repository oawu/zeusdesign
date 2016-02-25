<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Migration_Add_invoice_destroy_user_id_index extends CI_Migration {
  public function up () {
    $this->db->query (
      "ALTER TABLE `invoices` ADD INDEX `destroy_user_id_index`(`destroy_user_id`);"
    );
  }
  public function down () {
    $this->db->query (
      "DROP INDEX `destroy_user_id_index` ON `invoices`;"
    );
  }
}