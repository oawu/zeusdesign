<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class ProductPicture extends OaModel {

  static $table_name = 'product_pictures';

  static $has_one = array (
  );

  static $has_many = array (
  );

  static $belongs_to = array (
  );

  public function __construct ($attributes = array (), $guard_attributes = true, $instantiating_via_find = false, $new_record = true) {
    parent::__construct ($attributes, $guard_attributes, $instantiating_via_find, $new_record);

    OrmImageUploader::bind ('name', 'ProductPictureNameImageUploader');
  }
  public function destroy () {
    if (!(isset ($this->name) && isset ($this->id)))
      return false;

    return $this->name->cleanAllFiles () && $this->delete ();
  }
}