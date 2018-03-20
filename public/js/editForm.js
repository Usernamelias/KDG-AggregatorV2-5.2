$(document).ready(function() {

    $('[data-edit]').on("show.bs.modal", function (e) {
        
        $("input[name=edit_id]").val($(e.relatedTarget).data('edit-id'));
        $("textarea[name=description2]").val($(e.relatedTarget).data('textarea'));
        $("select[name=project_name2]").val($(e.relatedTarget).data('project-name'));
        
        $("input[name=start_time2]").val("");
        $("input[name=end_time2]").val("");
        $("input[name=duration2]").val("");
        // $('[data-edit]').closest('[data-start-time]').attr('data-start-time');

        if($(e.relatedTarget).data('start-time') !== ""){
            $("input[name=start_time2]").val($(e.relatedTarget).data('start-time'));
            $("input[name=end_time2]").val($(e.relatedTarget).data('end-time'));
        }else{
            $("input[name=duration2]").val($(e.relatedTarget).data('duration'));
        }

        $("input[id=billable2]").val(1);
        $("input[id=nonbillable2]").val(0);

        $('input[name="billable2"][value='+$(e.relatedTarget).data('billable')+']').prop('checked',true);
  
        if($("input[name=start_time2]").val() == "" && $(e.relatedTarget).data('edit-id') == $("input[name=edit_id]").val()){
            $('#durationTextField').show();
            $('#startEndTime').hide();
            $('#enterDuration').hide();
            $('#enterStartEndTime').show();
            $('#clearTimes2').hide();
        }else{
            $('#durationTextField').hide();
            $('#startEndTime').show();
            $('#enterDuration').show();
            $('#enterStartEndTime').hide();
            $('#clearTimes2').show();
        }
        $(".combobox").trigger('change');
        $("select[name=task2]").val($(e.relatedTarget).data('task'));

        $(".clearTimes").click(function() {
            $(this).closest('form').find("input[name=start_time2]").val("");
            $(this).closest('form').find("input[name=end_time2]").val("");
        });

        $('div').removeClass('has-error');
        $('.error-list').hide();
    });
});

