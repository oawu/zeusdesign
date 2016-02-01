/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

$(function () {
  window.addPv ('Work', $('#id').val ());
  var masonry = new Masonry ($('.b1 article:first-child div').clone ().appendTo ('.b1 article:last-child').get (0), { itemSelector: 'section', columnWidth: 1, transitionDuration: '0.3s', visibleStyle: { opacity: 1, transform: 'none' }});
  setTimeout (function () {
    masonry.layout ();
  }, 300);
});