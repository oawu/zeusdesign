<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Works extends Site_controller {
         
  public function __construct () {
    parent::__construct ();
    $this->add_param ('_method', $this->get_class ());
  }

  public function show ($id) {
    if (!($id && ($work_info = render_cell ('site_cache_cell', 'work', $id))))
      return redirect_message (array ('works'), array (
          '_flash_message' => '找不到該筆資料。'
        ));

    $work = $work_info['work'];
    $tags    = $work_info['tags'];
    $others  = $work_info['others'];
    $blocks  = $work_info['blocks'];
    $pictures  = $work_info['pictures'];

    $this->set_title ($work['title'] . ' - ' . Cfg::setting ('site', 'site', 'title'))
         ->add_hidden (array ('id' => 'id', 'value' => $work['id']))
         ->add_js (resource_url ('resource', 'javascript', 'masonry_v3.1.2', 'masonry.pkgd.min.js'))
         ->add_meta (array ('name' => 'keywords', 'content' => $work['title'] . ',' . implode (',', Cfg::setting ('site', 'site', 'keywords'))))
         ->add_meta (array ('name' => 'description', 'content' => $work['mini_content']['150']))
         ->add_meta (array ('property' => 'og:title', 'content' => $work['title'] . ' - ' . Cfg::setting ('site', 'site', 'title')))
         ->add_meta (array ('property' => 'og:description', 'content' => $work['mini_content']['300']))

         ->add_meta (array ('property' => 'og:image', 'tag' => 'larger', 'content' => $img = $work['cover_url']['1200x630c'], 'alt' => $work['title'] . ' - ' . Cfg::setting ('site', 'site', 'title')))
         ->add_meta (array ('property' => 'og:image:type', 'tag' => 'larger', 'content' => 'image/' . pathinfo ($img, PATHINFO_EXTENSION)))
         ->add_meta (array ('property' => 'og:image:width', 'tag' => 'larger', 'content' => '1200'))
         ->add_meta (array ('property' => 'og:image:height', 'tag' => 'larger', 'content' => '630'))
         ->add_meta (array ('name' => 'lastmod', 'property' => 'article:modified_time', 'content' => $work['updated_at']['c']))
         ->add_meta (array ('name' => 'pubdate', 'property' => 'article:published_time', 'content' => $work['created_at']['c']))
         ->add_param ('tags', $tags)
         ;


    if (($tags = column_array ($tags, 'name')) || ($tags = Cfg::setting ('site', 'site', 'keywords')))
      foreach ($tags as $i => $tag)
        if (!$i) $this->add_meta (array ('property' => 'article:section', 'content' => $tag))->add_meta (array ('property' => 'article:tag', 'content' => $tag));
        else $this->add_meta (array ('property' => 'article:tag', 'content' => $tag));

    if ($others)
      foreach ($others as $other)
        $this->add_meta (array ('property' => 'og:see_also', 'content' => base_url ('work', $other['site_show_page_last_uri'])));

    $this->load_view (array (
            'work' => $work,
            'blocks' => $blocks,
            'pictures' => $pictures,
          ));
  }
  public function index ($offset = 0) {
    $columns = array ();
    $configs = array ($this->get_class (), '%s');
    $conditions = conditions ($columns, $configs);
    Work::addConditions ($conditions, 'is_enabled = ? AND destroy_user_id IS NULL', Work::ENABLE_YES);

    $limit = 12;
    $total = Work::count (array ('conditions' => $conditions));
    $offset = $offset < $total ? $offset : 0;

    $this->load->library ('pagination');
    $pagination = $this->pagination->initialize (array_merge (array ('total_rows' => $total, 'num_links' => 3, 'per_page' => $limit, 'uri_segment' => 0, 'base_url' => '', 'page_query_string' => false, 'first_link' => '第一頁', 'last_link' => '最後頁', 'prev_link' => '上一頁', 'next_link' => '下一頁', 'full_tag_open' => '<ul class="pagination">', 'full_tag_close' => '</ul>', 'first_tag_open' => '<li class="f">', 'first_tag_close' => '</li>', 'prev_tag_open' => '<li class="p">', 'prev_tag_close' => '</li>', 'num_tag_open' => '<li>', 'num_tag_close' => '</li>', 'cur_tag_open' => '<li class="active"><a href="#">', 'cur_tag_close' => '</a></li>', 'next_tag_open' => '<li class="n">', 'next_tag_close' => '</li>', 'last_tag_open' => '<li class="l">', 'last_tag_close' => '</li>'), $configs))->create_links ();
    $works = Work::find ('all', array (
        'offset' => $offset,
        'limit' => $limit,
        'order' => 'id DESC',
        'conditions' => $conditions
      ));

    if ($works) {
      $this->add_meta (array ('name' => 'keywords', 'content' => implode (',', column_array ($works, 'title')) . ',' . implode (',', Cfg::setting ('site', 'site', 'keywords'))))
           ->add_meta (array ('name' => 'description', 'content' => $works[0]->mini_content (150)))
           ->add_meta (array ('property' => 'og:title', 'content' => '設計作品' . ' - ' . Cfg::setting ('site', 'site', 'title')))
           ->add_meta (array ('property' => 'og:description', 'content' => $works[0]->mini_content (300)))
           ->add_meta (array ('property' => 'og:image', 'tag' => 'larger', 'content' => $img = $works[0]->cover->url ('1200x630c'), 'alt' => '設計作品' . ' - ' . Cfg::setting ('site', 'site', 'title')))
           ->add_meta (array ('property' => 'og:image:type', 'tag' => 'larger', 'content' => 'image/' . pathinfo ($img, PATHINFO_EXTENSION)))
           ->add_meta (array ('property' => 'og:image:width', 'tag' => 'larger', 'content' => '1200'))
           ->add_meta (array ('property' => 'og:image:height', 'tag' => 'larger', 'content' => '630'))
           ->add_meta (array ('property' => 'article:modified_time', 'content' => $works[0]->updated_at->format ('c')))
           ->add_meta (array ('property' => 'article:published_time', 'content' => $works[0]->created_at->format ('c')))
         ;

      if (($tags = column_array ($works[0]->tags, 'name')) || ($tags = Cfg::setting ('site', 'site', 'keywords')))
        foreach ($tags as $i => $tag)
          if (!$i) $this->add_meta (array ('property' => 'article:section', 'content' => $tag))->add_meta (array ('property' => 'article:tag', 'content' => $tag));
          else $this->add_meta (array ('property' => 'article:tag', 'content' => $tag));
    }
    return $this->set_title ('設計作品' . ' - ' . Cfg::setting ('site', 'site', 'title'))
                ->load_view (array (
                    'works' => $works,
                    'pagination' => $pagination,
                    'columns' => $columns
                  ));
  }
}
