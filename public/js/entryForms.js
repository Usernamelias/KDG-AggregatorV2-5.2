$(document).ready(function(){
    //$('.timepicker').mdtimepicker();

    $('.writeInTask').click(function() {
      var $parent = $(this).closest('form');
      $parent.find('.taskSelectField').hide();
      $parent.find('.writeInTask').hide();
      $parent.find('.taskTextField').show();
      $parent.find('.selectTask').show();
      $parent.find(".taskTextField input").focus();
    });

    $('.selectTask').click(function() {
      var $parent = $(this).closest('form');
      $parent.find('.taskSelectField').show();
      $parent.find('.taskTextField').hide();
      $parent.find('.selectTask').hide();
      $parent.find('.writeInTask').show();
    });

    $('.enterDuration').click(function() {
      var $parent = $(this).closest('form');
      $parent.find('.durationTextField').show();
      $parent.find('.startEndTime').hide();
      $parent.find('.enterDuration').hide();
      $parent.find('.enterStartEndTime').show();
      $parent.find('.clearTimes').hide();
      $parent.find(".duration").focus();
    });

    $('.enterStartEndTime').click(function() {
      var $parent = $(this).closest('form');
      $parent.find('.durationTextField').hide();
      $parent.find('.startEndTime').show();
      $parent.find('.enterDuration').show();
      $parent.find('.enterStartEndTime').hide();
      $parent.find('.clearTimes').show();
    });
  });