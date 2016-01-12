<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Migration_Add_product_tag_mappings extends CI_Migration {
  public function up () {
    $this->db->query (
      "CREATE TABLE `product_tag_mappings` (
        `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
        `product_id` int(11) unsigned NOT NULL COMMENT 'Picture ID',
        `product_tag_id` int(11) unsigned NOT NULL COMMENT 'Picture Tag ID',
        `updated_at` datetime NOT NULL DEFAULT '" . date ('Y-m-d H:i:s') . "' COMMENT '更新時間',
        `created_at` datetime NOT NULL DEFAULT '" . date ('Y-m-d H:i:s') . "' COMMENT '新增時間',
        PRIMARY KEY (`id`),
        KEY `product_id_index` (`product_id`),
        KEY `product_tag_id_index` (`product_tag_id`),
        UNIQUE KEY `product_id_product_tag_id_unique` (`product_id`, `product_tag_id`),
        FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
        FOREIGN KEY (`product_tag_id`) REFERENCES `product_tags` (`id`) ON DELETE CASCADE
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;"
    );
  }
  public function down () {
    $this->db->query (
      "DROP TABLE `product_tag_mappings`;"
    );
  }
}