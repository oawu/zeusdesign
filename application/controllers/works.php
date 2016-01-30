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
    if (!($id && ($work = Work::find_by_id ($id, array ('conditions' => array ('is_enabled = ?', Work::ENABLE_YES))))))
      return redirect_message (array ('works'), array (
          '_flash_message' => '找不到該筆資料。'
        ));

    $this->set_title ($work->title . ' - ' . Cfg::setting ('site', 'site', 'title'))
         ->add_js (resource_url ('resource', 'javascript', 'masonry_v3.1.2', 'masonry.pkgd.min.js'))
         ->add_meta (array ('name' => 'keywords', 'content' => $work->title . ',' . implode (',', Cfg::setting ('site', 'site', 'keywords'))))
         ->add_meta (array ('name' => 'description', 'content' => $work->mini_content () . ', ' . Cfg::setting ('site', 'site', 'description')))
         ->add_meta (array ('property' => 'og:title', 'content' => $work->title . ' - ' . Cfg::setting ('site', 'site', 'title')))
         ->add_meta (array ('property' => 'og:description', 'content' => $work->mini_content () . ' - ' . Cfg::setting ('site', 'site', 'description')))

         ->add_meta (array ('property' => 'og:image', 'tag' => 'larger', 'content' => $img = $work->cover->url ('1200x630c'), 'alt' => Cfg::setting ('site', 'site', 'title')))
         ->add_meta (array ('property' => 'og:image:type', 'tag' => 'larger', 'content' => 'image/' . pathinfo ($img, PATHINFO_EXTENSION)))
         ->add_meta (array ('property' => 'og:image:width', 'tag' => 'larger', 'content' => '1200'))
         ->add_meta (array ('property' => 'og:image:height', 'tag' => 'larger', 'content' => '630'))
          
         ->add_meta (array ('property' => 'og:type', 'content' => 'article'))
         ->add_meta (array ('property' => 'article:author', 'content' => Cfg::setting ('facebook', 'page', 'link')))
         ->add_meta (array ('property' => 'article:publisher', 'content' => Cfg::setting ('facebook', 'page', 'link')))
         ->add_meta (array ('name' => 'lastmod', 'property' => 'article:modified_time', 'content' => $work->updated_at->format ('c')))
         ->add_meta (array ('name' => 'pubdate', 'property' => 'article:published_time', 'content' => $work->created_at->format ('c')));
    
    if ($tags = column_array ($work->tags, 'name'))
      foreach ($tags as $i => $tag)
        if (!$i) $this->add_meta (array ('property' => 'article:section', 'content' => $tag))->add_meta (array ('property' => 'article:tag', 'content' => $tag));
        else $this->add_meta (array ('property' => 'article:tag', 'content' => $tag));
    else
      foreach (Cfg::setting ('site', 'site', 'keywords') as $i => $tag)
        if (!$i) $this->add_meta (array ('property' => 'article:section', 'content' => $tag))->add_meta (array ('property' => 'article:tag', 'content' => $tag));
        else $this->add_meta (array ('property' => 'article:tag', 'content' => $tag));

    if ($others = render_cell ('site_cache_cell', 'work_other', $work))
      foreach ($others as $other)
        $this->add_meta (array ('property' => 'og:see_also', 'content' => base_url ('work', $other->site_show_page_last_uri ())));

    $this->load_view (array (
            'work' => $work
          ));
  }
  public function index ($id = 0) {
    Work::addConditions ($conditions, 'is_enabled = ?', Work::ENABLE_YES);
    if ($id && ($tag = WorkTag::find_by_id ($id)) && ($work_id = column_array (WorkTagMapping::find ('all', array ('select' => 'work_id', 'conditions' => array ('work_tag_id = ?', $tag->id))), 'work_id')))
      Work::addConditions ($conditions, 'id IN (?)', $work_id);

    $works = Work::all (array ('conditions' => $conditions));

    $this->load_view (array (
        'id' => $id,
        'works' => $works
      ));
  }
}
