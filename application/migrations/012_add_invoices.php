<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Migration_Add_invoices extends CI_Migration {
  public function up () {
    $this->db->query (
      "CREATE TABLE `invoices` (
        `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
        `invoice_tag_id` int(11) unsigned NOT NULL DEFAULT 0 COMMENT 'Invoice Tag ID',
        `user_id` int(11) unsigned NOT NULL DEFAULT 0 COMMENT 'User ID(負責人)',
        `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '專案名稱',
        `contact` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '窗口',
        `money` int(11) unsigned NOT NULL DEFAULT 0 COMMENT '金額',
        `memo` text NOT NULL COMMENT '備註',
        `cover` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '圖檔',
        `closing_at` date NOT NULL DEFAULT '" . date ('Y-m-d') . "' COMMENT '結案日期',
        `updated_at` datetime NOT NULL DEFAULT '" . date ('Y-m-d H:i:s') . "' COMMENT '更新時間',
        `created_at` datetime NOT NULL DEFAULT '" . date ('Y-m-d H:i:s') . "' COMMENT '新增時間',
        PRIMARY KEY (`id`),
        KEY `user_id_index` (`user_id`),
        KEY `closing_at_index` (`closing_at`),
        KEY `invoice_tag_id_index` (`invoice_tag_id`),
        FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;"
    );
  }
  public function down () {
    $this->db->query (
      "DROP TABLE `invoices`;"
    );
  }
}