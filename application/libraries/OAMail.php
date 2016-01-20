<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

require_once FCPATH . 'application' . DIRECTORY_SEPARATOR . 'libraries' . DIRECTORY_SEPARATOR . 'PHPMailer-5.2.13' . DIRECTORY_SEPARATOR . 'class.phpmailer.php';
require_once FCPATH . 'application' . DIRECTORY_SEPARATOR . 'libraries' . DIRECTORY_SEPARATOR . 'PHPMailer-5.2.13' . DIRECTORY_SEPARATOR . 'class.smtp.php';

class OAMail extends PHPMailer {

  public function __construct () {
    parent::__construct ();

    $config = Cfg::setting ('mail');

    if (isset ($config['host']) && isset ($config['port']) && isset ($config['user']) && isset ($config['passwd']) && isset ($config['from']) && isset ($config['from_name']) && $config['host'] && $config['port'] && $config['user'] && $config['passwd'] && $config['from'] && $config['from_name']) {
      $this->isSMTP ();
      $this->SMTPAuth = true;
      $this->Host = $config['host'];
      $this->Port = $config['port'];
      $this->Username = $config['user'];
      $this->Password = $config['passwd'];
      $this->From = $config['from'];
      $this->FromName = $config['from_name'];
    }

    $this->CharSet = $config['charset'];
    $this->Encoding = $config['encoding'];
    $this->isHTML (true);
    $this->WordWrap = 50;

    if (!empty($config['secure'])) {
      $this->SMTPSecure = $config['secure'];
    }
  }

  public function addTo ($address, $name = '') {
    $this->addAddress ($address, $name);
    return $this;
  }
  public function addCC ($address, $name = '') {
    parent::addCC ($address, $name);
    return $this;
  }
  public function addBCC ($address, $name = '') {
    parent::addBCC ($address, $name);
    return $this;
  }
  public function addFile ($path, $name = '') {
    $this->addAttachment ($path, $name);
    return $this;
  }
  public function setSubject ($subject) {
    $this->Subject = $subject;
    return $this;
  }
  
  public function setBody ($body) {
    $this->Body = $body;
    return $this;
  }

  public function setFrom ($address, $name = '') {
    parent::setFrom ($address, $name);
    return $this;
  }

  public static function create () {
    return new self ();
  }
}