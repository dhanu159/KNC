var manageTable;

$(document).ready(function() {

    manageTable = $('#manageTable').DataTable({
        'ajax': 'fetchItemtData',
        'order': []
    });


    // submit the create from 
    $("#createitemForm").unbind('submit').on('submit', function() {
        var form = $(this);

        // remove the text-danger
        $(".text-danger").remove();

        $.ajax({
            url: form.attr('action'),
            type: form.attr('method'),
            data: form.serialize(), // /converting the form data into array and sending it to server
            dataType: 'json',
            success: function(response) {

                manageTable.ajax.reload(null, false);

                if (response.success === true) {

                    toastr["success"](response.messages);

                    // hide the modal
                    $("#addItemModal").modal('hide');

                    // reset the form
                    $("#createitemForm")[0].reset();
                    $("#createitemForm .form-group").removeClass('has-error').removeClass('has-success');

                } else {

                    if (response.messages instanceof Object) {
                        $.each(response.messages, function(index, value) {
                            var id = $("#" + index);

                            id.closest('.form-group')
                                .removeClass('has-error')
                                .removeClass('has-success')
                                .addClass(value.length > 0 ? 'has-error' : 'has-success');

                            id.after(value);

                        });
                    } else {
                        toastr["error"](response.messages);

                        // hide the modal
                        $("#addItemModal").modal('hide');
                        // reset the form
                        $("#createitemForm")[0].reset();
                        $("#createitemForm .form-group").removeClass('has-error').removeClass('has-success');
                    }
                }
            }
        });

        return false;
    });


});