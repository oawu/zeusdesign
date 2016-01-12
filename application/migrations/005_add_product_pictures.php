<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Migration_Add_product_pictures extends CI_Migration {
  public function up () {
    $this->db->query (
      "CREATE TABLE `product_pictures` (
        `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
        `product_id` int(11) unsigned NOT NULL COMMENT 'Product ID',
        `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '檔案名稱',
        `updated_at` datetime NOT NULL DEFAULT '" . date ('Y-m-d H:i:s') . "' COMMENT '更新時間',
        `created_at` datetime NOT NULL DEFAULT '" . date ('Y-m-d H:i:s') . "' COMMENT '新增時間',
        PRIMARY KEY (`id`),
        KEY `product_id_index` (`product_id`),
        FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;"
    );
  }
  public function down () {
    $this->db->query (
      "DROP TABLE `product_pictures`;"
    );
  }
}