<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Migration_Add_works_user_id_index extends CI_Migration {
  public function up () {
    $this->db->query (
      "ALTER TABLE `works` ADD INDEX `user_id_index`(`user_id`);"
    );
  }
  public function down () {
    $this->db->query (
      "DROP INDEX `user_id_index` ON `works`;"
    );
  }
}