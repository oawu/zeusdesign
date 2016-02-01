/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

$(function () {
  $('.b1 article section a').each (function () {
    $(this).attr ('target', '_blank');
  });

  $('time').timeago ();
});