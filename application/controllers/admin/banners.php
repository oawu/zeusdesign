<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Banners extends Admin_controller {
  private $banner = null;

  public function __construct () {
    parent::__construct ();

    if (in_array ($this->uri->rsegments (2, 0), array ('edit', 'update', 'destroy', 'sort')))
      if (!(($id = $this->uri->rsegments (3, 0)) && ($this->banner = Banner::find_by_id ($id))))
        return redirect_message (array ('admin', $this->get_class ()), array (
            '_flash_message' => '找不到該筆資料。'
          ));

    $this->add_tab ('Banner列表', array ('href' => base_url ('admin', $this->get_class ()), 'index' => 1))
         ->add_tab ('新增Banner', array ('href' => base_url ('admin', $this->get_class (), 'add'), 'index' => 2));
  }
  public function index ($offset = 0) {
    $columns = array (array ('key' => 'title',   'title' => '標題', 'sql' => 'title LIKE ?'), 
                      array ('key' => 'content', 'title' => '內容', 'sql' => 'content LIKE ?'), 
                      );
    
    $conditions = conditions ($columns, $configs = array ('admin', $this->get_class (), '%s'));

    $limit = 25;
    $total = Banner::count (array ('conditions' => $conditions));
    $offset = $offset < $total ? $offset : 0;

    $this->load->library ('pagination');
    $pagination = $this->pagination->initialize (array_merge (array ('total_rows' => $total, 'num_links' => 5, 'per_page' => $limit, 'uri_segment' => 0, 'base_url' => '', 'page_query_string' => false, 'first_link' => '第一頁', 'last_link' => '最後頁', 'prev_link' => '上一頁', 'next_link' => '下一頁', 'full_tag_open' => '<ul class="pagination">', 'full_tag_close' => '</ul>', 'first_tag_open' => '<li>', 'first_tag_close' => '</li>', 'prev_tag_open' => '<li>', 'prev_tag_close' => '</li>', 'num_tag_open' => '<li>', 'num_tag_close' => '</li>', 'cur_tag_open' => '<li class="active"><a href="#">', 'cur_tag_close' => '</a></li>', 'next_tag_open' => '<li>', 'next_tag_close' => '</li>', 'last_tag_open' => '<li>', 'last_tag_close' => '</li>'), $configs))->create_links ();
    $banners = Banner::find ('all', array (
        'offset' => $offset,
        'limit' => $limit,
        'order' => 'sort DESC',
        'conditions' => $conditions
      ));

    return $this->set_tab_index (1)
                ->set_subtitle ('Banner列表')
                ->load_view (array (
                    'banners' => $banners,
                    'pagination' => $pagination,
                    'columns' => $columns
                  ));
  }
  public function add () {
    $posts = Session::getData ('posts', true);

    return $this->set_tab_index (2)
                ->set_subtitle ('新增Banner')
                ->load_view (array (
                    'posts' => $posts
                  ));
  }
  public function create () {
    if (!$this->has_post ())
      return redirect_message (array ('admin', $this->get_class (), 'add'), array (
          '_flash_message' => '非 POST 方法，錯誤的頁面請求。'
        ));

    $posts = OAInput::post ();
    $cover = OAInput::file ('cover');

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
    $posts['sort'] = Banner::count ();

    $create = Banner::transaction (function () use ($posts, $cover) {
      return verifyCreateOrm ($banner = Banner::create (array_intersect_key ($posts, Banner::table ()->columns))) && $banner->cover->put ($cover);
    });

    if (!$create)
      return redirect_message (array ('admin', $this->get_class (), 'add'), array (
          '_flash_message' => '新增失敗！',
          'posts' => $posts
        ));

    return redirect_message (array ('admin', $this->get_class ()), array (
        '_flash_message' => '新增成功！'
      ));
  }
  public function edit () {
    $posts = Session::getData ('posts', true);

    return $this->add_tab ('編輯Banner', array ('href' => base_url ('admin', $this->get_class (), $this->banner->id, 'edit'), 'index' => 3))
                ->set_tab_index (3)
                ->set_subtitle ('編輯Banner')
                ->load_view (array (
                    'posts' => $posts,
                    'banner' => $this->banner
                  ));
  }
  public function update () {
    if (!$this->has_post ())
      return redirect_message (array ('admin', $this->get_class (), $this->banner->id, 'edit'), array (
          '_flash_message' => '非 POST 方法，錯誤的頁面請求。'
        ));

    $posts = OAInput::post ();
    $cover = OAInput::file ('cover');

    if (!((string)$this->banner->cover || $cover))
      return redirect_message (array ('admin', $this->get_class (), $this->banner->id, 'edit'), array (
          '_flash_message' => '請選擇圖片(gif、jpg、png)檔案!',
          'posts' => $posts
        ));

    if ($msg = $this->_validation_posts ($posts))
      return redirect_message (array ('admin', $this->get_class (), $this->banner->id, 'edit'), array (
          '_flash_message' => $msg,
          'posts' => $posts
        ));

    if ($columns = array_intersect_key ($posts, $this->banner->table ()->columns))
      foreach ($columns as $column => $value)
        $this->banner->$column = $value;

    $banner = $this->banner;
    $update = Banner::transaction (function () use ($banner, $cover) {
      if (!$banner->save ())
        return false;

      if ($cover && !$banner->cover->put ($cover))
        return false;
      
      return true;
    });

    if (!$update)
      return redirect_message (array ('admin', $this->get_class (), $this->banner->id, 'edit'), array (
          '_flash_message' => '更新失敗！',
          'posts' => $posts
        ));
    return redirect_message (array ('admin', $this->get_class ()), array (
        '_flash_message' => '更新成功！'
      ));
  }
  public function sort ($id, $sort) {
    if (!in_array ($sort, array ('up', 'down')))
      return redirect_message (array ('admin', $this->get_class ()), array (
          '_flash_message' => '排序失敗！'
        ));

    $total = Banner::count ();

    switch ($sort) {
      case 'up':
        $sort = $this->banner->sort;
        $this->banner->sort = $this->banner->sort + 1 >= $total ? 0 : $this->banner->sort + 1;
        break;

      case 'down':
        $sort = $this->banner->sort;
        $this->banner->sort = $this->banner->sort - 1 < 0 ? $total - 1 : $this->banner->sort - 1;
        break;
    }

    Banner::addConditions ($conditions, 'sort = ?', $this->banner->sort);

    $banner = $this->banner;
    $update = Banner::transaction (function () use ($conditions, $banner, $sort) {
      if (($next = Banner::find ('one', array ('conditions' => $conditions))) && (($next->sort = $sort) || true))
        if (!$next->save ()) return false;
      if (!$banner->save ()) return false;

      return true;
    });

    if (!$update)
      return redirect_message (array ('admin', $this->get_class ()), array (
          '_flash_message' => '排序失敗！',
          'posts' => $posts
        ));
    return redirect_message (array ('admin', $this->get_class ()), array (
        '_flash_message' => '排序成功！'
      ));
  }
  public function destroy () {
    $banner = $this->banner;
    $delete = Banner::transaction (function () use ($banner) {
      return $banner->destroy ();
    });

    if (!$delete)
      return redirect_message (array ('admin', $this->get_class ()), array (
          '_flash_message' => '刪除失敗！',
        ));

    return redirect_message (array ('admin', $this->get_class ()), array (
        '_flash_message' => '刪除成功！'
      ));
  }
  private function _validation_posts (&$posts) {
    if (!(isset ($posts['title']) && ($posts['title'] = trim ($posts['title']))))
      return '沒有填寫標題！';

    if (!(isset ($posts['content']) && ($posts['content'] = trim ($posts['content']))))
      return '沒有填寫內容！';

    if (!(isset ($posts['link']) && ($posts['link'] = trim ($posts['link']))))
      return '沒有填寫鏈結！';

    if (!(isset ($posts['is_enabled']) && is_numeric ($posts['is_enabled'] = trim ($posts['is_enabled'])) && in_array ($posts['is_enabled'], array_keys (Banner::$enableNames))))
      return '選擇是否上下架錯誤！';

    if (!(isset ($posts['target']) && is_numeric ($posts['target'] = trim ($posts['target'])) && in_array ($posts['target'], array_keys (Banner::$targetNames))))
      return '選擇鏈結開啟方式錯誤！';

    return '';
  }
}
