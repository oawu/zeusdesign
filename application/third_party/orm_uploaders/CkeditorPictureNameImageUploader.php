<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class CkeditorPictureNameImageUploader extends OrmImageUploader {

  public function getVersions () {
    return array (
        '' => array (),
        '50h' => array ('resize', 50, 50, 'height'),
        '400h' => array ('resize', 400, 400, 'height'),
      );
  }
}