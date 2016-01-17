<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Works extends Site_controller {
         
  public function __construct () {
    parent::__construct ();
    $this->add_param ('_method', $this->get_class ());
  }

  public function show ($id) {
    if (!($id && ($work = Work::find_by_id ($id))))
      return redirect_message (array ('works'), array (
          '_flash_message' => '找不到該筆資料。'
        ));

    $this->add_js (resource_url ('resource', 'javascript', 'masonry_v3.1.2', 'masonry.pkgd.min.js'))
         ->load_view (array (
            'work' => $work
          ));
  }
  public function index ($id = 0) {
    Work::addConditions ($conditions, 'is_enabled = ?', Work::ENABLE_YES);
    if ($id && ($tag = WorkTag::find_by_id ($id)) && ($work_id = column_array (WorkTagMapping::find ('all', array ('select' => 'work_id', 'conditions' => array ('work_tag_id = ?', $tag->id))), 'work_id')))
      Work::addConditions ($conditions, 'id IN (?)', $work_id);

    $works = Work::all (array ('conditions' => $conditions));

    $this->load_view (array (
        'id' => $id,
        'works' => $works
      ));
  }
}
