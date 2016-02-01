<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Migration_Add_works_pv extends CI_Migration {
  public function up () {
    $this->db->query (
      "ALTER TABLE `works` ADD `pv` int(11) unsigned NOT NULL DEFAULT 0 COMMENT 'Page view' AFTER `is_enabled`;"
    );
  }
  public function down () {
    $this->db->query (
      "ALTER TABLE `works` DROP COLUMN `pv`;"
    );
  }
}