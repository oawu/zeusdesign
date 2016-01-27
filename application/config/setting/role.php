<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

$role['roles'] = array (
  'login' => '未驗證',
  'member' => '會員',
  'admin' => '管理者', 
  'root' => '權限最高者');

$role['members'] = array ('member', 'admin', 'root');
$role['admins'] = array ('admin', 'root');