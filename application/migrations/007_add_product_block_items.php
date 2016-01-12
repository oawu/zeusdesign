<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Migration_Add_product_block_items extends CI_Migration {
  public function up () {
    $this->db->query (
      "CREATE TABLE `product_block_items` (
        `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
        `product_block_id` int(11) unsigned NOT NULL COMMENT 'Product Block ID',
        `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '標題',
        `link` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '鏈結',
        `updated_at` datetime NOT NULL DEFAULT '" . date ('Y-m-d H:i:s') . "' COMMENT '更新時間',
        `created_at` datetime NOT NULL DEFAULT '" . date ('Y-m-d H:i:s') . "' COMMENT '新增時間',
        PRIMARY KEY (`id`),
        KEY `product_block_id_index` (`product_block_id`),
        FOREIGN KEY (`product_block_id`) REFERENCES `product_blocks` (`id`) ON DELETE CASCADE
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;"
    );
  }
  public function down () {
    $this->db->query (
      "DROP TABLE `product_block_items`;"
    );
  }
}