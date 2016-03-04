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
      if (!(($id = $this->uri->rsegments (3, 0)) && ($this->invoice = Invoice::find ('one', array ('conditions' => array ('id = ? AND destroy_user_id IS NULL', $id))))))
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

  private function _build_excel ($invoices, $infos) {
    $excel = new OAExcel ();
    
    $excel->getActiveSheet ()->getRowDimension (1)->setRowHeight (25);
    $excel->getActiveSheet ()->getColumnDimension ('A')->setWidth (15);
    $excel->getActiveSheet ()->getColumnDimension ('B')->setWidth (10);
    $excel->getActiveSheet ()->getColumnDimension ('C')->setWidth (10);
    $excel->getActiveSheet ()->getColumnDimension ('D')->setWidth (8);
    $excel->getActiveSheet ()->getColumnDimension ('E')->setWidth (8);
    $excel->getActiveSheet ()->getColumnDimension ('F')->setWidth (8);
    $excel->getActiveSheet ()->getColumnDimension ('G')->setWidth (11);
    $excel->getActiveSheet ()->getColumnDimension ('H')->setWidth (15);
    $excel->getActiveSheet ()->freezePaneByColumnAndRow (0, 2);
    $excel->getActiveSheet ()->getStyle ('G')->getAlignment ()->setWrapText (true); 

    $excel->getActiveSheet ()->getStyle ('A1:H1')->applyFromArray (array (
      'fill' => array (
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'color' => array('rgb' => 'fff3ca')
      ),
      'borders' => array (
        'allborders' => array (
          'style' => PHPExcel_Style_Border::BORDER_THIN,
          'color' => array('rgb' => '888888')))));

    foreach ($invoices as $i => $invoice) {
      $j = 0;
      foreach ($infos as $info) {
        if ($i == 0) {
          $excel->getActiveSheet ()->getStyle (chr (65 + $j) . ($i + 1))->getAlignment ()->setVertical (PHPExcel_Style_Alignment::VERTICAL_TOP);
          $excel->getActiveSheet ()->getStyle (chr (65 + $j) . ($i + 1))->getAlignment ()->setHorizontal (PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
          $excel->getActiveSheet ()->getStyle (chr (65 + $j) . ($i + 1))->getFont ()->setName ('新細明體');
          $excel->getActiveSheet ()->SetCellValue (chr (65 + $j) . ($i + 1), $info['title']);
        }
        eval ('$val = ' . $info['exp'] . ';');
        
        $excel->getActiveSheet ()->getStyle (chr (65 + $j) . ($i + 2))->getAlignment ()->setVertical (PHPExcel_Style_Alignment::VERTICAL_TOP);
        $excel->getActiveSheet ()->getStyle (chr (65 + $j) . ($i + 2))->getAlignment ()->setHorizontal (PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet ()->getStyle (chr (65 + $j) . ($i + 2))->getFont ()->setName ("新細明體");
        $excel->getActiveSheet ()->SetCellValue (chr (65 + $j) . ($i + 2), $val);
        $excel->getActiveSheet ()->getStyle (chr (65 + $j) . ($i + 2))->getNumberFormat ()->setFormatCode ($info['format']);
        $j++;
      }
    }
    return $excel;
  }
   // function x () {
   //  // for ($i=1; $i < 100; $i++) { 
   //  //   $j = ($j = (ceil ($i / 3) - 1) * 7 + 1);
   //  //   echo (($i % 3 < 2 ? $i % 3 < 1 ? 'G' : 'A' : 'D') . $j) . "<br/>";
   //  // }
   //  foreach (Invoice::all () as $i) {
   //    $i->cover->put_url ($i->cover->url ());
   //    foreach ($i->pictures as $p)
   //      $p->name->put_url ($p->name->url ());
   //  }
   // }
  private function _excel_add_image ($sheet, $image, &$i) {
    if (!(string)$image) return --$i ? '' : '';

    download_web_file ($image->url ('350x230p'), $filepath = FCPATH . implode (DIRECTORY_SEPARATOR, array_merge (Cfg::system ('orm_uploader', 'uploader', 'temp_directory'), array ((string)$image))));

    $objDrawing = new PHPExcel_Worksheet_Drawing ();
    $objDrawing->setPath ($filepath);

    $j = ($j = (ceil ($i / 3) - 1) * 7 + 1);
    $objDrawing->setCoordinates (($i % 3 < 2 ? $i % 3 < 1 ? 'G' : 'A' : 'D') . $j);
    $objDrawing->setOffsetX (20);
    $objDrawing->setOffsetY (8);
    $objDrawing->setWidth (175);

    $objDrawing->setWorksheet ($sheet); 

    return $filepath;
  }
  private function _array_2d_to_1d ($a) {
    $o = array ();
    foreach ($a as $b) foreach ($b as $c) array_push($o, $c);
    return $o;
  }
  private function _search_columns () {
    return array (array ('key' => 'user_id',     'title' => '負責人',   'sql' => 'user_id = ?', 'select' => array_map (function ($user) { return array ('value' => $user->id, 'text' => $user->name);}, User::all (array ('select' => 'id, name')))),
                  array ('key' => 'is_finished', 'title' => '是否請款', 'sql' => 'is_finished = ?', 'select' => array_map (function ($key) { return array ('value' => $key, 'text' => Invoice::$finishNames[$key]);}, array_keys (Invoice::$finishNames))),
                  array ('key' => 'name',        'title' => '專案名稱', 'sql' => 'name LIKE ?'), 
                  array ('key' => 'contact',     'title' => '窗口',    'sql' => 'contact LIKE ?'),
                  array ('key' => 'memo',        'title' => '備註',    'sql' => 'memo LIKE ?'),
                  array ('key' => 'start',       'title' => '開始時間', 'sql' => 'closing_at >= ?'),
                  array ('key' => 'end',         'title' => '結束時間', 'sql' => 'closing_at <= ?'));
  }
  public function export () {
    $columns = $this->_search_columns ();
    $configs = array ('admin', $this->get_class (), '%s');
    $conditions = conditions ($columns, $configs);
    Invoice::addConditions ($conditions, 'destroy_user_id IS NULL');

    $invoices = Invoice::find ('all', array (
        'order' => 'closing_at DESC',
        'include' => array ('pictures', 'user', 'tag'),
        'conditions' => $conditions
      ));

    $this->load->library ('OAExcel');

    $infos = array (array ('title' => '專案名稱', 'format' => PHPExcel_Style_NumberFormat::FORMAT_TEXT,          'exp' => '$invoice->name'),
                    array ('title' => '窗口',     'format' => PHPExcel_Style_NumberFormat::FORMAT_TEXT,          'exp' => '$invoice->contact'),
                    array ('title' => '數量',   'format' => PHPExcel_Style_NumberFormat::FORMAT_NUMBER,        'exp' => '$invoice->quantity'),
                    array ('title' => '單價',   'format' => PHPExcel_Style_NumberFormat::FORMAT_NUMBER,        'exp' => '$invoice->single_money'),
                    array ('title' => '總金額',   'format' => PHPExcel_Style_NumberFormat::FORMAT_NUMBER,        'exp' => '$invoice->all_money'),
                    array ('title' => '分類',     'format' => PHPExcel_Style_NumberFormat::FORMAT_TEXT,          'exp' => '$invoice->tag ? $invoice->tag->name : "其他"'),
                    array ('title' => '結案日期', 'format' => PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2, 'exp' => '$invoice->closing_at ? $invoice->closing_at->format ("Y-m-d") : ""'),
                    array ('title' => '備註',    'format' => PHPExcel_Style_NumberFormat::FORMAT_TEXT,           'exp' => '$invoice->memo'));

    $excel = $this->_build_excel ($invoices, $infos);
    $excel->getActiveSheet ()->setTitle ('帳務列表');

    $excel->createSheet (1);
    $excel->setActiveSheetIndex (1)->setTitle ('圖片列表');

    $that = $this;

    $filepaths = array_filter ($this->_array_2d_to_1d (array_map (function ($invoice) use ($that, $excel, &$i) {
      return array_merge (array ($that->_excel_add_image ($excel->getActiveSheet (), $invoice->cover, ($i = isset ($i) ? $i + 1 : 1))),
        array_map (function ($picture) use ($that, $excel, &$i) {
          return $that->_excel_add_image ($excel->getActiveSheet (), $picture->name, ++$i);
        }, $invoice->pictures));
    }, $invoices)));

    $excel->setActiveSheetIndex (0);

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; charset=utf8');
    header('Content-Disposition: attachment; filename=宙思_帳務_' . date ('Ymd') . '.xlsx');

    $objWriter = new PHPExcel_Writer_Excel2007 ($excel);
    $objWriter->save ("php://output");
    
    array_map (function ($filepath) {return @unlink ($filepath); }, $filepaths);
  }
  public function index ($offset = 0) {
    $columns = $this->_search_columns ();
    $configs = array ('admin', $this->get_class (), '%s');
    $conditions = conditions ($columns, $configs);
    Invoice::addConditions ($conditions, 'destroy_user_id IS NULL');

    $limit = 25;
    $total = Invoice::count (array ('conditions' => $conditions));
    $offset = $offset < $total ? $offset : 0;

    $this->load->library ('pagination');
    $pagination = $this->pagination->initialize (array_merge (array ('total_rows' => $total, 'num_links' => 5, 'per_page' => $limit, 'uri_segment' => 0, 'base_url' => '', 'page_query_string' => false, 'first_link' => '第一頁', 'last_link' => '最後頁', 'prev_link' => '上一頁', 'next_link' => '下一頁', 'full_tag_open' => '<ul class="pagination">', 'full_tag_close' => '</ul>', 'first_tag_open' => '<li>', 'first_tag_close' => '</li>', 'prev_tag_open' => '<li>', 'prev_tag_close' => '</li>', 'num_tag_open' => '<li>', 'num_tag_close' => '</li>', 'cur_tag_open' => '<li class="active"><a href="#">', 'cur_tag_close' => '</a></li>', 'next_tag_open' => '<li>', 'next_tag_close' => '</li>', 'last_tag_open' => '<li>', 'last_tag_close' => '</li>'), $configs))->create_links ();
    $invoices = Invoice::find ('all', array (
        'offset' => $offset,
        'limit' => $limit,
        'order' => 'id DESC',
        'include' => array ('pictures', 'user', 'tag'),
        'conditions' => $conditions
      ));

    return $this->set_tab_index (1)
                ->set_subtitle ('帳務列表')
                ->add_hidden (array ('id' => 'is_finished_url', 'value' => base_url ('admin', $this->get_class (), 'is_finished')))
                ->load_view (array (
                    'invoices' => $invoices,
                    'pagination' => $pagination,
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

    // if (!$cover)
    //   return redirect_message (array ('admin', $this->get_class (), 'add'), array (
    //       '_flash_message' => '請選擇照片(gif、jpg、png)檔案，或提供照片網址!',
    //       'posts' => $posts
    //     ));

    if ($msg = $this->_validation_posts ($posts))
      return redirect_message (array ('admin', $this->get_class (), 'add'), array (
          '_flash_message' => $msg,
          'posts' => $posts
        ));

    $invoice = null;
    $create = Invoice::transaction (function () use ($posts, $cover, &$invoice) {
      if (!verifyCreateOrm ($invoice = Invoice::create (array_intersect_key ($posts, Invoice::table ()->columns))))
        return false;
      
      if ($cover && !$invoice->cover->put ($cover))
        return false;

      return true;
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

    // if (!((string)$this->invoice->cover || $cover))
    //   return redirect_message (array ('admin', $this->get_class (), $this->invoice->id, 'edit'), array (
    //       '_flash_message' => '請選擇圖片(gif、jpg、png)檔案!',
    //       'posts' => $posts
    //     ));

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
    if (!User::current ()->id)
      return redirect_message (array ('admin', $this->get_class ()), array (
          '_flash_message' => '刪除失敗！',
        ));

    $posts = array (
        'destroy_user_id' => User::current ()->id
      );

    $invoice = $this->invoice;
    if ($columns = array_intersect_key ($posts, $invoice->table ()->columns))
      foreach ($columns as $column => $value)
        $invoice->$column = $value;

    $delete = Invoice::transaction (function () use ($invoice) { return $invoice->save (); });

    if (!$delete)
      return redirect_message (array ('admin', $this->get_class ()), array (
          '_flash_message' => '刪除失敗！',
        ));

    return redirect_message (array ('admin', $this->get_class ()), array (
        '_flash_message' => '刪除成功！'
      ));
  }

  public function is_finished ($id = 0) {
    if (!($id && ($invoice = Invoice::find_by_id ($id, array ('select' => 'id, is_finished, updated_at')))))
      return $this->output_json (array ('status' => false, 'message' => '當案不存在，或者您的權限不夠喔！'));

    $posts = OAInput::post ();

    if ($msg = $this->_validation_is_finished_posts ($posts))
      return $this->output_json (array ('status' => false, 'message' => $msg, 'content' => Invoice::$finishNames[$invoice->is_finished]));

    if ($columns = array_intersect_key ($posts, $invoice->table ()->columns))
      foreach ($columns as $column => $value)
        $invoice->$column = $value;

    $update = Invoice::transaction (function () use ($invoice) { return $invoice->save (); });

    if ($update) return $this->output_json (array ('status' => true, 'message' => '更新成功！', 'content' => Invoice::$finishNames[$invoice->is_finished]));
    else return $this->output_json (array ('status' => false, 'message' => '更新失敗！', 'content' => Invoice::$finishNames[$invoice->is_finished]));
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
    
    if (!(isset ($posts['quantity']) && is_numeric ($posts['quantity'] = trim ($posts['quantity'])) && ($posts['quantity'] > 0) && ($posts['quantity'] < 4294967296)))
      return '沒有填寫數量 或 數量錯誤！';
    
    if (!(isset ($posts['single_money']) && is_numeric ($posts['single_money'] = trim ($posts['single_money'])) && ($posts['single_money'] > 0) && ($posts['single_money'] < 4294967296)))
      return '沒有填寫單價 或 單價錯誤！';
    
    if (!(isset ($posts['all_money']) && is_numeric ($posts['all_money'] = trim ($posts['all_money'])) && ($posts['all_money'] > 0) && ($posts['all_money'] < 4294967296)))
      return '沒有填寫金額 或 金額錯誤！';

    if (!(isset ($posts['closing_at']) && ($posts['closing_at'] = trim ($posts['closing_at'])) && (DateTime::createFromFormat ('Y-m-d', $posts['closing_at']) !== false)))
      return '沒有選擇結案日期 或 格式錯誤！';

    if (!isset ($posts['pic_ids'])) $posts['pic_ids'] = array ();

    if (!(isset ($posts['is_finished']) && ($posts['is_finished'] = trim ($posts['is_finished']))))
      $posts['is_finished'] = 0;
    else
      $posts['is_finished'] = 1;

    return '';
  }
  private function _validation_is_finished_posts (&$posts) {
    if (!(isset ($posts['is_finished']) && is_numeric ($posts['is_finished']) && in_array ($posts['is_finished'], array_keys (Invoice::$finishNames)))) return '參數錯誤！';
    return '';
  }
}
