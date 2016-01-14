<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Site_frame_cell extends Cell_Controller {

  /* render_cell ('site_frame_cell', 'header', var1, ..); */
  // public function _cache_header () {
  //   return array ('time' => 60 * 60, 'key' => null);
  // }
  public function header () {
    return $this->load_view ();
  }

  /* render_cell ('site_frame_cell', 'footer', var1, ..); */
  // public function _cache_footer () {
  //   return array ('time' => 60 * 60, 'key' => null);
  // }
  public function footer () {
    return $this->load_view ();
  }
}