<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

$role['role_names'] = array (
    'member' => '登入後台',
    'invoice_generator' => '帳務上稿',
    'bloger' => '文章上稿',
    'official_editor' => '官網上稿',
    'templete_generator' => '產生樣板',
    'user_manager' => '會員管理',
    'contact_manager' => '留言管理',
    'root' => '最高權限',
  );

$role['roles'] = array_keys ($role['role_names']);

$role['login_role'] = 'member';