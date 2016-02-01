/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

$(function () {
  window.addPv = function (className, pv) {
    $.ajax ({
        url: $('#ajax_pv_url').val (),
        data: {
          class: className, id: pv
        },
        async: true, cache: false, dataType: 'json', type: 'POST',
    });
  };

  window.$body = $('html, body');
  $('.i_c').imgLiquid ({verticalAlign: 'center'});
  $('footer > a').click (function () {
    window.$body.animate ({ scrollTop: 0 }, 'slow');
  });
});