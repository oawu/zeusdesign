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
         ->add_hidden (array ('id' => 'ajax_pv_url', 'value' => base_url ('ajax', 'pv')))
         ;
  }

  private function _add_meta () {
    return $this->add_meta (array ('name' => 'robots', 'content' => 'index,follow'))
                ->add_meta (array ('name' => 'author', 'content' => 'Sylvain Lafitte, Web Designer, sylvainlafitte.com'))
                ->add_meta (array ('name' => 'keywords', 'content' => implode (',', Cfg::setting ('site', 'site', 'keywords'))))
                ->add_meta (array ('name' => 'description', 'content' => Cfg::setting ('site', 'site', 'description')))

                ->add_meta (array ('property' => 'og:site_name', 'content' => Cfg::setting ('site', 'site', 'title')))
                ->add_meta (array ('property' => 'og:url', 'content' => current_url ()))
                
                ->add_meta (array ('property' => 'og:title', 'content' => Cfg::setting ('site', 'site', 'title')))
                ->add_meta (array ('property' => 'og:description', 'content' => Cfg::setting ('site', 'site', 'description')))
                
                ->add_meta (array ('property' => 'fb:admins', 'content' => Cfg::setting ('facebook', 'admins')))
                ->add_meta (array ('property' => 'fb:app_id', 'content' => Cfg::setting ('facebook', 'appId')))

                ->add_meta (array ('property' => 'og:locale', 'content' => 'zh_TW'))
                ->add_meta (array ('property' => 'og:locale:alternate', 'content' => 'en_US'))
                ->add_meta (array ('property' => 'og:type', 'content' => 'article'))
                ->add_meta (array ('property' => 'article:author', 'content' => Cfg::setting ('facebook', 'page', 'link')))
                ->add_meta (array ('property' => 'article:publisher', 'content' => Cfg::setting ('facebook', 'page', 'link')))
         

                ->add_meta (array ('property' => 'og:image', 'tag' => 'larger', 'content' => $img = resource_url ('resource', 'image', 'logo', 'banner-compressor.jpg'), 'alt' => Cfg::setting ('site', 'site', 'title')))
                ->add_meta (array ('property' => 'og:image:type', 'tag' => 'larger', 'content' => 'image/' . pathinfo ($img, PATHINFO_EXTENSION)))
                ->add_meta (array ('property' => 'og:image:width', 'tag' => 'larger', 'content' => '1200'))
                ->add_meta (array ('property' => 'og:image:height', 'tag' => 'larger', 'content' => '630'))
          

                ;
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