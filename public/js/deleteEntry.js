$(function() {
    $('#deleteModal').on("show.bs.modal", function (e) {
         $("input[name=id]").val($(e.relatedTarget).data('delete-id'));
    });
});
