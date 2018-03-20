$(document).ready(function(){
  $(".clear-button").click(function() {
    $(this).closest('form').find("input[type=text], textarea, select").val("");
    $(this).closest('form').find('input[name=billable]').attr('checked', false);

    var radio = document.getElementsByName("billable");
    for(var i = 0; i < radio.length; i++)
      radio[i].checked = false;

      $('.search-select').val(null).trigger('change');
  });

  $(".clearTimes").click(function() {
    $(this).closest('form').find("input[name=start_time]").val("");
    $(this).closest('form').find("input[name=end_time]").val("");
  });
});