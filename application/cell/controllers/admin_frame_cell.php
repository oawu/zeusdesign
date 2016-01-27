<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Admin_frame_cell extends Cell_Controller {

  /* render_cell ('admin_frame_cell', 'wrapper_left', var1, ..); */
  // public function _cache_wrapper_left ($menus_list) {
  //   return array ('time' => 60 * 60, 'key' => null);
  // }
  public function wrapper_left ($menus_list) {
    return $this->load_view (array (
        'menus_list' => $menus_list
      ));
  }

  /* render_cell ('admin_frame_cell', 'navbar', var1, ..); */
  // public function _cache_nav ($subtitle = '', $back_link = '') {
  //   return array ('time' => 60 * 60, 'key' => $subtitle);
  // }
  public function navbar ($subtitle = '', $back_link = '') {
    return $this->load_view (array (
        'subtitle' => $subtitle,
        'back_link' => $back_link,
      ));
  }

  /* render_cell ('admin_frame_cell', 'footer', var1, ..); */
  // public function footer () {
  //   return array ('time' => 60 * 60, 'key' => null);
  // }
  public function footer () {
    return $this->load_view ();
  }

  /* render_cell ('admin_frame_cell', 'tabs', var1, ..); */
  // public function tabs ($tabs = array (), $index = null) {
  //   return array ('time' => 60 * 60, 'key' =>  implode ('|', array_keys ($tabs)) . ($index !== null ? '_' . $index : ''));
  // }
  public function tabs ($tabs = array (), $index = null) {
    return $this->load_view (array (
        'tabs' => $tabs,
        'index' => $index
      ));
  }

  /* render_cell ('admin_frame_cell', 'pagination', $pagination); */
  // public function _cache_pagination () {
  //   return array ('time' => 60 * 60, 'key' => null);
  // }
  public function pagination ($pagination) {
    return $this->setUseCssList (true)
                ->load_view (array ('pagination' => $pagination));
  }
}