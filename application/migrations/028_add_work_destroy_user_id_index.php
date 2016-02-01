<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Migration_Add_work_destroy_user_id_index extends CI_Migration {
  public function up () {
    $this->db->query (
      "ALTER TABLE `works` ADD INDEX `is_enabled_destroy_user_id_index`(`is_enabled`, `destroy_user_id`);"
    );
  }
  public function down () {
    $this->db->query (
      "DROP INDEX `is_enabled_destroy_user_id_index` ON `works`;"
    );
  }
}