<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class PokemonPicImageUploader extends OrmImageUploader {

  public function getVersions () {
    return array (
        '' => array (),
        '500h' => array ('resize', 500, 500, 'height'),
      );
  }
}