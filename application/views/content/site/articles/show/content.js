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
});