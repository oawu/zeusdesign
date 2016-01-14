<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class WorkBlock extends OaModel {

  static $table_name = 'work_blocks';

  static $has_one = array (
  );

  static $has_many = array (
    array ('items', 'class_name' => 'WorkBlockItem'),
  );

  static $belongs_to = array (
  );

  public function __construct ($attributes = array (), $guard_attributes = true, $instantiating_via_find = false, $new_record = true) {
    parent::__construct ($attributes, $guard_attributes, $instantiating_via_find, $new_record);
  }
  public function destroy () {
    if ($this->items)
      foreach ($this->items as $item)
        if (!$item->destroy ())
          return false;

    return $this->delete ();
  }
}