/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

$(function () {
  var $ps = $('.ps');

  var $psfm = function (i) {
    return $('<div />', {'class': 'p'}).append (
      $('<input />', {'type': 'file', 'name': 'pictures[' + i + ']'})
    ).append (
      $('<button />', {'type': 'button', 'class': 'icon-bin'}).click (function () { $(this).parents ('.p').remove (); })
    );
  };

  $ps.find ('.icon-plus').click (function () {
    var i = $(this).data ('i');
    $psfm (i).insertBefore ($ps);
    $(this).data ('i', i + 1);
  }).click ();

});