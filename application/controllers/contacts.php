<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Contacts extends Site_controller {

  public function index () {
    $posts = Session::getData ('posts', true);

    $this->add_param ('_method', $this->get_method ())
         ->add_js (resource_url ('resource', 'javascript', 'jquery.validate_v1.9.0', 'jquery.validate.min.js'))
         ->add_js (resource_url ('resource', 'javascript', 'jquery.validate_v1.9.0', 'jquery.validate.lang.js'))
         ->load_view (array (
            'posts' => $posts
          ));
  }
  public function create () {
    if (!$this->has_post ())
      return redirect_message (array ($this->get_class ()), array (
          '_flash_message' => '非 POST 方法，錯誤的頁面請求。'
        ));

    $posts = OAInput::post ();

    if ($msg = $this->_validation_posts ($posts))
      return redirect_message (array ($this->get_class ()), array (
          '_flash_message' => $msg,
          'posts' => $posts
        ));

    $create = Contact::transaction (function () use ($posts) {
      return verifyCreateOrm ($contact = Contact::create (array_intersect_key ($posts, Contact::table ()->columns)));
    });

    if (!$create)
      return redirect_message (array ($this->get_class ()), array (
          '_flash_message' => '新增失敗，系統可能在維修，請稍候再嘗試一次！',
          'posts' => $posts
        ));
    return redirect_message (array ($this->get_class ()), array (
        '_flash_message' => '新增成功，已經收到您的建議，我們會儘快回覆您！'
      ));
  }
  private function _validation_posts (&$posts) {
    if (!(isset ($posts['name']) && ($posts['name'] = trim ($posts['name']))))
      return '沒有填寫稱呼！';

    if (!(isset ($posts['email']) && ($posts['email'] = trim ($posts['email']))))
      return '沒有填寫E-Mail！';

    if (!(isset ($posts['message']) && ($posts['message'] = trim ($posts['message']))))
      return '沒有填寫建議或意見！';

    $posts['ip'] = $this->input->ip_address ();

    return '';
  }
}
