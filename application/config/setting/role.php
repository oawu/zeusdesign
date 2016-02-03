<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

$role['roles'] = array (
  'member',
  'invoice_generator',
  'bloger', 
  'editor',
  'templete_generator',
  'user_manager',
  'root'
);
$role['role_names'] = array (
    'member' => '登入後台',
    'invoice_generator' => '帳務上稿',
    'bloger' => '文章上稿',
    'editor' => '官網上稿者',
    'templete_generator' => '產生樣板',
    'user_manager' => '會員管理員',
    'root' => '權限最高者',
  );

$role['login_role'] = 'member';