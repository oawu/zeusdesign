<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Migration_Add_pokemons extends CI_Migration {
  public function up () {
    $this->db->query (
      "CREATE TABLE `pokemons` (
        `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
        `uid` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '全國編號',
        `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '名稱',
        `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'login' COMMENT '圖片網址',
        `pic` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '圖片',
        `updated_at` datetime NOT NULL DEFAULT '" . date ('Y-m-d H:i:s') . "' COMMENT '更新時間',
        `created_at` datetime NOT NULL DEFAULT '" . date ('Y-m-d H:i:s') . "' COMMENT '新增時間',
        PRIMARY KEY (`id`),
        KEY `uid_index` (`uid`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;"
    );
  }
  public function down () {
    $this->db->query (
      "DROP TABLE `pokemons`;"
    );
  }
}