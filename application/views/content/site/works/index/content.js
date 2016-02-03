/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

$(function () {
  $('.b1 article figure a').imgLiquid ({verticalAlign: 'center'});

  $('aside.f ul a').each (function () {
    $(this).addClass ($.inArray ($(this).data ('id'), tagIds) > -1 ? 'a' : null);
  });

  var $a = $('.b1 article h1 a');
  $('.b1 aside a').each (function () {
    $(this).addClass ($(this).data ('id') == $a.data ('id') ? 'a' : null);
  });
  
});