<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Migration_Add_contacts extends CI_Migration {
  public function up () {
    $this->db->query (
      "CREATE TABLE `contacts` (
        `id` int(11) unsigned NOT NULL AUTO_INCREMENT,

        `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '稱呼',
        `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'E-Mail',
        `message` text NOT NULL COMMENT '留言',
        `ip` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0.0.0.0' COMMENT 'IP',

        `is_viewed` tinyint(1) unsigned NOT NULL DEFAULT 0 COMMENT '是否看過，1 是，0 否',
        `is_mailed` tinyint(1) unsigned NOT NULL DEFAULT 0 COMMENT '是否寄送 E-Mail，1 是，0 否',
        `is_replied` tinyint(1) unsigned NOT NULL DEFAULT 0 COMMENT '是否回覆，1 是，0 否',

        `updated_at` datetime NOT NULL DEFAULT '" . date ('Y-m-d H:i:s') . "' COMMENT '更新時間',
        `created_at` datetime NOT NULL DEFAULT '" . date ('Y-m-d H:i:s') . "' COMMENT '新增時間',
        PRIMARY KEY (`id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;"
    );
  }
  public function down () {
    $this->db->query (
      "DROP TABLE `contacts`;"
    );
  }
}