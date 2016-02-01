<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Migration_Add_article_destroy_user_id_index extends CI_Migration {
  public function up () {
    $this->db->query (
      "ALTER TABLE `articles` ADD INDEX `is_visibled_destroy_user_id_index`(`is_visibled`, `destroy_user_id`);"
    );
  }
  public function down () {
    $this->db->query (
      "DROP INDEX `is_visibled_destroy_user_id_index` ON `articles`;"
    );
  }
}