<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Migration_Add_articles extends CI_Migration {
  public function up () {
    $this->db->query (
      "CREATE TABLE `articles` (
        `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
        `user_id` int(11) unsigned NOT NULL DEFAULT 0 COMMENT 'User ID(作者)',
        `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '標題',
        `content` text NOT NULL COMMENT '內容',
        `cover` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '封面',
        `is_visibled` tinyint(1) unsigned NOT NULL DEFAULT 0 COMMENT '是否公開，1 是，0 否',
        `pv` int(11) unsigned NOT NULL DEFAULT 0 COMMENT 'Page view',
        `updated_at` datetime NOT NULL DEFAULT '" . date ('Y-m-d H:i:s') . "' COMMENT '更新時間',
        `created_at` datetime NOT NULL DEFAULT '" . date ('Y-m-d H:i:s') . "' COMMENT '新增時間',
        PRIMARY KEY (`id`),
        KEY `user_id_index` (`user_id`),
        KEY `is_visibled_index` (`is_visibled`),
        FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;"
    );
  }
  public function down () {
    $this->db->query (
      "DROP TABLE `articles`;"
    );
  }
}