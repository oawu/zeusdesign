<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Product extends OaModel {

  static $table_name = 'products';

  static $has_one = array (
  );

  static $has_many = array (
    array ('mappings', 'class_name' => 'ProductTagMapping', 'order' => 'sort DESC'),
    array ('tags', 'class_name' => 'ProductTag', 'through' => 'mappings'),
  );

  static $belongs_to = array (
  );

  public function __construct ($attributes = array (), $guard_attributes = true, $instantiating_via_find = false, $new_record = true) {
    parent::__construct ($attributes, $guard_attributes, $instantiating_via_find, $new_record);

    OrmImageUploader::bind ('cover', 'ProductCoverImageUploader');
  }
}