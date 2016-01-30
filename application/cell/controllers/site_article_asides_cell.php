<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Site_article_asides_cell extends Cell_Controller {

  /* render_cell ('site_article_asides_cell', 'tags', var1, ..); */
  public function _cache_tags () {
    return array ('time' => 60 * 60, 'key' => null);
  }
  public function tags () {
    $tags = ArticleTag::all (array ('order' => 'RAND()'));
    return $this->setUseCssList (true)
                ->load_view (array (
                    'tags' => $tags
                  ));
  }

  /* render_cell ('site_article_asides_cell', 'hots', var1, ..); */
  public function _cache_hots () {
    return array ('time' => 60 * 60, 'key' => null);
  }
  public function hots () {
    $articles = Article::find ('all', array (
        'select' => 'id, title',
        'limit' => 10,
        'order' => 'pv DESC',
      ));

    return $this->setUseCssList (true)
                ->load_view (array (
                    'articles' => $articles
                  ));
  }

  /* render_cell ('site_article_asides_cell', 'news', var1, ..); */
  public function _cache_news () {
    return array ('time' => 60 * 60, 'key' => null);
  }
  public function news () {
    $articles = Article::find ('all', array (
        'select' => 'id, title',
        'limit' => 10,
        'order' => 'id DESC',
      ));

    return $this->setUseCssList (true)
                ->load_view (array (
                    'articles' => $articles
                  ));
  }
}