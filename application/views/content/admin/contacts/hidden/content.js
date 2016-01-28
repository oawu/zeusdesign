/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

$(function () {
  function updateIsReplied (id, status, callback) {
    if ($('#is_replied_url').val ())
      $.ajax ({
        url: $('#is_replied_url').val () + '/' + id,
        data: {
          is_replied: status ? 1 : 0,
        },
        async: true, cache: false, dataType: 'json', type: 'post',
        beforeSend: function () { }
      })
      .done (callback ? callback : function (result) { })
      .fail (function (result) { ajaxError (result); })
      .complete (function (result) { });
  }

  $('.timeago').timeago ();

  $('.checkbox input').change (function () {
    $(this).prop ('disabled', true).nextAll ('div').text ('設定中');

    updateIsReplied (
      $(this).data ('id'),
      $(this).prop ('checked') === true,
      function (result) { $(this).prop ('disabled', false); if (result.content) $(this).nextAll ('div').text (result.content);
    }.bind ($(this)));
  });
});