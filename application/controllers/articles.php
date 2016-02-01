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
    if (!($id && ($article_info = render_cell ('site_cache_cell', 'article', $id))))
      return redirect_message (array ('articles'), array (
          '_flash_message' => '找不到該筆資料。'
        ));

    $article = $article_info['article'];
    $tags    = $article_info['tags'];
    $others  = $article_info['others'];
    $user    = $article_info['user'];
    $sources = $article_info['sources'];

    $this->set_title ($article['title'] . ' - ' . Cfg::setting ('site', 'site', 'title'))
         ->add_hidden (array ('id' => 'id', 'value' => $article['id']))
         ->add_meta (array ('name' => 'keywords', 'content' => $article['title'] . ',' . implode (',', Cfg::setting ('site', 'site', 'keywords'))))
         ->add_meta (array ('name' => 'description', 'content' => $article['mini_content']['300'] . ', ' . Cfg::setting ('site', 'site', 'description')))
         ->add_meta (array ('property' => 'og:title', 'content' => $article['title'] . ' - ' . Cfg::setting ('site', 'site', 'title')))
         ->add_meta (array ('property' => 'og:description', 'content' => $article['mini_content']['300'] . ' - ' . Cfg::setting ('site', 'site', 'description')))
         ->add_meta (array ('property' => 'og:image', 'tag' => 'larger', 'content' => $img = $article['cover_url']['1200x630c'], 'alt' => $article['title'] . ' - ' . Cfg::setting ('site', 'site', 'title')))
         ->add_meta (array ('property' => 'og:image:type', 'tag' => 'larger', 'content' => 'image/' . pathinfo ($img, PATHINFO_EXTENSION)))
         ->add_meta (array ('property' => 'og:image:width', 'tag' => 'larger', 'content' => '1200'))
         ->add_meta (array ('property' => 'og:image:height', 'tag' => 'larger', 'content' => '630'))
         ->add_meta (array ('property' => 'og:type', 'content' => 'article'))
         ->add_meta (array ('property' => 'article:author', 'content' => Cfg::setting ('facebook', 'page', 'link')))
         ->add_meta (array ('property' => 'article:publisher', 'content' => Cfg::setting ('facebook', 'page', 'link')))
         ->add_meta (array ('property' => 'article:modified_time', 'content' => $article['updated_at']['c']))
         ->add_meta (array ('property' => 'article:published_time', 'content' => $article['created_at']['c']))
         ;

    if (($tags = column_array ($tags, 'name')) || ($tags = Cfg::setting ('site', 'site', 'keywords')))
      foreach ($tags as $i => $tag)
        if (!$i) $this->add_meta (array ('property' => 'article:section', 'content' => $tag))->add_meta (array ('property' => 'article:tag', 'content' => $tag));
        else $this->add_meta (array ('property' => 'article:tag', 'content' => $tag));

    if ($others)
      foreach ($others as $other)
        $this->add_meta (array ('property' => 'og:see_also', 'content' => base_url ('article', $other['site_show_page_last_uri'])));

    $this->load_view (array (
            'article' => $article,
            'tags' => $tags,
            'sources' => $sources,
            'user' => $user
          ));
  }
  public function index ($offset = 0) {
    $columns = array ();
    $configs = array ($this->get_class (), '%s');
    $conditions = conditions ($columns, $configs);
    Article::addConditions ($conditions, 'is_visibled = ? AND destroy_user_id = ?', Article::IS_VISIBLED, 0);

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
