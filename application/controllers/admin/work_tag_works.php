<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Work_tag_works extends Admin_controller {
  private $tag = null;
  private $work = null;

  public function __construct () {
    parent::__construct ();

    if (!(($id = $this->uri->rsegments (3, 0)) && ($this->tag = WorkTag::find_by_id ($id))))
      return redirect_message (array ('admin', 'work_tags'), array (
          '_flash_message' => '找不到該筆資料。'
        ));

    if (in_array ($this->uri->rsegments (2, 0), array ('edit', 'update', 'destroy', 'sort')))
      if (!(($id = $this->uri->rsegments (4, 0)) && ($this->work = Work::find_by_id ($id))))
        return redirect_message (array ('admin', 'work_tags', $this->tag->id, 'works'), array (
            '_flash_message' => '找不到該筆資料。'
          ));

    $this->add_param ('class', 'work_tags')
         ->add_tab ('標籤列表', array ('href' => base_url ($this->tag->work_tag_id ? array ('admin', 'work_tags', $this->tag->work_tag_id, 'tags') : array ('admin', 'work_tags')), 'index' => 1))
         ->add_tab ('作品列表', array ('href' => base_url ('admin', 'works'), 'index' => 2))
         ->add_tab ('新增作品', array ('href' => base_url ('admin', 'work_tags', $this->tag->id, 'works', 'add'), 'index' => 3));
         ;
  }

  public function index ($tag_id, $offset = 0) {
    $columns = array ('title' => 'string', 'content' => 'string');
    $configs = array ('admin', 'work_tags', '%s');

    $conditions = array (implode (' AND ', conditions ($columns, $configs, 'Work', OAInput::get ())));

    if ($work_id = column_array (WorkTagMapping::find ('all', array ('select' => 'work_id', 'conditions' => array ('work_tag_id = ?', $this->tag->id))), 'work_id'))
      Work::addConditions ($conditions, 'id IN (?)', $work_id);
    else
      Work::addConditions ($conditions, 'id = ?', -1);

    $limit = 25;
    $total = Work::count (array ('conditions' => $conditions));
    $offset = $offset < $total ? $offset : 0;

    $this->load->library ('pagination');
    $pagination = $this->pagination->initialize (array_merge (array ('total_rows' => $total, 'num_links' => 5, 'per_page' => $limit, 'uri_segment' => 0, 'base_url' => '', 'page_query_string' => false, 'first_link' => '第一頁', 'last_link' => '最後頁', 'prev_link' => '上一頁', 'next_link' => '下一頁', 'full_tag_open' => '<ul class="pagination">', 'full_tag_close' => '</ul>', 'first_tag_open' => '<li>', 'first_tag_close' => '</li>', 'prev_tag_open' => '<li>', 'prev_tag_close' => '</li>', 'num_tag_open' => '<li>', 'num_tag_close' => '</li>', 'cur_tag_open' => '<li class="active"><a href="#">', 'cur_tag_close' => '</a></li>', 'next_tag_open' => '<li>', 'next_tag_close' => '</li>', 'last_tag_open' => '<li>', 'last_tag_close' => '</li>'), $configs))->create_links ();
    $works = Work::find ('all', array (
        'offset' => $offset,
        'limit' => $limit,
        'order' => 'id DESC',
        'include' => array ('pictures'),
        'conditions' => $conditions
      ));

    return $this->set_tab_index (2)
                ->set_subtitle ($this->tag->name . '內的作品列表')
                ->load_view (array (
                    'tag' => $this->tag,
                    'works' => $works,
                    'pagination' => $pagination,
                    'has_search' => array_filter ($columns),
                    'columns' => $columns
                  ));
  }
  public function add () {
    $posts = Session::getData ('posts', true);

    return $this->set_tab_index (3)
                ->set_subtitle ('新增作品')
                ->load_view (array (
                    'tag' => $this->tag,
                    'posts' => $posts
                  ));
  }
  public function create () {
    if (!$this->has_post ())
      return redirect_message (array ('admin', 'work_tags', $this->tag->id, 'works', 'add'), array (
          '_flash_message' => '非 POST 方法，錯誤的頁面請求。'
        ));

    $posts = OAInput::post ();
    $cover = OAInput::file ('cover');
    $pictures = OAInput::file ('pictures[]');

    if (!$cover)
      return redirect_message (array ('admin', 'work_tags', $this->tag->id, 'works', 'add'), array (
          '_flash_message' => '請選擇照片(gif、jpg、png)檔案，或提供照片網址!',
          'posts' => $posts
        ));

    if ($msg = $this->_validation_posts ($posts))
      return redirect_message (array ('admin', 'work_tags', $this->tag->id, 'works', 'add'), array (
          '_flash_message' => $msg,
          'posts' => $posts
        ));

    $work = null;
    $create = Work::transaction (function () use ($posts, $cover, &$work) {
      return verifyCreateOrm ($work = Work::create (array_intersect_key ($posts, Work::table ()->columns))) && $work->cover->put ($cover);
    });

    if (!($create && $work))
      return redirect_message (array ('admin', 'work_tags', $this->tag->id, 'works', 'add'), array (
          '_flash_message' => '新增失敗！',
          'posts' => $posts
        ));

    $tag = $this->tag;
    WorkTagMapping::transaction (function () use ($tag, $work) {
      return verifyCreateOrm (WorkTagMapping::create (array_intersect_key (array ('work_tag_id' => $tag->id, 'work_id' => $work->id), WorkTagMapping::table ()->columns)));
    });

    if ($blocks = $posts['blocks'])
      foreach ($blocks as $block)
        if (!($b = null) && WorkBlock::transaction (function () use ($block, $work, &$b) { return verifyCreateOrm ($b = WorkBlock::create (array_intersect_key (array_merge ($block, array ('work_id' => $work->id)), WorkBlock::table ()->columns))); }))
          if (($items = $block['items']) && $b)
            foreach ($items as $item)
              WorkBlockItem::transaction (function () use ($item, $b) {
                return verifyCreateOrm (WorkBlockItem::create (array_intersect_key (array_merge ($item, array ('work_block_id' => $b->id)), WorkBlockItem::table ()->columns)));
              });

    if ($pictures)
      foreach ($pictures as $picture)
        WorkPicture::transaction (function () use ($picture, $work) {
          return verifyCreateOrm ($pic = WorkPicture::create (array_intersect_key (array_merge ($picture, array ('work_id' => $work->id)), WorkPicture::table ()->columns))) && $pic->name->put ($picture);
        });

    return redirect_message (array ('admin', 'work_tags', $tag->id, 'works'), array (
      '_flash_message' => '新增成功！'
    ));
  }
  public function edit () {
    $posts = Session::getData ('posts', true);

    return $this->add_tab ('編輯作品', array ('href' => base_url ('admin', 'work_tags', $this->tag->id, 'works', $this->work->id, 'edit'), 'index' => 4))
                ->set_tab_index (4)
                ->set_subtitle ('編輯作品')
                ->load_view (array (
                    'posts' => $posts,
                    'tag' => $this->tag,
                    'work' => $this->work
                  ));
  }
  public function update () {
    if (!$this->has_post ())
      return redirect_message (array ('admin', 'work_tags', $this->tag->id, 'works', $this->work->id, 'edit'), array (
          '_flash_message' => '非 POST 方法，錯誤的頁面請求。'
        ));

    $posts = OAInput::post ();
    $cover = OAInput::file ('cover');

    if (!((string)$this->work->cover || $cover))
      return redirect_message (array ('admin', 'work_tags', $this->tag->id, 'works', $this->work->id, 'edit'), array (
          '_flash_message' => '請選擇圖片(gif、jpg、png)檔案!',
          'posts' => $posts
        ));

    if ($msg = $this->_validation_posts ($posts))
      return redirect_message (array ('admin', 'work_tags', $this->tag->id, 'works', $this->work->id, 'edit'), array (
          '_flash_message' => $msg,
          'posts' => $posts
        ));

    if ($columns = array_intersect_key ($posts, $this->work->table ()->columns))
      foreach ($columns as $column => $value)
        $this->work->$column = $value;

    $work = $this->work;
    $update = WorkTag::transaction (function () use ($work, $cover) {
      if (!$work->save ())
        return false;

      if ($cover && !$work->cover->put ($cover))
        return false;
      
      return true;
    });

    if (!$update)
      return redirect_message (array ('admin', 'work_tags', $this->tag->id, 'works', $this->work->id, 'edit'), array (
          '_flash_message' => '更新失敗！',
          'posts' => $posts
        ));

    if (($del_ids = array_diff (column_array ($work->pictures, 'id'), $posts['pic_ids'])) && ($pictures = WorkPicture::find ('all', array ('select' => 'id, name', 'conditions' => array ('id IN (?)', $del_ids)))))
      foreach ($pictures as $picture)
        WorkPicture::transaction (function () use ($picture) {
          return $picture->destroy ();
        });

    if ($pictures = OAInput::file ('pictures[]'))
      foreach ($pictures as $picture)
        WorkPicture::transaction (function () use ($picture, $work) {
          return verifyCreateOrm ($pic = WorkPicture::create (array_intersect_key (array_merge ($picture, array ('work_id' => $work->id)), WorkPicture::table ()->columns))) && $pic->name->put ($picture);
        });
    
    $clean_blocks = WorkBlock::transaction (function () use ($work) {
      foreach ($work->blocks as $block) if (!$block->destroy ()) return false;
      return true;
    });

    if ($blocks = $posts['blocks'])
      foreach ($blocks as $block)
        if (!($b = null) && WorkBlock::transaction (function () use ($block, $work, &$b) { return verifyCreateOrm ($b = WorkBlock::create (array_intersect_key (array_merge ($block, array ('work_id' => $work->id)), WorkBlock::table ()->columns))); }))
          if (($items = $block['items']) && $b)
            foreach ($items as $item)
              WorkBlockItem::transaction (function () use ($item, $b) {
                return verifyCreateOrm (WorkBlockItem::create (array_intersect_key (array_merge ($item, array ('work_block_id' => $b->id)), WorkBlockItem::table ()->columns)));
              });

    return redirect_message (array ('admin', 'work_tags', $this->tag->id, 'works', $this->work->id, 'edit'), array (
        '_flash_message' => '更新成功！'
      ));
  }
  public function destroy () {
    $work = $this->work;
    $delete = Work::transaction (function () use ($work) {
      return $work->destroy ();
    });

    if (!$delete)
      return redirect_message (array ('admin', 'work_tags', $this->tag->id, 'works'), array (
          '_flash_message' => '刪除失敗！',
        ));

    return redirect_message (array ('admin', 'work_tags', $this->tag->id, 'works'), array (
        '_flash_message' => '刪除成功！'
      ));
  }
  private function _validation_posts (&$posts) {
    if (!(isset ($posts['title']) && ($posts['title'] = trim ($posts['title']))))
      return '沒有填寫標題！';

    if (!(isset ($posts['content']) && ($posts['content'] = trim ($posts['content']))))
      return '沒有填寫內容！';

    if (!isset ($posts['pic_ids'])) $posts['pic_ids'] = array ();

    if (isset ($posts['blocks']))
      $posts['blocks'] = array_filter ($posts['blocks'], function (&$blocks) {
        $blocks['items'] = isset ($blocks['items']) && $blocks['items'] ? array_filter ($blocks['items'], function ($item) {
          return ($item['title'] = trim ($item['title'])) || ($item['link'] = trim ($item['link']));
        }) : array ();
        return $blocks['title'] = trim ($blocks['title']);
      });
    else
      $posts['blocks'] = array ();

    return '';
  }
}
