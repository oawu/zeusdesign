<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Contacts extends Site_controller {

  public function index () {
    $posts = Session::getData ('posts', true);

    $this->set_title ('聯絡我們' . ' - ' . Cfg::setting ('site', 'site', 'title'))
         ->add_param ('_method', $this->get_class ())
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

    $contact = null;
    $create = Contact::transaction (function () use ($posts, &$contact) {
      return verifyCreateOrm ($contact = Contact::create (array_intersect_key ($posts, Contact::table ()->columns)));
    });

    if (!($create && $contact))
      return redirect_message (array ($this->get_class ()), array (
          '_flash_message' => '新增失敗，系統可能在維修，請稍候再嘗試一次！',
          'posts' => $posts
        ));

    $this->_mail ($contact);
    return redirect_message (array ($this->get_class ()), array (
        '_flash_message' => '新增成功，已經收到您的建議，我們會儘快回覆您！'
      ));
  }
  private function _mail ($contact) {
    if (!($contact && ($roles = Cfg::setting ('role', 'admins')) && ($admins = User::find ('all', array ('select' => 'name, email', 'conditions' => array ('role IN (?)', $roles))))))
      return false;
    
    $this->load->library ('OAMail');

    $email = $contact->email;
    $name = $contact->name;
    $message = $contact->message;
    $mail = OAMail::create ()->setSubject ('[系統通知] 官網有新的留言！')
                             ->setBody ("<article style='font-size:15px;line-height:22px;color:rgb(85,85,85)'><p style='margin-bottom:0'>Hi 管理員,</p><section style='padding:5px 20px'><p>剛剛有一個訪客在您的「聯絡我們」留言囉，以下他所留下的聯絡資料：</p><table style='width:100%;border-collapse:collapse'><tbody><tr><th style='width:100px;text-align:right;padding:11px 5px 10px 0;border-bottom:1px dashed rgba(200,200,200,1)'>E-Mail：</th><td style='text-align:left;text-align:left;padding:11px 0 10px 5px;border-bottom:1px dashed rgba(200,200,200,1)'>" . $email . "</td></tr><tr><th style='width:100px;text-align:right;padding:11px 5px 10px 0;border-bottom:1px dashed rgba(200,200,200,1)'>稱 呼：</th><td style='text-align:left;text-align:left;padding:11px 0 10px 5px;border-bottom:1px dashed rgba(200,200,200,1)'>" . $name . "</td></tr><tr><th style='width:100px;text-align:right;padding:11px 5px 10px 0;border-bottom:1px dashed rgba(200,200,200,1)'>內容：</th><td style='text-align:left;text-align:left;padding:11px 0 10px 5px;border-bottom:1px dashed rgba(200,200,200,1)'>" . $message . "</td></tr></tbody></table><br/><p style='text-align:right'>如果需要詳細列表，可以置<a href='" . base_url ('admin','contacts') . "' style='color:rgba(96,156,255,1);margin:0 2px'>管理後台</a>檢閱。</p></section></article>");

    foreach ($admins as $admin)
      $mail->addTo ($admin->email, $admin->name);

    return $mail->send ();
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
