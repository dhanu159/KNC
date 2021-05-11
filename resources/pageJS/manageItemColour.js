var manageTable;

$(document).ready(function() {


    manageTable = $('#manageTable').DataTable({
        'ajax': 'fetchItemColourData',
        'order': []
    });


    // submit the create from 
    $("#createColourForm").unbind('submit').on('submit', function() {
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
                    $("#addColourModal").modal('hide');

                    // reset the form
                    $("#createColourForm")[0].reset();
                    $("#createColourForm .form-group").removeClass('has-error').removeClass('has-success');

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
                        toastr["warning"](response.messages);
                    }
                }
            }
        });

        return false;
    });


});

function editColour(id) {
    $.ajax({
        url: 'fetchColourDataById/' + id,
        type: 'post',
        dataType: 'json',
        success: function(response) {
            $("#edit_Colour_name").val("");
            $("#edit_Colour_name").val(response.vcColourName);

            // submit the edit from 
            $("#updateColourForm").unbind('submit').bind('submit', function() {
                var form = $(this);

  
                // remove the text-danger
                $(".text-danger").remove();

                $.ajax({
                    url: form.attr('action') + '/' + id,
                    type: form.attr('method'),
                    data: form.serialize(), // /converting the form data into array and sending it to server
                    dataType: 'json',
                    success: function(response) {

                        manageTable.ajax.reload(null, false);

                        if (response.success === true) {


                            toastr["success"](response.messages);


                            // hide the modal
                            $("#editColourModal").modal('hide');
                            $("#updateColourForm")[0].reset();
                            $("#updateColourForm .form-group").removeClass('has-error').removeClass('has-success');

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

                                toastr["warning"](response.messages);


                                // hide the modal
                                $("#editColourModal").modal('hide');
                                $("#updateColourForm")[0].reset();
                                $("#updateColourForm .form-group").removeClass('has-error').removeClass('has-success');
                            }
                        }
                    }
                });

                return false;
            });

        }
    });
}

function removeColour(id) {
    if (id) {

        // submit the edit from 
        $("#removeColourForm").unbind('submit').bind('submit', function() {
            var form = $(this);

            // remove the text-danger
            $(".text-danger").remove();


            $.ajax({
                url: form.attr('action'),
                type: form.attr('method'),
                data: {
                    intColourID: id
                },
                dataType: 'json',
                success: function(response) {

                    manageTable.ajax.reload(null, false);

                    if (response.success === true) {


                        toastr["success"](response.messages);


                        // hide the modal
                        $("#removeColourModal").modal('hide');
                        $("#removeColourForm")[0].reset();
                        $("#removeColourForm .form-group").removeClass('has-error').removeClass('has-success');

                    } else {


                        toastr["warning"](response.messages);

                        // hide the modal
                        $("#removeColourModal").modal('hide');
                        $("#removeColourForm")[0].reset();
                        $("#removeColourForm .form-group").removeClass('has-error').removeClass('has-success');
                    }
                }
            });

            return false;
        });
    }
}