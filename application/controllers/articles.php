<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Articles extends Site_controller {
  public function __construct () {
    parent::__construct ();

    $this->add_js (resource_url ('resource', 'javascript', 'jquery-timeago_v1.3.1', 'jquery.timeago.js'))
         ->add_js (resource_url ('resource', 'javascript', 'jquery-timeago_v1.3.1', 'locales', 'jquery.timeago.zh-TW.js'))
         ;

  }
  
  public function show ($id = 0) {
    if (!($id && ($article = Article::find_by_id ($id, array ('conditions' => array ('is_visibled = ?', Article::IS_VISIBLED))))))
      return redirect_message (array ('articles'), array (
          '_flash_message' => '找不到該筆資料。'
        ));

    $this
         ->set_title ($article->title . ' - ' . Cfg::setting ('site', 'site', 'title'))
         ->add_meta (array ('name' => 'keywords', 'content' => $article->title . ',' . Cfg::setting ('site', 'site', 'keywords')))
         ->add_meta (array ('name' => 'description', 'content' => $article->mini_content () . ', ' . Cfg::setting ('site', 'site', 'description')))
         ->add_meta (array ('property' => 'og:title', 'content' => $article->title . ' - ' . Cfg::setting ('site', 'site', 'title')))
         ->add_meta (array ('property' => 'og:description', 'content' => $article->mini_content () . ' - ' . Cfg::setting ('site', 'site', 'description')))

         ->add_meta (array ('property' => 'og:image', 'tag' => 'larger', 'content' => $img = $article->cover->url ('1200x630c'), 'alt' => Cfg::setting ('site', 'site', 'title')))
         ->add_meta (array ('property' => 'og:image:type', 'tag' => 'larger', 'content' => 'image/' . pathinfo ($img, PATHINFO_EXTENSION)))
         ->add_meta (array ('property' => 'og:image:width', 'tag' => 'larger', 'content' => '1200'))
         ->add_meta (array ('property' => 'og:image:height', 'tag' => 'larger', 'content' => '630'))
         
         ->add_meta (array ('property' => 'og:type', 'content' => 'article'))
         ->add_meta (array ('name' => 'lastmod', 'property' => 'article:modified_time', 'itemprop' => 'dateModified', 'content' => $article->updated_at->format ('c')))
         ->add_meta (array ('name' => 'pubdate', 'property' => 'article:published_time', 'itemprop' => 'datePublished', 'content' => $article->created_at->format ('c')))
         ->add_meta (array ('name' => 'section', 'property' => 'article:section', 'itemprop' => 'articleSection', 'content' => $article->tags ? $article->tags[0]->name : '宙思設計'))
         ->add_meta (array ('name' => 'tags', 'property' => 'article:tag', 'itemprop' => 'articleTag', 'content' => $article->tags ? implode(',', column_array ($article->tags, 'name')) : '網頁設計,宙思,設計'))
         ->add_meta (array ('property' => 'article:author', 'content' => Cfg::setting ('facebook', 'page', 'link')))
         ->add_meta (array ('property' => 'article:publisher', 'content' => Cfg::setting ('facebook', 'page', 'link')))


         ->add_js (resource_url ('resource', 'javascript', 'masonry_v3.1.2', 'masonry.pkgd.min.js'))
         ->load_view (array (
            'article' => $article
          ));
  }
  public function index ($offset = 0) {
    $columns = array ();
    $configs = array ($this->get_class (), '%s');
    $conditions = conditions ($columns, $configs);
    Article::addConditions ($conditions, 'is_visibled = ?', Article::IS_VISIBLED);

    $limit = 7;
    $total = Article::count (array ('conditions' => $conditions));
    $offset = $offset < $total ? $offset : 0;

    $this->load->library ('pagination');
    $pagination = $this->pagination->initialize (array_merge (array ('total_rows' => $total, 'num_links' => 3, 'per_page' => $limit, 'uri_segment' => 0, 'base_url' => '', 'page_query_string' => false, 'first_link' => '第一頁', 'last_link' => '最後頁', 'prev_link' => '上一頁', 'next_link' => '下一頁', 'full_tag_open' => '<ul class="pagination">', 'full_tag_close' => '</ul>', 'first_tag_open' => '<li class="f">', 'first_tag_close' => '</li>', 'prev_tag_open' => '<li class="p">', 'prev_tag_close' => '</li>', 'num_tag_open' => '<li>', 'num_tag_close' => '</li>', 'cur_tag_open' => '<li class="active"><a href="#">', 'cur_tag_close' => '</a></li>', 'next_tag_open' => '<li class="n">', 'next_tag_close' => '</li>', 'last_tag_open' => '<li class="l">', 'last_tag_close' => '</li>'), $configs))->create_links ();
    $articles = Article::find ('all', array (
        'offset' => $offset,
        'limit' => $limit,
        'order' => 'id DESC',
        'include' => array ('user'),
        'conditions' => $conditions
      ));

    return $this->load_view (array (
                    'articles' => $articles,
                    'pagination' => $pagination,
                    'columns' => $columns
                  ));
  }
}
