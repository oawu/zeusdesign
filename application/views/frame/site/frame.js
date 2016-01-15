/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

$(function () {
  window.$body = $('html, body');
  $('.i_c').imgLiquid ({verticalAlign: 'center'});
  $('footer > a').click (function () {
    window.$body.animate ({ scrollTop: 0 }, 'slow');
  });
});