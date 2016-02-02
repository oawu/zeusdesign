<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Articles extends Admin_controller {
  private $article = null;

  public function __construct () {
    parent::__construct ();

    if (in_array ($this->uri->rsegments (2, 0), array ('edit', 'update', 'destroy')))
      if (!(($id = $this->uri->rsegments (3, 0)) && ($this->article = Article::find ('one', array ('conditions' => array ('id = ? AND destroy_user_id IS NULL', $id))))))
        return redirect_message (array ('admin', $this->get_class ()), array (
            '_flash_message' => '找不到該筆資料。'
          ));

    $this->add_tab ('文章列表', array ('href' => base_url ('admin', $this->get_class ()), 'index' => 1))
         ->add_tab ('新增文章', array ('href' => base_url ('admin', $this->get_class (), 'add'), 'index' => 2))
         ->add_hidden (array ('id' => 'tools_ckeditors_upload_image_url', 'value' => base_url ('admin', 'tools', 'ckeditors_upload_image')))
         ;
  }

  public function index ($offset = 0) {
    $columns = array (array ('key' => 'user_id',      'title' => '作者',     'sql' => 'user_id = ?', 'select' => array_map (function ($user) { return array ('value' => $user->id, 'text' => $user->name);}, User::all (array ('select' => 'id, name')))),
                      array ('key' => 'is_visibled',  'title' => '是否公開',  'sql' => 'is_visibled = ?', 'select' => array_map (function ($key) { return array ('value' => $key, 'text' => Article::$visibleNames[$key]);}, array_keys (Article::$visibleNames))),
                      array ('key' => 'title',        'title' => '標題',     'sql' => 'title LIKE ?'), 
                      array ('key' => 'content',      'title' => '內容',     'sql' => 'content LIKE ?'));
    $configs = array ('admin', $this->get_class (), '%s');
    $conditions = conditions ($columns, $configs);
    Article::addConditions ($conditions, 'destroy_user_id IS NULL');

    $limit = 25;
    $total = Article::count (array ('conditions' => $conditions));
    $offset = $offset < $total ? $offset : 0;

    $this->load->library ('pagination');
    $pagination = $this->pagination->initialize (array_merge (array ('total_rows' => $total, 'num_links' => 5, 'per_page' => $limit, 'uri_segment' => 0, 'base_url' => '', 'page_query_string' => false, 'first_link' => '第一頁', 'last_link' => '最後頁', 'prev_link' => '上一頁', 'next_link' => '下一頁', 'full_tag_open' => '<ul class="pagination">', 'full_tag_close' => '</ul>', 'first_tag_open' => '<li>', 'first_tag_close' => '</li>', 'prev_tag_open' => '<li>', 'prev_tag_close' => '</li>', 'num_tag_open' => '<li>', 'num_tag_close' => '</li>', 'cur_tag_open' => '<li class="active"><a href="#">', 'cur_tag_close' => '</a></li>', 'next_tag_open' => '<li>', 'next_tag_close' => '</li>', 'last_tag_open' => '<li>', 'last_tag_close' => '</li>'), $configs))->create_links ();
    $articles = Article::find ('all', array (
        'offset' => $offset,
        'limit' => $limit,
        'order' => 'id DESC',
        'include' => array ('user'),
        'conditions' => $conditions
      ));

    return $this->set_tab_index (1)
                ->set_subtitle ('文章列表')
                ->add_hidden (array ('id' => 'is_visibled_url', 'value' => base_url ('admin', $this->get_class (), 'is_visibled')))
                ->load_view (array (
                    'articles' => $articles,
                    'pagination' => $pagination,
                    'columns' => $columns
                  ));
  }
  public function add () {
    $posts = Session::getData ('posts', true);

    $posts['sources'] = isset ($posts['sources']) && $posts['sources'] ? array_slice (array_filter ($posts['sources'], function ($source) {
      return (isset ($source['title']) && $source['title']) || (isset ($source['href']) && $source['href']);
    }), 0) : array ();

    return $this->set_tab_index (2)
                ->set_subtitle ('新增文章')
                ->add_js (resource_url ('resource', 'javascript', 'ckeditor_d2015_05_18', 'ckeditor.js'), false)
                ->add_js (resource_url ('resource', 'javascript', 'ckeditor_d2015_05_18', 'config.js'), false)
                ->add_js (resource_url ('resource', 'javascript', 'ckeditor_d2015_05_18', 'adapters', 'jquery.js'), false)
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
    $posts['content'] = OAInput::post ('content', false);
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

    $article = null;
    $create = Article::transaction (function () use ($posts, $cover, &$article) {
      if (!verifyCreateOrm ($article = Article::create (array_intersect_key ($posts, Article::table ()->columns))))
        return false;
      
      if (!$article->cover->put ($cover))
        return false;

      return true;
    });

    if (!($create && $article))
      return redirect_message (array ('admin', $this->get_class (), 'add'), array (
          '_flash_message' => '新增失敗！',
          'posts' => $posts
        ));

    if ($posts['tag_ids'] && ($tag_ids = column_array (ArticleTag::find ('all', array ('select' => 'id', 'conditions' => array ('id IN (?)', $posts['tag_ids']))), 'id')))
      foreach ($tag_ids as $tag_id)
        ArticleTagMapping::transaction (function () use ($tag_id, $article) {
          return verifyCreateOrm (ArticleTagMapping::create (array_intersect_key (array ('article_tag_id' => $tag_id, 'article_id' => $article->id), ArticleTagMapping::table ()->columns)));
        });

    if ($posts['sources'])
      foreach ($posts['sources'] as $i => $source)
        ArticleSource::transaction (function () use ($i, $source, $article) {
          return verifyCreateOrm (ArticleSource::create (array_intersect_key (array_merge ($source, array (
            'article_id' => $article->id,
            'sort' => $i
            )), ArticleSource::table ()->columns)));
        });

    return redirect_message (array ('admin', $this->get_class ()), array (
        '_flash_message' => '新增成功！'
      ));
  }
  public function edit () {
    $posts = Session::getData ('posts', true);
    
    $posts['sources'] = isset ($posts['sources']) && $posts['sources'] ? array_slice (array_filter ($posts['sources'], function ($source) {
      return (isset ($source['title']) && $source['title']) || (isset ($source['href']) && $source['href']);
    }), 0) : ($this->article->sources ? array_filter (array_map (function ($source) {return array ('title' => $source->title, 'href' => $source->href);}, $this->article->sources), function ($source) {
      return (isset ($source['title']) && $source['title']) || (isset ($source['href']) && $source['href']);
    }) : array ());

    return $this->add_tab ('編輯文章', array ('href' => base_url ('admin', $this->get_class (), $this->article->id, 'edit'), 'index' => 3))
                ->set_tab_index (3)
                ->set_subtitle ('編輯文章')
                ->add_js (resource_url ('resource', 'javascript', 'ckeditor_d2015_05_18', 'ckeditor.js'), false)
                ->add_js (resource_url ('resource', 'javascript', 'ckeditor_d2015_05_18', 'config.js'), false)
                ->add_js (resource_url ('resource', 'javascript', 'ckeditor_d2015_05_18', 'adapters', 'jquery.js'), false)
                ->load_view (array (
                    'posts' => $posts,
                    'article' => $this->article
                  ));
  }
  public function update () {
    if (!$this->has_post ())
      return redirect_message (array ('admin', $this->get_class (), $this->article->id, 'edit'), array (
          '_flash_message' => '非 POST 方法，錯誤的頁面請求。'
        ));

    $posts = OAInput::post ();
    $posts['content'] = OAInput::post ('content', false);
    $cover = OAInput::file ('cover');

    if (!((string)$this->article->cover || $cover))
      return redirect_message (array ('admin', $this->get_class (), $this->article->id, 'edit'), array (
          '_flash_message' => '請選擇圖片(gif、jpg、png)檔案!',
          'posts' => $posts
        ));

    if ($msg = $this->_validation_posts ($posts))
      return redirect_message (array ('admin', $this->get_class (), $this->article->id, 'edit'), array (
          '_flash_message' => $msg,
          'posts' => $posts
        ));

    if ($columns = array_intersect_key ($posts, $this->article->table ()->columns))
      foreach ($columns as $column => $value)
        $this->article->$column = $value;

    $article = $this->article;
    $update = ArticleTag::transaction (function () use ($article, $cover) {
      if (!$article->save ())
        return false;

      if ($cover && !$article->cover->put ($cover))
        return false;
      
      return true;
    });

    if (!$update)
      return redirect_message (array ('admin', $this->get_class (), $this->article->id, 'edit'), array (
          '_flash_message' => '更新失敗！',
          'posts' => $posts
        ));

    $ori_ids = column_array ($article->mappings, 'article_tag_id');

    if (($del_ids = array_diff ($ori_ids, $posts['tag_ids'])) && ($mappings = ArticleTagMapping::find ('all', array ('select' => 'id, article_tag_id', 'conditions' => array ('article_id = ? AND article_tag_id IN (?)', $article->id, $del_ids)))))
      foreach ($mappings as $mapping)
        ArticleTagMapping::transaction (function () use ($mapping) {
          return $mapping->destroy ();
        });

    if (($add_ids = array_diff ($posts['tag_ids'], $ori_ids)) && ($tags = ArticleTag::find ('all', array ('select' => 'id', 'conditions' => array ('id IN (?)', $add_ids)))))
      foreach ($tags as $tag)
        ArticleTagMapping::transaction (function () use ($tag, $article) {
          return verifyCreateOrm (ArticleTagMapping::create (Array_intersect_key (array ('article_tag_id' => $tag->id, 'article_id' => $article->id), ArticleTagMapping::table ()->columns)));
        });

    if ($article->sources)
      foreach ($article->sources as $source)
        ArticleSource::transaction (function () use ($source) {
          return $source->destroy ();
        });

    if ($posts['sources'])
      foreach ($posts['sources'] as $i => $source)
        ArticleSource::transaction (function () use ($i, $source, $article) {
          return verifyCreateOrm (ArticleSource::create (array_intersect_key (array_merge ($source, array (
            'article_id' => $article->id,
            'sort' => $i
            )), ArticleSource::table ()->columns)));
        });

    $this->_clean_cell ($article);
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

    $article = $this->article;
    if ($columns = array_intersect_key ($posts, $article->table ()->columns))
      foreach ($columns as $column => $value)
        $article->$column = $value;

    $delete = Article::transaction (function () use ($article) { return $article->save (); });

    if (!$delete)
      return redirect_message (array ('admin', $this->get_class ()), array (
          '_flash_message' => '刪除失敗！',
        ));

    $this->_clean_cell ($article);
    return redirect_message (array ('admin', $this->get_class ()), array (
        '_flash_message' => '刪除成功！'
      ));
  }

  public function is_visibled ($id = 0) {
    if (!($id && ($article = Article::find_by_id ($id, array ('select' => 'id, is_visibled, updated_at')))))
      return $this->output_json (array ('status' => false, 'message' => '當案不存在，或者您的權限不夠喔！'));

    $posts = OAInput::post ();

    if ($msg = $this->_validation_is_visibled_posts ($posts))
      return $this->output_json (array ('status' => false, 'message' => $msg, 'content' => Article::$visibleNames[$article->is_visibled]));

    if ($columns = array_intersect_key ($posts, $article->table ()->columns))
      foreach ($columns as $column => $value)
        $article->$column = $value;

    $update = Article::transaction (function () use ($article) { return $article->save (); });

    if (!$update)
      return $this->output_json (array ('status' => false, 'message' => '更新失敗！', 'content' => Article::$visibleNames[$article->is_visibled]));

    $this->_clean_cell ($article);
    return $this->output_json (array ('status' => true, 'message' => '更新成功！', 'content' => Article::$visibleNames[$article->is_visibled]));
  }
  private function _clean_cell ($article) {
    if (isset ($article->id)) clean_cell ('site_cache_cell', 'article', $article->id);
    clean_cell ('site_article_asides_cell', 'news');
    clean_cell ('site_article_asides_cell', 'hots');
  }
  private function _validation_posts (&$posts) {
    if (!(isset ($posts['user_id']) && is_numeric ($posts['user_id'] = trim ($posts['user_id'])) && ($posts['user_id'] >= 0) && (!$posts['user_id'] || User::find_by_id ($posts['user_id']))))
      return '沒有選擇作者 或 作者錯誤！';

    if (!(isset ($posts['title']) && ($posts['title'] = trim ($posts['title']))))
      return '沒有填寫標題！';
    
    if (!(isset ($posts['content']) && ($posts['content'] = trim ($posts['content']))))
      return '沒有填寫內容！';
    
    if (!(isset ($posts['is_visibled']) && ($posts['is_visibled'] = trim ($posts['is_visibled']))))
      $posts['is_finished'] = 0;
    else
      $posts['is_finished'] = 1;

    if (!isset ($posts['tag_ids']))
      $posts['tag_ids'] = array ();

    $posts['sources'] = isset ($posts['sources']) && ($posts['sources'] = array_filter (array_map (function ($source) {
          $return = array (
              'title' => trim ($source['title']),
              'href' => trim ($source['href']));
          return $return['href'] ? $return : null;
        }, $posts['sources']))) ? $posts['sources'] : array ();

    return '';
  }
  private function _validation_is_visibled_posts (&$posts) {
    if (!(isset ($posts['is_visibled']) && is_numeric ($posts['is_visibled']) && in_array ($posts['is_visibled'], array_keys (Article::$visibleNames)))) return '參數錯誤！';
    return '';
  }
}
