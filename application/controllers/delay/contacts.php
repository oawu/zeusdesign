<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */
class Contacts extends Delay_controller {

  public function mail () {
    if (!(($id = OAInput::post ('id')) && ($contact = Contact::find_by_id ($id, array ('select' => 'name, email, message')))))
      return false;

    if (!(($roles = Cfg::setting ('role', 'admins')) && ($admins = User::find ('all', array ('select' => 'name, email', 'conditions' => array ('role IN (?)', $roles))))))
      return false;
    
    $this->load->library ('OAMail');

    $email = $contact->email;
    $name = $contact->name;
    $message = $contact->message;
    $mail = OAMail::create ()->setSubject ('[系統通知] 官網有新的留言！')
                             ->setBody ("<article style='font-size:15px;line-height:22px;color:rgb(85,85,85)'><p style='margin-bottom:0'>Hi 管理員,</p><section style='padding:5px 20px'><p>剛剛有一個訪客在您的「聯絡我們」留言囉，以下他所留下的聯絡資料：</p><table style='width:100%;border-collapse:collapse'><tbody><tr><th style='width:100px;text-align:right;padding:11px 5px 10px 0;border-bottom:1px dashed rgba(200,200,200,1)'>E-Mail：</th><td style='text-align:left;text-align:left;padding:11px 0 10px 5px;border-bottom:1px dashed rgba(200,200,200,1)'>" . $email . "</td></tr><tr><th style='width:100px;text-align:right;padding:11px 5px 10px 0;border-bottom:1px dashed rgba(200,200,200,1)'>稱 呼：</th><td style='text-align:left;text-align:left;padding:11px 0 10px 5px;border-bottom:1px dashed rgba(200,200,200,1)'>" . $name . "</td></tr><tr><th style='width:100px;text-align:right;padding:11px 5px 10px 0;border-bottom:1px dashed rgba(200,200,200,1)'>內容：</th><td style='text-align:left;text-align:left;padding:11px 0 10px 5px;border-bottom:1px dashed rgba(200,200,200,1)'>" . $message . "</td></tr></tbody></table><br/><p style='text-align:right'>如果需要詳細列表，可以置<a href='" . base_url ('admin','contacts') . "' style='color:rgba(96,156,255,1);margin:0 2px'>管理後台</a>檢閱。</p></section></article>");

    foreach ($admins as $admin)
      $mail->addTo ($admin->email, $admin->name);
    $mail->send ();

    $mail = OAMail::create ()->setSubject ('[宙思設計] 留言成功通知！')
                             ->setBody ("<article style='font-size:15px;line-height:22px;color:rgb(85, 85, 85)'><p style='margin-bottom:0'>Hi " . $name . ",</p><section style='padding:5px 20px'><p>您好，我們是<a href='http://www.zeusdesign.com.tw/' style='color:rgba(96, 156, 255, 1);margin:0 2px'>宙思設計</a>團隊，我們已經收到您的留言囉。</p><p>我們稍後會有專人主動與您聯絡或回信給您！</p><p>若是尚未得到回覆，您可以至<a href='https://www.facebook.com/ZeusDesignStudio/' style='color:rgba(96, 156, 255, 1);margin:0 2px'>宙思設計臉書粉絲專頁</a>留言，或來電(02-2941-6737)聯絡我們。</p><p style='text-align:right'>- <a href='http://www.zeusdesign.com.tw/' style='color:rgba(96, 156, 255, 1);margin:0 2px'>宙思設計</a>團隊感謝您。</p></section></article>")
                             ->addTo ($email, $name);
    $mail->send ();
  }
}
