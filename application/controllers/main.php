<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Main extends Site_controller {

  public function x () {
    // foreach (Work::all () as $w)
    //   $w->cover->put_url ($w->cover->url ());

    $this->load->library ('OAMail');
    
    OAMail::create ()->addTo ('comdan66@gmail.com', 'OA')
                     ->setSubject ('11111')
                     ->setBody ('222222')
                     // ->addFile (FCPATH . 'temp/01.jpg', '3333333')
                     ->send ();

    // OAMail::sendMail (array (
    //     'mail' => 'comdan66@gmail.com',
    //     'name' => 'COMDAN66'
    //   ), 'xxxxxx', '222222', array ('oa_wu@hiiir.com'));
  }

  public function abouts () {
    $this->set_title ('關於宙思' . ' - ' . Cfg::setting ('site', 'site', 'title'))
         ->add_param ('_method', $this->get_method ())
         ->load_view ();
  }
  public function index () {
    $this->set_title (Cfg::setting ('site', 'site', 'title'))
         ->add_param ('_method', $this->get_method ())
         ->load_view ();
  }
}
