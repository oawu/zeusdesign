<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Works extends Admin_controller {
  private $work = null;

  public function __construct () {
    parent::__construct ();

    if (in_array ($this->uri->rsegments (2, 0), array ('edit', 'update', 'destroy', 'sort')))
      if (!(($id = $this->uri->rsegments (3, 0)) && ($this->work = Work::find ('one', array ('conditions' => array ('id = ? AND destroy_user_id IS NULL', $id))))))
        return redirect_message (array ('admin', $this->get_class ()), array (
            '_flash_message' => '找不到該筆資料。'
          ));

    $this->add_tab ('作品列表', array ('href' => base_url ('admin', $this->get_class ()), 'index' => 1))
         ->add_tab ('新增作品', array ('href' => base_url ('admin', $this->get_class (), 'add'), 'index' => 2));
  }

  public function index ($offset = 0) {
    $columns = array (array ('key' => 'user_id', 'title' => '作者',     'sql' => 'user_id = ?', 'select' => array_map (function ($user) { return array ('value' => $user->id, 'text' => $user->name);}, User::all (array ('select' => 'id, name')))),
                      array ('key' => 'title',   'title' => '標題', 'sql' => 'title LIKE ?'), 
                      array ('key' => 'content', 'title' => '內容', 'sql' => 'content LIKE ?'),
                      );
    $configs = array ('admin', $this->get_class (), '%s');
    $conditions = conditions ($columns, $configs);
    Work::addConditions ($conditions, 'destroy_user_id IS NULL');

    $limit = 25;
    $total = Work::count (array ('conditions' => $conditions));
    $offset = $offset < $total ? $offset : 0;

    $this->load->library ('pagination');
    $pagination = $this->pagination->initialize (array_merge (array ('total_rows' => $total, 'num_links' => 5, 'per_page' => $limit, 'uri_segment' => 0, 'base_url' => '', 'page_query_string' => false, 'first_link' => '第一頁', 'last_link' => '最後頁', 'prev_link' => '上一頁', 'next_link' => '下一頁', 'full_tag_open' => '<ul class="pagination">', 'full_tag_close' => '</ul>', 'first_tag_open' => '<li>', 'first_tag_close' => '</li>', 'prev_tag_open' => '<li>', 'prev_tag_close' => '</li>', 'num_tag_open' => '<li>', 'num_tag_close' => '</li>', 'cur_tag_open' => '<li class="active"><a href="#">', 'cur_tag_close' => '</a></li>', 'next_tag_open' => '<li>', 'next_tag_close' => '</li>', 'last_tag_open' => '<li>', 'last_tag_close' => '</li>'), $configs))->create_links ();
    $works = Work::find ('all', array (
        'offset' => $offset,
        'limit' => $limit,
        'order' => 'id DESC',
        'include' => array ('user', 'pictures'),
        'conditions' => $conditions
      ));

    return $this->set_tab_index (1)
                ->set_subtitle ('作品列表')
                ->load_view (array (
                    'works' => $works,
                    'pagination' => $pagination,
                    'columns' => $columns
                  ));
  }
  public function add () {
    $posts = Session::getData ('posts', true);
    $blocks = array_map (function ($block) {
      return  array (
          'title' => htmlentities ($block['title']),
          'items' => array_map (function ($item) {
            return array (
                'title' => htmlentities ($item['title']),
                'link' => htmlentities ($item['link'])
              );
          }, $block['items'])
        );
    }, json_encode (isset ($posts['blocks']) ? $posts['blocks'] : array ()));

    return $this->set_tab_index (2)
                ->set_subtitle ('新增作品')
                ->load_view (array (
                    'posts' => $posts,
                    'blocks' => $blocks
                  ));
  }
  public function create () {
    if (!$this->has_post ())
      return redirect_message (array ('admin', $this->get_class (), 'add'), array (
          '_flash_message' => '非 POST 方法，錯誤的頁面請求。'
        ));

    $posts = OAInput::post ();
    $cover = OAInput::file ('cover');
    $pictures = OAInput::file ('pictures[]');

    if (!$cover)
      return redirect_message (array ('admin', $this->get_class (), 'add'), array (
          '_flash_message' => '請選擇照片(gif、jpg、png)檔案，或提供照片網址!',
          'posts' => $posts
        ));

    if ($msg = $this->_validation_posts ($posts))
      return redirect_message (array ('admin', $this->get_class (), 'add'), array (
          '_flash_message' => $msg,
          'posts' => $posts
        ));

    $work = null;
    $create = Work::transaction (function () use ($posts, $cover, &$work) {
      return verifyCreateOrm ($work = Work::create (array_intersect_key ($posts, Work::table ()->columns))) && $work->cover->put ($cover);
    });

    if (!($create && $work))
      return redirect_message (array ('admin', $this->get_class (), 'add'), array (
          '_flash_message' => '新增失敗！',
          'posts' => $posts
        ));

    if ($posts['tag_ids'] && ($tag_ids = column_array (WorkTag::find ('all', array ('select' => 'id', 'conditions' => array ('id IN (?)', $posts['tag_ids']))), 'id')))
      foreach ($tag_ids as $tag_id)
        WorkTagMapping::transaction (function () use ($tag_id, $work) {
          return verifyCreateOrm (WorkTagMapping::create (array_intersect_key (array ('work_tag_id' => $tag_id, 'work_id' => $work->id), WorkTagMapping::table ()->columns)));
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

    return redirect_message (array ('admin', $this->get_class ()), array (
        '_flash_message' => '新增成功！'
      ));
  }
  public function edit () {
    $posts = Session::getData ('posts', true);
    $blocks = array_map (function ($block) {
      return  array (
          'title' => htmlentities ($block['title']),
          'items' => array_map (function ($item) {
            return array (
                'title' => htmlentities ($item['title']),
                'link' => htmlentities ($item['link'])
              );
          }, $block['items'])
        );
    }, isset ($posts['blocks']) ? $posts['blocks'] : $this->work->blocks ());

    return $this->add_tab ('編輯作品', array ('href' => base_url ('admin', $this->get_class (), $this->work->id, 'edit'), 'index' => 3))
                ->set_tab_index (3)
                ->set_subtitle ('編輯作品')
                ->load_view (array (
                    'posts' => $posts,
                    'blocks' => $blocks,
                    'work' => $this->work
                  ));
  }
  public function update () {
    if (!$this->has_post ())
      return redirect_message (array ('admin', $this->get_class (), $this->work->id, 'edit'), array (
          '_flash_message' => '非 POST 方法，錯誤的頁面請求。'
        ));

    $posts = OAInput::post ();
    $cover = OAInput::file ('cover');

    if (!((string)$this->work->cover || $cover))
      return redirect_message (array ('admin', $this->get_class (), $this->work->id, 'edit'), array (
          '_flash_message' => '請選擇圖片(gif、jpg、png)檔案!',
          'posts' => $posts
        ));

    if ($msg = $this->_validation_posts ($posts))
      return redirect_message (array ('admin', $this->get_class (), $this->work->id, 'edit'), array (
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
      return redirect_message (array ('admin', $this->get_class (), $this->work->id, 'edit'), array (
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

    $ori_ids = column_array ($work->mappings, 'work_tag_id');

    if (($del_ids = array_diff ($ori_ids, $posts['tag_ids'])) && ($mappings = WorkTagMapping::find ('all', array ('select' => 'id, work_tag_id', 'conditions' => array ('work_id = ? AND work_tag_id IN (?)', $work->id, $del_ids)))))
      foreach ($mappings as $mapping)
        WorkTagMapping::transaction (function () use ($mapping) {
          return $mapping->destroy ();
        });

    if (($add_ids = array_diff ($posts['tag_ids'], $ori_ids)) && ($tags = WorkTag::find ('all', array ('select' => 'id', 'conditions' => array ('id IN (?)', $add_ids)))))
      foreach ($tags as $tag)
        WorkTagMapping::transaction (function () use ($tag, $work) {
          return verifyCreateOrm (WorkTagMapping::create (array_intersect_key (array ('work_tag_id' => $tag->id, 'work_id' => $work->id), WorkTagMapping::table ()->columns)));
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

    $this->_clean_cell ($work);
    return redirect_message (array ('admin', $this->get_class ()), array (
        '_flash_message' => '更新成功！'
      ));
  }
  public function destroy () {
    if (!User::current ()->id)
      return redirect_message (array ('admin', $this->get_class ()), array (
          '_flash_message' => '刪除失敗！',
        ));

    $posts = array (
        'destroy_user_id' => User::current ()->id
      );

    $work = $this->work;
    if ($columns = array_intersect_key ($posts, $work->table ()->columns))
      foreach ($columns as $column => $value)
        $work->$column = $value;

    $delete = Work::transaction (function () use ($work) { return $work->save (); });

    if (!$delete)
      return redirect_message (array ('admin', $this->get_class ()), array (
          '_flash_message' => '刪除失敗！',
        ));

    $this->_clean_cell ($work);
    return redirect_message (array ('admin', $this->get_class ()), array (
        '_flash_message' => '刪除成功！'
      ));
  }

  private function _clean_cell ($work) {
    if (isset ($work->id)) clean_cell ('site_cache_cell', 'work', $work->id);
  }
  private function _validation_posts (&$posts) {
    if (!(isset ($posts['user_id']) && is_numeric ($posts['user_id'] = trim ($posts['user_id'])) && ($posts['user_id'] >= 0) && (!$posts['user_id'] || User::find_by_id ($posts['user_id']))))
      return '沒有選擇作者 或 作者錯誤！';

    if (!(isset ($posts['title']) && ($posts['title'] = trim ($posts['title']))))
      return '沒有填寫標題！';

    if (!(isset ($posts['content']) && ($posts['content'] = trim ($posts['content']))))
      return '沒有填寫內容！';

    if (!isset ($posts['pic_ids'])) $posts['pic_ids'] = array ();
    if (!isset ($posts['tag_ids'])) $posts['tag_ids'] = array ();

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
