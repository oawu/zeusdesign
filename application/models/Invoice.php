<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Invoice extends OaModel {

  static $table_name = 'invoices';

  static $has_one = array (
  );

  static $has_many = array (
    array ('pictures', 'class_name' => 'InvoicePicture'),
  );

  static $belongs_to = array (
    array ('tag', 'class_name' => 'InvoiceTag'),
    array ('user', 'class_name' => 'User'),
  );

  const NO_FINISHED = 0;
  const IS_FINISHED = 1;

  static $finishName = array(
    self::NO_FINISHED => '未請款',
    self::IS_FINISHED => '已請款',
  );

  public function __construct ($attributes = array (), $guard_attributes = true, $instantiating_via_find = false, $new_record = true) {
    parent::__construct ($attributes, $guard_attributes, $instantiating_via_find, $new_record);

    OrmImageUploader::bind ('cover', 'InvoiceCoverImageUploader');
  }
  public function mini_memo ($length = 100) {
    if (!isset ($this->memo)) return '';
    return $length ? mb_strimwidth (remove_ckedit_tag ($this->memo), 0, $length, '…','UTF-8') : remove_ckedit_tag ($this->memo);
  }
  public function destroy () {
    if (!(isset ($this->cover) && isset ($this->id)))
      return false;

    if ($this->pictures)
      foreach ($this->pictures as $picture)
        if (!$picture->destroy ())
          return false;

    return $this->cover->cleanAllFiles () && $this->delete ();
  }
}