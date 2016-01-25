<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Invoices extends Admin_controller {
  private $invoice = null;

  public function __construct () {
    parent::__construct ();

    if (in_array ($this->uri->rsegments (2, 0), array ('edit', 'update', 'destroy')))
      if (!(($id = $this->uri->rsegments (3, 0)) && ($this->invoice = Invoice::find_by_id ($id))))
        return redirect_message (array ('admin', $this->get_class ()), array (
            '_flash_message' => '找不到該筆資料。'
          ));

    $this->add_tab ('帳務列表', array ('href' => base_url ('admin', $this->get_class ()), 'index' => 1))
         ->add_tab ('新增帳務', array ('href' => base_url ('admin', $this->get_class (), 'add'), 'index' => 2))
         ->add_css (resource_url ('resource', 'css', 'jquery-ui_v1.10.3', 'jquery-ui-1.10.3.custom.min.css'))
         ->add_js (resource_url ('resource', 'javascript', 'jquery-ui_v1.10.3', 'jquery-ui-1.10.3.custom.min.js'))
         ->add_js (resource_url ('resource', 'javascript', 'jquery-ui_v1.10.3', 'datepicker.lang', 'jquery.ui.datepicker-zh-TW.js'))
         ;
  }

  public function index ($offset = 0) {
    $columns = array ('name' => 'string', 'contact' => 'string', 'memo' => 'string');
    $configs = array ('admin', $this->get_class (), '%s');

    $conditions = array (implode (' AND ', conditions ($columns, $configs, 'Invoice', OAInput::get ())));

    $limit = 25;
    $total = Invoice::count (array ('conditions' => $conditions));
    $offset = $offset < $total ? $offset : 0;

    $this->load->library ('pagination');
    $pagination = $this->pagination->initialize (array_merge (array ('total_rows' => $total, 'num_links' => 5, 'per_page' => $limit, 'uri_segment' => 0, 'base_url' => '', 'page_query_string' => false, 'first_link' => '第一頁', 'last_link' => '最後頁', 'prev_link' => '上一頁', 'next_link' => '下一頁', 'full_tag_open' => '<ul class="pagination">', 'full_tag_close' => '</ul>', 'first_tag_open' => '<li>', 'first_tag_close' => '</li>', 'prev_tag_open' => '<li>', 'prev_tag_close' => '</li>', 'num_tag_open' => '<li>', 'num_tag_close' => '</li>', 'cur_tag_open' => '<li class="active"><a href="#">', 'cur_tag_close' => '</a></li>', 'next_tag_open' => '<li>', 'next_tag_close' => '</li>', 'last_tag_open' => '<li>', 'last_tag_close' => '</li>'), $configs))->create_links ();
    $invoices = Invoice::find ('all', array (
        'offset' => $offset,
        'limit' => $limit,
        'order' => 'id DESC',
        'include' => array ('pictures', 'user'),
        'conditions' => $conditions
      ));

    return $this->set_tab_index (1)
                ->set_subtitle ('帳務列表')
                ->load_view (array (
                    'invoices' => $invoices,
                    'pagination' => $pagination,
                    'has_search' => array_filter ($columns),
                    'columns' => $columns
                  ));
  }
  public function add () {
    $posts = Session::getData ('posts', true);

    return $this->set_tab_index (2)
                ->set_subtitle ('新增帳務')
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

    $invoice = null;
    $create = Invoice::transaction (function () use ($posts, $cover, &$invoice) {
      return verifyCreateOrm ($invoice = Invoice::create (array_intersect_key ($posts, Invoice::table ()->columns))) && $invoice->cover->put ($cover);
    });

    if (!($create && $invoice))
      return redirect_message (array ('admin', $this->get_class (), 'add'), array (
          '_flash_message' => '新增失敗！',
          'posts' => $posts
        ));

    if ($pictures)
      foreach ($pictures as $picture)
        InvoicePicture::transaction (function () use ($picture, $invoice) {
          return verifyCreateOrm ($pic = InvoicePicture::create (array_intersect_key (array_merge ($picture, array ('invoice_id' => $invoice->id)), InvoicePicture::table ()->columns))) && $pic->name->put ($picture);
        });

    return redirect_message (array ('admin', $this->get_class ()), array (
        '_flash_message' => '新增成功！'
      ));
  }
  public function edit () {
    $posts = Session::getData ('posts', true);

    return $this->add_tab ('編輯帳務', array ('href' => base_url ('admin', $this->get_class (), $this->invoice->id, 'edit'), 'index' => 3))
                ->set_tab_index (3)
                ->set_subtitle ('編輯帳務')
                ->load_view (array (
                    'posts' => $posts,
                    'invoice' => $this->invoice
                  ));
  }
  public function update () {
    if (!$this->has_post ())
      return redirect_message (array ('admin', $this->get_class (), $this->invoice->id, 'edit'), array (
          '_flash_message' => '非 POST 方法，錯誤的頁面請求。'
        ));

    $posts = OAInput::post ();
    $cover = OAInput::file ('cover');

    if (!((string)$this->invoice->cover || $cover))
      return redirect_message (array ('admin', $this->get_class (), $this->invoice->id, 'edit'), array (
          '_flash_message' => '請選擇圖片(gif、jpg、png)檔案!',
          'posts' => $posts
        ));

    if ($msg = $this->_validation_posts ($posts))
      return redirect_message (array ('admin', $this->get_class (), $this->invoice->id, 'edit'), array (
          '_flash_message' => $msg,
          'posts' => $posts
        ));

    if ($columns = array_intersect_key ($posts, $this->invoice->table ()->columns))
      foreach ($columns as $column => $value)
        $this->invoice->$column = $value;

    $invoice = $this->invoice;
    $update = InvoiceTag::transaction (function () use ($invoice, $cover) {
      if (!$invoice->save ())
        return false;

      if ($cover && !$invoice->cover->put ($cover))
        return false;
      
      return true;
    });

    if (!$update)
      return redirect_message (array ('admin', $this->get_class (), $this->invoice->id, 'edit'), array (
          '_flash_message' => '更新失敗！',
          'posts' => $posts
        ));

    if (($del_ids = array_diff (column_array ($invoice->pictures, 'id'), $posts['pic_ids'])) && ($pictures = InvoicePicture::find ('all', array ('select' => 'id, name', 'conditions' => array ('id IN (?)', $del_ids)))))
      foreach ($pictures as $picture)
        InvoicePicture::transaction (function () use ($picture) {
          return $picture->destroy ();
        });

    if ($pictures = OAInput::file ('pictures[]'))
      foreach ($pictures as $picture)
        InvoicePicture::transaction (function () use ($picture, $invoice) {
          return verifyCreateOrm ($pic = InvoicePicture::create (array_intersect_key (array_merge ($picture, array ('invoice_id' => $invoice->id)), InvoicePicture::table ()->columns))) && $pic->name->put ($picture);
        });

    return redirect_message (array ('admin', $this->get_class ()), array (
        '_flash_message' => '更新成功！'
      ));
  }
  public function destroy () {
    $invoice = $this->invoice;
    $delete = Invoice::transaction (function () use ($invoice) {
      return $invoice->destroy ();
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
    if (!(isset ($posts['invoice_tag_id']) && is_numeric ($posts['invoice_tag_id'] = trim ($posts['invoice_tag_id'])) && ($posts['invoice_tag_id'] >= 0) && (!$posts['invoice_tag_id'] || InvoiceTag::find_by_id ($posts['invoice_tag_id']))))
      return '沒有選擇類別 或 類別錯誤！';

    if (!(isset ($posts['user_id']) && is_numeric ($posts['user_id'] = trim ($posts['user_id'])) && ($posts['user_id'] >= 0) && (!$posts['user_id'] || User::find_by_id ($posts['user_id']))))
      return '沒有選擇負責人 或 負責人錯誤！';

    if (!(isset ($posts['name']) && ($posts['name'] = trim ($posts['name']))))
      return '沒有填寫名稱！';
    
    if (!(isset ($posts['contact']) && ($posts['contact'] = trim ($posts['contact']))))
      return '沒有填寫窗口！';
    
    if (!(isset ($posts['money']) && is_numeric ($posts['money'] = trim ($posts['money'])) && ($posts['money'] > 0) && ($posts['money'] < 4294967296)))
      return '沒有填寫金額 或 金額錯誤！';

    if (!(isset ($posts['closing_at']) && ($posts['closing_at'] = trim ($posts['closing_at']))))
      return '沒有選擇結案日期！';

    if (!isset ($posts['pic_ids'])) $posts['pic_ids'] = array ();

    return '';
  }
}
