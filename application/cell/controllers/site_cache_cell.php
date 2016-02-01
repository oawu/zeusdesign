<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Site_cache_cell extends Cell_Controller {
  
  /* render_cell ('site_cache_cell', 'work', $id); */
  public function _cache_work ($id) {
    return array ('time' => 10 * 60, 'key' => $id);
  }
  public function work ($id) {
    if (!($id && ($work = Work::find_by_id ($id, array ('conditions' => array ('is_enabled = ?', Work::ENABLE_YES))))))
      return array ();

    if ($work->tags)
      usort ($work->tags, function ($a, $b) { return count ($a->mappings) < count ($b->mappings); });

    if ($work->tags && ($work_ids = column_array (WorkTagMapping::find ('all', array ('select' => 'work_id', 'order' => 'RAND()', 'limit' => 5, 'conditions' => array ('work_id != ? AND work_tag_id = ?', $work->id, $work->tags[0]->id))), 'work_id')))
       Work::addConditions ($conditions, 'id IN (?)', $work_ids);
    else
       Work::addConditions ($conditions, 'id != ?', $work->id);
    
    $others = Work::find ('all', array ('select' => 'id, title', 'limit' => 5, 'conditions' => $conditions));

    return array (
        'work' => array_merge ($work->to_array (array (
          'only' => array ('id', 'title', 'content', 'pv'),
          'methods' => array ('site_show_page_last_uri'),
          )), array (
          'mini_content' => array (
              '300' => $work->mini_content (300),
            ),
          'cover_url' => array (
              '1200x630c' => $work->cover->url ('1200x630c')
            ),
          'updated_at' => array (
              'c' => $work->updated_at->format ('c'),
              'Y-m-d H:i:s' => $work->updated_at->format ('Y-m-d H:i:s')
            ),
          'created_at' => array (
              'c' => $work->created_at->format ('c'),
              'Y-m-d H:i:s' => $work->created_at->format ('Y-m-d H:i:s')
            ),
        )),
        'tags' => array_map (function ($tag) {
          return $tag->to_array (array (
              'only' => array ('id', 'name')
            ));
        }, $work->tags),
        'others' => array_map (function ($work) {
          return $work->to_array (array (
              'only' => array (),
              'methods' => array ('site_show_page_last_uri')
            ));
        }, $others),
        'blocks' => array_map (function ($block) {
          return array (
              'title' => $block->title,
              'items' => array_map (function ($item) {
                return $item->to_array (array (
                    'only' => array ('link', 'title')
                  ));
              }, $block->items)
            );
        }, WorkBlock::find ('all', array ('include' => array ('items'), 'conditions' => array ('work_id = ?', $work->id)))),
        'pictures' => array_map (function ($picture) {
          return array (
              'name' => array (
                  '800w' => $picture->name->url ('800w')
                )
            );
        }, $work->pictures),
      );
  }

  /* render_cell ('site_cache_cell', 'article', $id); */
  // public function _cache_article ($id) {
  //   return array ('time' => 10 * 60, 'key' => $id);
  // }
  public function article ($id) {
    if (!($id && ($article = Article::find ('one', array ('include' => array ('sources'), 'conditions' => array ('id = ? AND is_visibled = ? AND destroy_user_id = ?', $id, Article::IS_VISIBLED, 0))))))
      return array ();

    if ($article->tags)
      usort ($article->tags, function ($a, $b) { return count ($a->mappings) < count ($b->mappings); });

    if ($article->tags && ($article_ids = column_array (ArticleTagMapping::find ('all', array ('select' => 'article_id', 'order' => 'RAND()', 'limit' => 5, 'conditions' => array ('article_id != ? AND article_tag_id = ?', $article->id, $article->tags[0]->id))), 'article_id')))
       Article::addConditions ($conditions, 'id IN (?)', $article_ids);
    else
       Article::addConditions ($conditions, 'id != ?', $article->id);
    
    $others = Article::find ('all', array ('select' => 'id, title', 'limit' => 5, 'conditions' => $conditions));

    return array (
        'article' => array_merge ($article->to_array (array (
          'only' => array ('id', 'title', 'content', 'pv'),
          'methods' => array ('site_show_page_last_uri'),
          )), array (
          'mini_content' => array (
              '300' => $article->mini_content (300),
            ),
          'cover_url' => array (
              '1200x630c' => $article->cover->url ('1200x630c')
            ),
          'updated_at' => array (
              'c' => $article->updated_at->format ('c'),
              'Y-m-d H:i:s' => $article->updated_at->format ('Y-m-d H:i:s')
            ),
          'created_at' => array (
              'c' => $article->created_at->format ('c'),
              'Y-m-d H:i:s' => $article->created_at->format ('Y-m-d H:i:s')
            ),
        )),
        'tags' => array_map (function ($tag) {
          return $tag->to_array (array (
              'only' => array ('id', 'name')
            ));
        }, $article->tags),
        'others' => array_map (function ($article) {
          return $article->to_array (array (
              'only' => array (),
              'methods' => array ('site_show_page_last_uri')
            ));
        }, $others),
        'sources' => array_map (function ($source) {
          return $source->to_array (array (
              'only' => array ('title', 'href'),
              'methods' => array ('mini_href')
            ));
        }, $article->sources),
        'user' => $article->user->to_array (array (
            'only' => array ('name'),
            'methods' => array ('facebook_link')
          ))
      );
  }
}