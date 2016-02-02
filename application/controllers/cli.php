<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Cli extends Site_controller {

  public function __construct () {
    parent::__construct ();

    // if (!(($psw = $this->uri->segment(3)) && (md5 ($psw) == '23a6a54bf45b8ea5551f958e4ed82990'))) {
    //   echo '密碼錯誤！';
    //   exit ();
    // }
    if (!$this->input->is_cli_request ()) {
      echo 'Request 錯誤！';
      exit ();
    }
  }
  public function sitmap () {
    $this->load->library ('Sitemap');

    // 基礎設定
    $domain = 'http://www.zeusdesign.com.tw';
    $sit_map = new Sitemap ($domain);
    $sit_map->setPath (FCPATH . 'sitemap' . DIRECTORY_SEPARATOR);
    $sit_map->setDomain ($domain);

    // main pages
    $sit_map->addItem ('/', '0.5', 'weekly', date('c'));
    $sit_map->addItem ('/abouts/', '0.5', 'weekly', date('c'));
    $sit_map->addItem ('/contacts/', '0.5', 'weekly', date('c'));
    $sit_map->addItem ('/works/', '0.8', 'daily', date('c'));
    $sit_map->addItem ('/articles/', '0.8', 'daily', date('c'));
    
    // all articles
    foreach (Article::find ('all', array ('select' => 'id, title, updated_at', 'order' => 'id DESC', 'conditions' => array ('is_visibled = ? AND destroy_user_id IS NULL', Article::IS_VISIBLED))) as $article)
      $sit_map->addItem ('/article/' . $article->site_show_page_last_uri (), '1', 'daily', $article->updated_at->format ('c'));

    // all article tags
    foreach (ArticleTag::all (array ('select' => 'id')) as $tag)
      $sit_map->addItem ('/article-tag/' . $tag->id . '/articles/', '0.8', 'daily', date('c'));

    // all works
    foreach (Work::find ('all', array ('select' => 'id, title, updated_at', 'order' => 'id DESC', 'conditions' => array ('is_enabled = ? AND destroy_user_id IS NULL', Work::ENABLE_YES))) as $work)
      $sit_map->addItem ('/work/' . $work->site_show_page_last_uri (), '1', 'daily', $work->updated_at->format ('c'));

    // all work tags
    foreach (WorkTag::all (array ('select' => 'id')) as $tag)
      $sit_map->addItem ('/work-tag/' . $tag->id . '/works/', '0.8', 'daily', date('c'));

    $sit_map->createSitemapIndex ($domain . '/sitemap/', date('c'));
  }
}
