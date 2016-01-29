<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Contacts extends Admin_controller {
  private $contact = null;

  public function __construct () {
    parent::__construct ();

    if (in_array ($this->uri->rsegments (2, 0), array ('edit', 'update', 'destroy', 'sort')))
      if (!(($id = $this->uri->rsegments (3, 0)) && ($this->contact = Contact::find_by_id ($id))))
        return redirect_message (array ('admin', $this->get_class ()), array (
            '_flash_message' => '找不到該筆資料。'
          ));

    $this->add_tab ('留言列表', array ('href' => base_url ('admin', $this->get_class ()), 'index' => 1));
    $this->add_tab ('留言列表(隱藏)', array ('href' => base_url ('admin', $this->get_class (), 'hidden'), 'index' => 2));
  }

  private function _conditions ($offset, $is_visibled) {
    $columns = array (array ('key' => 'name',   'title' => '稱呼', 'sql' => 'name LIKE ?'), 
                      array ('key' => 'email', 'title' => 'E-Mail', 'sql' => 'email LIKE ?'), 
                      array ('key' => 'message', 'title' => '留言', 'sql' => 'message LIKE ?'), 
                      array ('key' => 'ip', 'title' => 'IP', 'sql' => 'ip LIKE ?'), 
                      );
    
    $conditions = conditions ($columns, $configs = array ('admin', $this->get_class (), '%s'));
    Contact::addConditions ($conditions, 'is_visibled = ?', $is_visibled);

    $limit = 25;
    $total = Contact::count (array ('conditions' => $conditions));
    $offset = $offset < $total ? $offset : 0;

    $this->load->library ('pagination');
    $pagination = $this->pagination->initialize (array_merge (array ('total_rows' => $total, 'num_links' => 5, 'per_page' => $limit, 'uri_segment' => 0, 'base_url' => '', 'page_query_string' => false, 'first_link' => '第一頁', 'last_link' => '最後頁', 'prev_link' => '上一頁', 'next_link' => '下一頁', 'full_tag_open' => '<ul class="pagination">', 'full_tag_close' => '</ul>', 'first_tag_open' => '<li>', 'first_tag_close' => '</li>', 'prev_tag_open' => '<li>', 'prev_tag_close' => '</li>', 'num_tag_open' => '<li>', 'num_tag_close' => '</li>', 'cur_tag_open' => '<li class="active"><a href="#">', 'cur_tag_close' => '</a></li>', 'next_tag_open' => '<li>', 'next_tag_close' => '</li>', 'last_tag_open' => '<li>', 'last_tag_close' => '</li>'), $configs))->create_links ();
    $contacts = Contact::find ('all', array (
        'offset' => $offset,
        'limit' => $limit,
        'conditions' => $conditions
      ));

    return array (
        'contacts' => $contacts,
        'pagination' => $pagination,
        'columns' => $columns,
      );
  }
  public function hidden ($offset = 0) {
    $conditions = $this->_conditions ($offset, Contact::NO_VISIBLED);

    return $this->set_tab_index (2)
                ->set_subtitle ('留言列表(隱藏)')
                ->add_js (resource_url ('resource', 'javascript', 'jquery-timeago_v1.3.1', 'jquery.timeago.js'))
                ->add_js (resource_url ('resource', 'javascript', 'jquery-timeago_v1.3.1', 'locales', 'jquery.timeago.zh-TW.js'))
                ->add_hidden (array ('id' => 'is_replied_url', 'value' => base_url ('admin', $this->get_class (), 'is_replied')))
                ->load_view (array (
                    'contacts' => $conditions['contacts'],
                    'pagination' => $conditions['pagination'],
                    'columns' => $conditions['columns']
                  ));
  }
  public function index ($offset = 0) {
    $conditions = $this->_conditions ($offset, Contact::IS_VISIBLED);

    return $this->set_tab_index (1)
                ->set_subtitle ('留言列表')
                ->add_js (resource_url ('resource', 'javascript', 'jquery-timeago_v1.3.1', 'jquery.timeago.js'))
                ->add_js (resource_url ('resource', 'javascript', 'jquery-timeago_v1.3.1', 'locales', 'jquery.timeago.zh-TW.js'))
                ->add_hidden (array ('id' => 'no_replied_url', 'value' => base_url ('admin', $this->get_class (), 'no_replied')))
                ->load_view (array (
                    'contacts' => $conditions['contacts'],
                    'pagination' => $conditions['pagination'],
                    'columns' => $conditions['columns']
                  ));
  }

  public function no_visibled ($id = 0) {
    if (!$this->has_post ())
      return redirect_message (array ('admin', $this->get_class (), 'add'), array (
          '_flash_message' => '非 POST 方法，錯誤的頁面請求。'
        ));

    if (!($id && ($contact = Contact::find_by_id ($id, array ('select' => 'id, is_visibled, updated_at')))))
      return redirect_message (array ('admin', $this->get_class ()), array (
          '_flash_message' => '找不到該筆資料。'
        ));

    $contact->is_visibled = Contact::NO_VISIBLED;

    $delete = Contact::transaction (function () use ($contact) {
      return $contact->save ();
    });

    if (!$delete)
      return redirect_message (array ('admin', $this->get_class ()), array (
          '_flash_message' => '隱藏失敗！',
        ));

    return redirect_message (array ('admin', $this->get_class ()), array (
        '_flash_message' => '隱藏成功！'
      ));
  }
  public function is_visibled ($id = 0) {
    if (!$this->has_post ())
      return redirect_message (array ('admin', $this->get_class (), 'add'), array (
          '_flash_message' => '非 POST 方法，錯誤的頁面請求。'
        ));

    if (!($id && ($contact = Contact::find_by_id ($id, array ('select' => 'id, is_visibled, updated_at')))))
      return redirect_message (array ('admin', $this->get_class (), 'hidden'), array (
          '_flash_message' => '找不到該筆資料。'
        ));

    $contact->is_visibled = Contact::IS_VISIBLED;

    $delete = Contact::transaction (function () use ($contact) {
      return $contact->save ();
    });

    if (!$delete)
      return redirect_message (array ('admin', $this->get_class (), 'hidden'), array (
          '_flash_message' => '顯示失敗！',
        ));

    return redirect_message (array ('admin', $this->get_class (), 'hidden'), array (
        '_flash_message' => '顯示成功！'
      ));
  }
  public function is_replied ($id = 0) {
    if (!($id && ($contact = Contact::find_by_id ($id, array ('select' => 'id, is_replied, updated_at')))))
      return $this->output_json (array ('status' => false, 'message' => '當案不存在，或者您的權限不夠喔！'));

    $posts = OAInput::post ();

    if ($msg = $this->_validation_is_replied_posts ($posts))
      return $this->output_json (array ('status' => false, 'message' => $msg, 'content' => Contact::$replyNames[$contact->is_replied]));

    if ($columns = array_intersect_key ($posts, $contact->table ()->columns))
      foreach ($columns as $column => $value)
        $contact->$column = $value;

    $update = Contact::transaction (function () use ($contact) { return $contact->save (); });

    if ($update) return $this->output_json (array ('status' => true, 'message' => '更新成功！', 'content' => Contact::$replyNames[$contact->is_replied]));
    else return $this->output_json (array ('status' => false, 'message' => '更新失敗！', 'content' => Contact::$replyNames[$contact->is_replied]));
  }
  private function _validation_is_replied_posts (&$posts) {
    if (!(isset ($posts['is_replied']) && is_numeric ($posts['is_replied']) && in_array ($posts['is_replied'], array_keys (Contact::$replyNames)))) return '參數錯誤！';
    return '';
  }
}
