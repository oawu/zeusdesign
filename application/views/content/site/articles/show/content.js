/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

$(function () {
  window.addPv ('Article', $('#id').val ());

  $('.b1 article section a').each (function () {
    $(this).attr ('target', '_blank');
  });

  $('time').timeago ();

  var tagIds = $.makeArray ($('.b1 ul.t a').map (function () {
      return $(this).data ('id');
    }));
  $('aside.f ul a').each (function () {
    $(this).addClass ($.inArray ($(this).data ('id'), tagIds) > -1 ? 'a' : null);
  });
  
  var $a = $('.b1 article header h1 a');
  $('aside:not(.f) ul a').each (function () {
    $(this).addClass ($(this).data ('id') == $a.data ('id') ? 'a' : null);
  });
});