<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Pokemons extends Site_controller {

  public function index () {
    $pokmons = Pokemon::find ('all');
    $this->set_frame_path ('frame', 'pure')
         ->add_css (resource_url ('resource', 'css', 'fancyBox_v2.1.5', 'my.css'))
         ->add_js (resource_url ('resource', 'javascript', 'fancyBox_v2.1.5', 'my.js'))
         ->add_js (resource_url ('resource', 'javascript', 'lazyload_v1.9.7', 'jquery.lazyload.min.js'))
         ->load_view (array (
        'pokmons' => $pokmons
      ));
  }
}
