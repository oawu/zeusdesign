<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Tag_articles extends Site_controller {
private $tag = null;
  private $article = null;

  public function __construct () {
    parent::__construct ();

    if (!(($id = $this->uri->rsegments (3, 0)) && (is_numeric ($id) ? ($this->tag = ArticleTag::find_by_id ($id)) : ($this->tag = ArticleTag::find_by_name (trim (urldecode ($id)))))))
      return redirect_message (array ('articles'), array (
          '_flash_message' => '找不到該筆資料。'
        ));

    if (in_array ($this->uri->rsegments (2, 0), array ('edit', 'update', 'destroy', 'sort')))
      if (!(($id = $this->uri->rsegments (4, 0)) && ($this->article = Article::find_by_id ($id))))
        return redirect_message (array ('articles', $this->tag->id, 'articles'), array (
            '_flash_message' => '找不到該筆資料。'
          ));


    $this->add_js (resource_url ('resource', 'javascript', 'jquery-timeago_v1.3.1', 'jquery.timeago.js'))
         ->add_js (resource_url ('resource', 'javascript', 'jquery-timeago_v1.3.1', 'locales', 'jquery.timeago.zh-TW.js'))
         ;
  }

  public function index ($tag_id, $offset = 0) {
    $columns = array ();
    $configs = array ('article-tag', $this->tag->id, 'articles', '%s');
    $conditions = conditions ($columns, $configs);
    ArticleTagMapping::addConditions ($conditions, 'article_tag_id = ?', $this->tag->id);

    $limit = 7;
    $total = ArticleTagMapping::count (array ('conditions' => $conditions));
    $offset = $offset < $total ? $offset : 0;

    $this->load->library ('pagination');
    $pagination = $this->pagination->initialize (array_merge (array ('total_rows' => $total, 'num_links' => 3, 'per_page' => $limit, 'uri_segment' => 0, 'base_url' => '', 'page_query_string' => false, 'first_link' => '第一頁', 'last_link' => '最後頁', 'prev_link' => '上一頁', 'next_link' => '下一頁', 'full_tag_open' => '<ul class="pagination">', 'full_tag_close' => '</ul>', 'first_tag_open' => '<li class="f">', 'first_tag_close' => '</li>', 'prev_tag_open' => '<li class="p">', 'prev_tag_close' => '</li>', 'num_tag_open' => '<li>', 'num_tag_close' => '</li>', 'cur_tag_open' => '<li class="active"><a href="#">', 'cur_tag_close' => '</a></li>', 'next_tag_open' => '<li class="n">', 'next_tag_close' => '</li>', 'last_tag_open' => '<li class="l">', 'last_tag_close' => '</li>'), $configs))->create_links ();
    $article_ids = column_array (ArticleTagMapping::find ('all', array (
            'select' => 'article_id',
            'offset' => $offset,
            'limit' => $limit,
            'order' => 'article_id DESC',
            'conditions' => $conditions
          )), 'article_id');

    $articles = $article_ids ? Article::find ('all', array (
        'order' => 'FIELD(id,' . implode (',', $article_ids) . ')',
        'conditions' => array ('is_visibled = ? AND id IN (?)', Article::IS_VISIBLED, $article_ids)
      )) : array ();
    
    if ($articles) {
      $this->add_meta (array ('name' => 'keywords', 'content' => implode (',', column_array ($articles, 'title')) . ',' . implode (',', Cfg::setting ('site', 'site', 'keywords'))))
           ->add_meta (array ('name' => 'description', 'content' => $articles[0]->mini_content (150)))
           ->add_meta (array ('property' => 'og:title', 'content' => $this->tag->name . '文章' . ' - ' . Cfg::setting ('site', 'site', 'title')))
           ->add_meta (array ('property' => 'og:description', 'content' => $articles[0]->mini_content (300)))
           ->add_meta (array ('property' => 'og:image', 'tag' => 'larger', 'content' => $img = $articles[0]->cover->url ('1200x630c'), 'alt' => $this->tag->name . '文章' . ' - ' . Cfg::setting ('site', 'site', 'title')))
           ->add_meta (array ('property' => 'og:image:type', 'tag' => 'larger', 'content' => 'image/' . pathinfo ($img, PATHINFO_EXTENSION)))
           ->add_meta (array ('property' => 'og:image:width', 'tag' => 'larger', 'content' => '1200'))
           ->add_meta (array ('property' => 'og:image:height', 'tag' => 'larger', 'content' => '630'))
           ->add_meta (array ('property' => 'article:modified_time', 'content' => $articles[0]->updated_at->format ('c')))
           ->add_meta (array ('property' => 'article:published_time', 'content' => $articles[0]->created_at->format ('c')))
         ;

      if (($tags = column_array ($articles[0]->tags, 'name')) || ($tags = Cfg::setting ('site', 'site', 'keywords')))
        foreach ($tags as $i => $tag)
          if (!$i) $this->add_meta (array ('property' => 'article:section', 'content' => $tag))->add_meta (array ('property' => 'article:tag', 'content' => $tag));
          else $this->add_meta (array ('property' => 'article:tag', 'content' => $tag));
    }

    return $this->set_title ($this->tag->name . '文章' . ' - ' . Cfg::setting ('site', 'site', 'title'))
                ->set_class ('articles')
                ->set_method ('index')
                ->load_view (array (
                    'tag' => $this->tag,
                    'articles' => $articles,
                    'pagination' => $pagination,
                    'columns' => $columns
                  ));
  }
}
