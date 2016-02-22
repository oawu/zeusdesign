/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

$(function () {
  window.addPv ('Article', $('#id').val ());

  $('.b1 article section a').each (function () {
    $(this).attr ('target', '_blank');
  });
  $('.b1 article section img').each (function () {
    
    var src = $(this).attr ('src').replace ('/400h_', '/_');

    $(this).attr ('data-fancybox-group', 'fancybox_group')
           .attr ('href', src);
  }).fancybox ({
    padding: 0,
    helpers: {
      overlay: { locked: false },
      title: { type: 'over' },
      thumbs: { width: 50, height: 50 }
    }
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