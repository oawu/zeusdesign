<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Contact extends OaModel {

  static $table_name = 'contacts';

  static $has_one = array (
  );

  static $has_many = array (
  );

  static $belongs_to = array (
  );

  const NO_VISIBLED = 0;
  const IS_VISIBLED = 1;

  static $visibledName = array(
    self::NO_VISIBLED => '隱藏',
    self::IS_VISIBLED => '顯示',
  );

  const NO_MAILED = 0;
  const IS_MAILED = 1;

  static $mailName = array(
    self::NO_MAILED => '未通知管理員',
    self::IS_MAILED => '已通知管理員',
  );

  const NO_REPLIED = 0;
  const IS_REPLIED = 1;

  static $replyName = array(
    self::NO_REPLIED => '未回覆',
    self::IS_REPLIED => '已回覆',
  );

  public function __construct ($attributes = array (), $guard_attributes = true, $instantiating_via_find = false, $new_record = true) {
    parent::__construct ($attributes, $guard_attributes, $instantiating_via_find, $new_record);
  }
}