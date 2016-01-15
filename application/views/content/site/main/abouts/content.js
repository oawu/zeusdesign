/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

$(function () {
  var $articles = $('.b1 section article');
  $('.b1 aside a').click (function () {
    window.$body.animate ({ scrollTop: $articles.eq ($(this).index ()).offset ().top }, 'slow');
  });
});