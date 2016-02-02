<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Site_work_asides_cell extends Cell_Controller {

  /* render_cell ('site_work_asides_cell', 'tags', var1, ..); */
  public function _cache_tags () {
    return array ('time' => 60 * 60, 'key' => null);
  }
  public function tags () {
    $tags = WorkTag::all (array (
        'select' => 'id, name',
        'order' => 'sort ASC',
        'include' => array ('tags'),
        'conditions' => array ('work_tag_id = ?', 0)
      ));
    return $this->setUseCssList (true)
                ->load_view (array (
                    'tags' => $tags
                  ));
  }
}