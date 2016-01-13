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
    array ('mappings', 'class_name' => 'ProductTagMapping'),
    array ('pictures', 'class_name' => 'ProductPicture'),
    array ('tags', 'class_name' => 'ProductTag', 'through' => 'mappings'),
    array ('blocks', 'class_name' => 'ProductBlock'),
  );

  static $belongs_to = array (
  );

  const ENABLE_NO  = 0;
  const ENABLE_YES = 1;

  static $enableName = array(
    self::ENABLE_NO  => '停用',
    self::ENABLE_YES => '啟用',
  );
  public function __construct ($attributes = array (), $guard_attributes = true, $instantiating_via_find = false, $new_record = true) {
    parent::__construct ($attributes, $guard_attributes, $instantiating_via_find, $new_record);

    OrmImageUploader::bind ('cover', 'ProductCoverImageUploader');
  }

  public function mini_content ($length = 100) {
    if (!isset ($this->content)) return '';
    return $length ? mb_strimwidth (remove_ckedit_tag ($this->content), 0, $length, '…','UTF-8') : remove_ckedit_tag ($this->content);
  }
  public function destroy () {
    if (!(isset ($this->cover) && isset ($this->id)))
      return false;

    if ($this->blocks)
      foreach ($this->blocks as $block)
        if (!$block->destroy ())
          return false;

    if ($this->pictures)
      foreach ($this->pictures as $picture)
        if (!$picture->destroy ())
          return false;

    if ($this->mappings)
      foreach ($this->mappings as $mapping)
        if (!$mapping->destroy ())
          return false;

    return $this->cover->cleanAllFiles () && $this->delete ();
  }
  public function blocks () {
    return array_map (function ($block) {
      return  array (
          'title' => $block->title,
          'items' => array_map (function ($item) {
            return array (
                'title' => $item->title,
                'link' => $item->link
              );
          }, $block->items)
        );
    }, $this->blocks);
  }
}