<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Site_cache_cell extends Cell_Controller {
  
  /* render_cell ('site_cache_cell', 'work_other', $work); */
  public function _cache_work_other ($work) {
    return array ('time' => 60 * 60, 'key' => $work->id);
  }
  public function work_other ($work) {
    if ($work->tags)
      usort ($work->tags, function ($a, $b) {
        return count ($a->mappings) < count ($b->mappings);
      });

    if ($work->tags && ($work_ids = column_array (WorkTagMapping::find ('all', array ('select' => 'work_id', 'order' => 'RAND()', 'limit' => 5, 'conditions' => array ('work_id != ? AND work_tag_id = ?', $work->id, $work->tags[0]->id))), 'work_id')))
       Work::addConditions ($conditions, 'id IN (?)', $work_ids);
    else
       Work::addConditions ($conditions, 'id != ?', $work->id);

    return Work::find ('all', array ('select' => 'id, title', 'limit' => 5, 'conditions' => $conditions));
  }

  /* render_cell ('site_cache_cell', 'article_other', $article); */
  public function _cache_article_other ($article) {
    return array ('time' => 60 * 60, 'key' => $article->id);
  }
  public function article_other ($article) {
    if ($article->tags)
      usort ($article->tags, function ($a, $b) {
        return count ($a->mappings) < count ($b->mappings);
      });

    if ($article->tags && ($article_ids = column_array (ArticleTagMapping::find ('all', array ('select' => 'article_id', 'order' => 'RAND()', 'limit' => 5, 'conditions' => array ('article_id != ? AND article_tag_id = ?', $article->id, $article->tags[0]->id))), 'article_id')))
       Article::addConditions ($conditions, 'id IN (?)', $article_ids);
    else
       Article::addConditions ($conditions, 'id != ?', $article->id);

    return Article::find ('all', array ('select' => 'id, title', 'limit' => 5, 'conditions' => $conditions));
  }
}