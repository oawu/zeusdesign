/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

$(function () {
  $('time').timeago ();
  $('figure a').imgLiquid ({verticalAlign: 'center'});

  var tagIds = $.makeArray ($('.b1 section h1 a').map (function () {
      return $(this).data ('id');
    }));
  $('aside.f ul a').each (function () {
    $(this).addClass ($.inArray ($(this).data ('id'), tagIds) > -1 ? 'a' : null);
  });
});