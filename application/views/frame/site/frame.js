/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

$(function () {
  $('.i_c').imgLiquid ({verticalAlign: 'center'});
  $('footer').click (function () {
    $('html, body').animate ({ scrollTop: 0 }, 'slow');
  });
});