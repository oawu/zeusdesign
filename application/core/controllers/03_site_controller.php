<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Site_controller extends Oa_controller {

  public function __construct () {
    parent::__construct ();

    $this
         ->set_componemt_path ('component', 'site')
         ->set_frame_path ('frame', 'site')
         ->set_content_path ('content', 'site')
         ->set_public_path ('public')

         ->set_title (Cfg::setting ('site', 'site', 'title'))

         ->_add_meta ()
         ->_add_css ()
         ->_add_js ()
         ;
  }

  private function _add_meta () {
    return $this;
  }

  private function _add_css () {
    return $this
                ->add_css ('http://fonts.googleapis.com/css?family=Open+Sans:400italic,400,700', false)
                ->append_css (base_url ('application', 'cell', 'views', 'site_frame_cell', 'header', 'content.css'))
                ->append_css (base_url ('application', 'cell', 'views', 'site_frame_cell', 'footer', 'content.css'))
    ;
  }

  private function _add_js () {
    return $this->add_js (resource_url ('resource', 'javascript', 'jquery_v1.10.2', 'jquery-1.10.2.min.js'))
                ->add_js (resource_url ('resource', 'javascript', 'imgLiquid_v0.9.944', 'imgLiquid-min.js'))
                ->add_js (resource_url ('resource', 'javascript', 'autosize_v3.0.8', 'autosize.min.js'))
                ;
  }
}