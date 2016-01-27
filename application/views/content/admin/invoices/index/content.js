/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

$(function () {
  function updateIsFinished (id, status, callback) {
    if ($('#is_finished_url').val ())
      $.ajax ({
        url: $('#is_finished_url').val () + '/' + id,
        data: {
          is_finished: status ? 1 : 0,
        },
        async: true, cache: false, dataType: 'json', type: 'post',
        beforeSend: function () { }
      })
      .done (callback ? callback : function (result) { })
      .fail (function (result) { ajaxError (result); })
      .complete (function (result) { });
  }

  var $start = $('input[type="text"][name="start"]');
  var $end = $('input[type="text"][name="end"]');

  $start.datepicker ({
    dateFormat: 'yy-mm-dd',
    yearRange: '1901:2030',
    timeFormat: 'HH:mm:ss',
    changeMonth: true, changeYear: true, firstDay: 0, stepHour: 1, stepMinute: 1, stepSecond: 1,
    // onClose: function (dateText, inst) { if ($end.val () !== '') { var testStartDate = $start.datepicker ('getDate'); var testEndDate = $end.datepicker ('getDate'); if (testStartDate > testEndDate) $end.datepicker ('setDate', testStartDate); } else { $end.val (dateText); } },
    onSelect: function (selectedDate, instance) { var date = $.datepicker.parseDate(instance.settings.dateFormat, selectedDate, instance.settings); date.setMonth (date.getMonth() + 6); $end.datepicker ('option', 'minDate', $start.datepicker ('getDate'));  $end.datepicker ('option', 'maxDate', date);  }
  });

  $end.datepicker ({
    dateFormat: 'yy-mm-dd',
    yearRange: '1901:2030',
    timeFormat: 'HH:mm:ss',
    changeMonth: true, changeYear: true, firstDay: 0, stepHour: 1, stepMinute: 1, stepSecond: 1,
    // onClose: function (dateText, inst) { if ($start.val () !== '') { var testStartDate = $start.datepicker ('getDate'); var testEndDate = $end.datepicker ('getDate'); if (testStartDate > testEndDate) $start.datepicker ('setDate', testEndDate); } else { $start.val (dateText); }},
    onSelect: function (selectedDate, instance) { var date = $.datepicker.parseDate(instance.settings.dateFormat, selectedDate, instance.settings); date.setMonth (date.getMonth() - 6); $start.datepicker ('option', 'maxDate', $end.datepicker ('getDate') ); $start.datepicker ('option', 'minDate', date ); }
  });

  $('#export').click (function () {
    $(this).parent ().attr ('action', $(this).attr ('href')).submit ();
    return false;
  });

  $('.search button').click (function () {
    $(this).parent ().attr ('action', $(this).attr ('href')).submit ();
  });

  $('.checkbox input').change (function () {
    $(this).prop ('disabled', true).nextAll ('div').text ('設定中');

    updateIsFinished (
      $(this).data ('id'),
      $(this).prop ('checked') === true,
      function (result) { $(this).prop ('disabled', false); if (result.content) $(this).nextAll ('div').text (result.content);
    }.bind ($(this)));
  });
});