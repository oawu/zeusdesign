<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

$menu['admin'] = array (
    '權限' => array (
        '首頁' => array ('icon' => 'icon-home', 'href' => base_url ('admin'), 'class' => 'main', 'method' => 'index', 'target' => '_self'),
        '角色設定' => array ('icon' => 'icon-user', 'href' => base_url ('admin'), 'class' => 'roles', 'method' => '', 'target' => '_self'),
        '使用者設定' => array ('icon' => 'icon-user2', 'href' => base_url ('admin'), 'class' => 'users', 'method' => '', 'target' => '_self'),
      ),
    '首頁上搞' => array (
        '分類' => array ('icon' => 'icon-tags', 'href' => base_url ('admin'), 'class' => '', 'method' => '', 'target' => '_self'),
        '作品' => array ('icon' => 'icon-images', 'href' => base_url ('admin'), 'class' => '', 'method' => '', 'target' => '_self'),
      ),
    '帳務' => array (
        '華碩帳務' => array ('icon' => 'icon-grid', 'href' => base_url ('admin'), 'class' => '', 'method' => '', 'target' => '_self'),
        '發票紀錄' => array ('icon' => 'icon-file-text2', 'href' => base_url ('admin'), 'class' => '', 'method' => '', 'target' => '_self'),
      ),
    '樣板產生器' => array (
        '分類' => array ('icon' => 'icon-tags', 'href' => base_url ('admin'), 'class' => '', 'method' => '', 'target' => '_self'),
        '列表' => array ('icon' => 'icon-list2', 'href' => base_url ('admin'), 'class' => '', 'method' => '', 'target' => '_self'),
        '製作' => array ('icon' => 'icon-plus', 'href' => base_url ('admin'), 'class' => '', 'method' => '', 'target' => '_self'),
      ),
  );
