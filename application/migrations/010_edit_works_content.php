<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Migration_Edit_works_content extends CI_Migration {
  public function up () {
    $this->db->query (
      "ALTER TABLE `works` CHANGE COLUMN `content` `content` text NOT NULL COMMENT '備註';"
    );
  }
  public function down () {
    $this->db->query (
      "ALTER TABLE `works` CHANGE COLUMN `content` `content` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '內容';"
    );
  }
}