var manageTable;

$(document).ready(function() {

    $('#item_type').on('change', function() {
        GenerateUnitPriceTextBox();
    });

    $('#edit_item_type').on('change', function() {
        EditGenerateUnitPriceTextBox();
    });


    manageTable = $('#manageTable').DataTable({
        'ajax': 'fetchItemData',
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

function editItem(id) {
    $.ajax({
        url: 'fetchItemDataById/' + id,
        type: 'post',
        dataType: 'json',
        success: function(response) {

            $("#edit_item_name").val(response.vcItemName);
            $('#edit_measure_unit').select2().select2('val', response.intMeasureUnitID);
            $('#edit_item_type').select2().select2('val', response.intItemTypeID);
            $("#edit_re_order").val(response.decReOrderLevel);
            $("#edit_rv").val(response.rv);
            if (response.intItemTypeID) {
                EditGenerateUnitPriceTextBox();
            }
            $('#edit_unit_price').val(response.decUnitPrice);

            // submit the edit from 
            $("#updateItemForm").unbind('submit').bind('submit', function() {
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
                            $("#editItemModal").modal('hide');
                            $("#updateItemForm")[0].reset();
                            $("#updateItemForm .form-group").removeClass('has-error').removeClass('has-success');

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
                                $("#editItemModal").modal('hide');
                                $("#updateItemForm")[0].reset();
                                $("#updateItemForm .form-group").removeClass('has-error').removeClass('has-success');
                            }
                        }
                    }
                });

                return false;
            });

        }
    });
}

function GenerateUnitPriceTextBox() {
    var Item_Type = $('#item_type').val();

    var htmlElements = "";

    if (Item_Type == 2) {
        htmlElements = '<div class="form-group">' +
            '							<label for="txtItemName">Unit Price</label>' +
            '							<input type="number" class="form-control" id="unit_price" name="unit_price" placeholder="Enter Unit Price" min=1 step="any">' +
            '						</div>';
    } else {
        htmlElements = "";

    }

    $("#GenerateUnitPriceTextBox").html("");
    $("#GenerateUnitPriceTextBox").append(htmlElements);

}

function EditGenerateUnitPriceTextBox() {
    var Item_Type = $('#edit_item_type').val();

    var htmlElements = "";

    if (Item_Type == 2) {
        htmlElements = '<div class="form-group">' +
            '							<label for="txtItemName">Unit Price</label>' +
            '							<input type="number" class="form-control" id="edit_unit_price" name="edit_unit_price" placeholder="Enter Unit Price" min=1 step="any">' +
            '						</div>';
    } else {
        htmlElements = "";

    }

    $("#EditGenerateUnitPriceTextBox").html("");
    $("#EditGenerateUnitPriceTextBox").append(htmlElements);

}

function removeItem(id) {
    if (id) {

        // submit the edit from 
        $("#removeItemForm").unbind('submit').bind('submit', function() {
            var form = $(this);

            // remove the text-danger
            $(".text-danger").remove();


            $.ajax({
                url: form.attr('action'),
                type: form.attr('method'),
                data: {
                    intItemID: id
                },
                dataType: 'json',
                success: function(response) {

                    manageTable.ajax.reload(null, false);

                    if (response.success === true) {


                        toastr["success"](response.messages);

                        // hide the modal
                        $("#removeItemModal").modal('hide');
                        $("#removeItemForm")[0].reset();
                        $("#removeItemForm .form-group").removeClass('has-error').removeClass('has-success');

                    } else {


                        toastr["error"](response.messages);

                        // hide the modal
                        $("#removeItemModal").modal('hide');
                        $("#removeItemForm")[0].reset();
                        $("#removeItemForm .form-group").removeClass('has-error').removeClass('has-success');
                    }
                }
            });

            return false;
        });
    }
}