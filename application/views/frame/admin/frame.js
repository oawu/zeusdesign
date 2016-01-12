/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

$(function () {
  $('.timeago').timeago ();
  $('.imgLiquid_center').imgLiquid ({verticalAlign: 'center'});

  $('a.destroy, a[data-method="delete"]').click (function () {
    if (!confirm ('確定要刪除？'))
      return false;
    window.showLoading ();
  });
});