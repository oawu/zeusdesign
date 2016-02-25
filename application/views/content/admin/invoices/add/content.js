/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
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
  });

  $('input[name="closing_at"]').datepicker({
    changeMonth: true,
    changeYear: true,
    firstDay: 0,
    dateFormat: 'yy-mm-dd',
    showOtherMonths: true,
    selectOtherMonths: true,
  });
  $('#is_finished').click (function () {
    $(this).nextAll ('label').text ($(this).prop ('checked') === true ? $(this).data ('is_finished_name') : $(this).data ('no_finished_name'));
  });
  
  var $all_money = $('#all_money');
  var $single_money = $('#single_money');

  var $quantity = $('#quantity').keyup (function () {
    if (isNaN ($(this).val ()) || isNaN ($single_money.val ()))
      return;

    $all_money.val ($(this).val () * $single_money.val ());
  });
  $single_money.keyup (function () {
    if (isNaN ($(this).val ()) || isNaN ($quantity.val ()))
      return;
    $all_money.val ($(this).val () * $quantity.val ());
  });
});