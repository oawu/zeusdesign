<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class WorkTag extends OaModel {

  static $table_name = 'work_tags';

  static $has_one = array (
  );

  static $has_many = array (
    array ('mappings', 'class_name' => 'WorkTagMapping'),
    array ('works', 'class_name' => 'Work', 'through' => 'mappings'),
    array ('tags', 'class_name' => 'WorkTag', 'order' => 'sort ASC')
  );

  static $belongs_to = array (
  );

  public function __construct ($attributes = array (), $guard_attributes = true, $instantiating_via_find = false, $new_record = true) {
    parent::__construct ($attributes, $guard_attributes, $instantiating_via_find, $new_record);
  }
  public function destroy () {
    if ($this->mappings)
      foreach ($this->mappings as $mapping)
        if (!$mapping->destroy ())
          return false;
    
    if ($this->tags)
      foreach ($this->tags as $tag)
        if (!$tag->destroy ())
          return false;

    return $this->delete ();
  }
  public function site_show_page_last_uri () {
    return $this->id . '-' . oa_url_encode ($this->name);
  }
}