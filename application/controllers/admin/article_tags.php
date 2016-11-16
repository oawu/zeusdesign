<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Article_tags extends Admin_controller {
  private $tag = null;

  public function __construct () {
    parent::__construct ();

    if (in_array ($this->uri->rsegments (2, 0), array ('edit', 'update', 'destroy')))
      if (!(($id = $this->uri->rsegments (3, 0)) && ($this->tag = ArticleTag::find_by_id ($id))))
        return redirect_message (array ('admin', $this->get_class ()), array (
            '_flash_message' => '找不到該筆資料。'
          ));

    $this->add_tab ('類別列表', array ('href' => base_url ('admin', $this->get_class ()), 'index' => 1))
         ->add_tab ('新增類別', array ('href' => base_url ('admin', $this->get_class (), 'add'), 'index' => 2));
  }

  public function index ($offset = 0) {
    $columns = array (array ('key' => 'name', 'title' => '名稱', 'sql' => 'name LIKE ?'), 
                  );
    $configs = array ('admin', $this->get_class (), '%s');
    $conditions = conditions ($columns, $configs);

    $limit = 25;
    $total = ArticleTag::count (array ('conditions' => $conditions));
    $offset = $offset < $total ? $offset : 0;

    $this->load->library ('pagination');
    $pagination = $this->pagination->initialize (array_merge (array ('total_rows' => $total, 'num_links' => 5, 'per_page' => $limit, 'uri_segment' => 0, 'base_url' => '', 'page_query_string' => false, 'first_link' => '第一頁', 'last_link' => '最後頁', 'prev_link' => '上一頁', 'next_link' => '下一頁', 'full_tag_open' => '<ul class="pagination">', 'full_tag_close' => '</ul>', 'first_tag_open' => '<li>', 'first_tag_close' => '</li>', 'prev_tag_open' => '<li>', 'prev_tag_close' => '</li>', 'num_tag_open' => '<li>', 'num_tag_close' => '</li>', 'cur_tag_open' => '<li class="active"><a href="#">', 'cur_tag_close' => '</a></li>', 'next_tag_open' => '<li>', 'next_tag_close' => '</li>', 'last_tag_open' => '<li>', 'last_tag_close' => '</li>'), $configs))->create_links ();
    $tags = ArticleTag::find ('all', array (
        'offset' => $offset,
        'limit' => $limit,
        'order' => 'id DESC',
        'conditions' => $conditions
      ));

    return $this->set_tab_index (1)
                ->set_subtitle ('文章類別列表')
                ->load_view (array (
                    'tags' => $tags,
                    'pagination' => $pagination,
                    'columns' => $columns
                  ));
  }
  public function add () {
    return redirect_message (array ('admin', $this->get_class ()), array (
        '_flash_message' => '請至新系統調整。'
      ));
    $posts = Session::getData ('posts', true);
    
    return $this->set_tab_index (2)
                ->set_subtitle ('新增文章類別')
                ->load_view (array (
                    'posts' => $posts
                  ));
  }
  public function create () {
    return redirect_message (array ('admin', $this->get_class ()), array (
        '_flash_message' => '請至新系統調整。'
      ));
    if (!$this->has_post ())
      return redirect_message (array ('admin', $this->get_class (), 'add'), array (
          '_flash_message' => '非 POST 方法，錯誤的頁面請求。'
        ));

    $posts = OAInput::post ();

    if ($msg = $this->_validation_posts ($posts))
      return redirect_message (array ('admin', $this->get_class (), 'add'), array (
          '_flash_message' => $msg,
          'posts' => $posts
        ));

    $create = ArticleTag::transaction (function () use ($posts) {
      return verifyCreateOrm ($tag = ArticleTag::create (array_intersect_key ($posts, ArticleTag::table ()->columns)));
    });

    if (!$create)
      return redirect_message (array ('admin', $this->get_class (), 'add'), array (
          '_flash_message' => '新增失敗！',
          'posts' => $posts
        ));

    $this->_clean_cell ();
    return redirect_message (array ('admin', $this->get_class ()), array (
        '_flash_message' => '新增成功！'
      ));
  }
  public function edit () {
    return redirect_message (array ('admin', $this->get_class ()), array (
        '_flash_message' => '請至新系統調整。'
      ));
    $posts = Session::getData ('posts', true);
    
    return $this->add_tab ('編輯類別', array ('href' => base_url ('admin', $this->get_class (), 'edit', $this->tag->id), 'index' => 3))
                ->set_tab_index (3)
                ->set_subtitle ('編輯文章類別')
                ->load_view (array (
                    'posts' => $posts,
                    'tag' => $this->tag
                  ));
  }
  public function update () {
    return redirect_message (array ('admin', $this->get_class ()), array (
        '_flash_message' => '請至新系統調整。'
      ));
    if (!$this->has_post ())
      return redirect_message (array ('admin', $this->get_class (), $this->tag->id, 'edit'), array (
          '_flash_message' => '非 POST 方法，錯誤的頁面請求。'
        ));

    $posts = OAInput::post ();

    if ($msg = $this->_validation_posts ($posts))
      return redirect_message (array ('admin', $this->get_class (), $this->tag->id, 'edit'), array (
          '_flash_message' => $msg,
          'posts' => $posts
        ));

    if ($columns = array_intersect_key ($posts, $this->tag->table ()->columns))
      foreach ($columns as $column => $value)
        $this->tag->$column = $value;

    $tag = $this->tag;
    $update = ArticleTag::transaction (function () use ($tag) {
      return $tag->save ();
    });

    if (!$update)
      return redirect_message (array ('admin', $this->get_class (), $this->tag->id, 'edit'), array (
          '_flash_message' => '更新失敗！',
          'posts' => $posts
        ));

    $this->_clean_cell ();
    return redirect_message (array ('admin', $this->get_class ()), array (
        '_flash_message' => '更新成功！'
      ));
  }
  public function destroy () {
    return redirect_message (array ('admin', $this->get_class ()), array (
        '_flash_message' => '請至新系統調整。'
      ));
    
    $tag = $this->tag;
    
    $delete = ArticleTag::transaction (function () use ($tag) {
      return $tag->destroy ();
    });

    if (!$delete)
      return redirect_message (array ('admin', $this->get_class ()), array (
          '_flash_message' => '刪除失敗！',
        ));

    $this->_clean_cell ();
    return redirect_message (array ('admin', $this->get_class ()), array (
        '_flash_message' => '刪除成功！'
      ));
  }
  private function _clean_cell () {
    clean_cell ('site_article_asides_cell', 'tags');
  }
  private function _validation_posts (&$posts) {
    if (!(isset ($posts['name']) && ($posts['name'] = trim ($posts['name']))))
      return '沒有填寫名稱！';

    return '';
  }
}
