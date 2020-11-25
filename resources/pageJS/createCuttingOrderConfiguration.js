$(document).ready(function() {



    // $('#itemTable tbody tr').each(function() {
    //     var value = $(this).closest("tr").find('.itemID').val();
    //     $("#cmbItem option[value=" + value + "]").remove();
    // });

    // on first focus (bubbles up to document), open the menu
    $(document).on('keyup', 'input[type=search]', function(e) {
        $("li").attr('aria-selected', false);
    });


    $('#cmbItem').on('select2:select', function(e) {
        fetchCuttingConfigData();
        // findAlreadItemAdded();
        // $('#cmbCuttingOrder').focus();

    });

    $("#btnAddToGrid").click(function() {
        AddToGrid();
    });
    //Bind keypress event to textbox
    $('.add-item').keypress(function(event) {
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if (keycode == '13') {
            $('#btnAddToGrid').focus();
            AddToGrid();
        }

        event.stopPropagation();
    });



    function cmbItemDisable() {
        var rowCount = $('#itemTable tr').length;
        if (rowCount > 1) {
            document.getElementById("cmbItem").disabled = true;
        }
        // if (rowCount == 1) {
        //     document.getElementById("cmbItem").disabled = false;
        // }
    }

    function cmbItemEnable() {
        var rowCount = $('#itemTable tr').length;
        if (rowCount == 1) {
            document.getElementById("cmbItem").disabled = false;
        }
    }


    remove();


    var row_id = 1;

    function AddToGrid() {
        cmbItemDisable();
        if ($('#cmbItem').val() == null) {
            toastr["error"]("Please select a Item !");
            $("#cmbItem").focus();
        } else if ($("input[name=cmbCuttingOrder]").val()) {
            toastr["error"]("Please select a Cutting Order !");
        } else {
            if ($("#cmbCuttingOrder option:selected").val() > 0) {
                var itemID = $("#cmbItem option:selected").val();
                var cuttingorderID = $("#cmbCuttingOrder option:selected").val();
                var cuttingorder = $("#cmbCuttingOrder option:selected").text();


                $(".first-tr").after('<tr>' +
                    '<td hidden>' +
                    '<input type="text" class="form-control itemID disable-typing" name="itemID[]" id="itemID_' + row_id + '" value="' + itemID + '" readonly>' +
                    '</td>' +
                    '<td hidden>' +
                    '<input type="text" class="form-control cuttingorderID disable-typing" name="cuttingorderID[]" id="cuttingorderID_' + row_id + '" value="' + cuttingorderID + '" readonly>' +
                    '</td>' +
                    '<td>' +
                    '<input type="text" class="form-control cuttingorder disable-typing" name="cuttingorder[]" id="cuttingorder_' + row_id + '" value="' + cuttingorder + '" readonly>' +
                    '</td>' +
                    '<td class="static">' +
                    '<span class="button red center-items"><i class="fas fa-times"></i></span>' +
                    '</td>' +
                    '</tr>');

                row_id++;
                remove();

                $("#cmbCuttingOrder :selected").remove();

                $("input[name=cmbCuttingOrder]").val("");
                CalculateItemCount();

                $("#cmbCuttingOrder").focus();
                $("li").attr('aria-selected', false);
                $("#cmbCuttingOrder").focus();


            } else {
                toastr["error"]("Please select valid item !");
                $("#cmbCuttingOrder").focus();
                $("li").attr('aria-selected', false);
            }
        }
    }




    $('#btnSubmit').click(function() {

        if ($('#itemTable tr').length == 2) {
            toastr["error"]("Please choose the receive items !");
            $("#cmbCuttingOrder").focus();
        } else {
            arcadiaConfirmAlert("You want to be able to save this !", function(button) {

                var form = $("#createCuttingOrderConfiguration");

                $.ajax({
                    type: form.attr('method'),
                    url: form.attr('action'),
                    data: form.serialize(),
                    dataType: 'json',
                    async: true,
                    success: function(response) {
                        if (response.success == true) {
                            arcadiaSuccessMessage(true);
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

                                arcadiaErrorMessage(response.messages);
                            }
                        }

                    }
                });
            }, this);
        }

    });

    $("#createCuttingOrderConfiguration").unbind('submit').on('submit', function(e) {});

});

function fetchCuttingConfigData() {
    debugger;
    var value = $("#cmbItem :selected").val();
    $.ajax({
        url: 'fetchCuttingConfigDataByItemID/' + value,
        type: 'post',
        dataType: 'json',
        success: function(response) {

            var html = '';
            var row_id = 1;
            for (var i = 0; i < response.length; i++) {
                debugger;
                html += '<tr>' +
                    '<td hidden>' +
                    '<input type="text" class="form-control cuttingorderID disable-typing" name="cuttingorderID[]" id="cuttingorderID_' + row_id + '" value="' + response[i].intCuttingOrderHeaderID + '" readonly>' +
                    '</td>' +
                    '<td>' +
                    '<input type="text" class="form-control cuttingorder disable-typing" name="cuttingorder[]" id="cuttingorder_' + row_id + '" value="' + response[i].vcOrderName + '" readonly>' +
                    '</td>' +
                    '<td class="static">' +
                    '<span class="button red center-items"><i class="fas fa-times"></i></span>' +
                    '</td>' +
                    '</tr>';
                row_id++;

                //remove();

            }
            // $('#itemTable .first-tr').first().after().empty();
            $("#itemTable").find("tr:gt(1)").remove();
            $('#itemTable .first-tr').first().after(html);
            // $("#GenerateUnitPriceTextBox").html("");
            // $("#GenerateUnitPriceTextBox").append(html);

            findAlreadItemAdded();
            remove();

        },
        error: function(data) {}


    });
}

function findAlreadItemAdded() {
    $('#itemTable tbody tr').each(function() {
        var value = $(this).closest("tr").find('.cuttingorderID').val();
        $("#cmbCuttingOrder option[value=" + value + "]").remove();
    });
}

function remove() {
    $(".red").click(function() {

        var cuttingorderID = $(this).closest("tr").find('.cuttingorderID').val();
        var cuttingorder = $(this).closest("tr").find('.cuttingorder').val();

        var IsAlreadyIncluded = false;

        $("#cmbCuttingOrder option").each(function() {
            if (cuttingorderID == $(this).val()) {
                IsAlreadyIncluded = true;
                return false;
            }
        });

        if (!IsAlreadyIncluded) {
            var cmbCuttingOrder = $('#cmbCuttingOrder');
            cmbCuttingOrder.append(
                $('<option></option>').val(cuttingorderID).html(cuttingorder)
            );
            $(this).closest("tr").remove();

        }
        CalculateItemCount();
        cmbItemEnable();
    });
}

function cmbItemDisable() {
    var rowCount = $('#itemTable tr').length;
    if (rowCount > 1) {
        document.getElementById("cmbItem").disabled = true;
    }
    // if (rowCount == 1) {
    //     document.getElementById("cmbItem").disabled = false;
    // }
}

function cmbItemEnable() {
    debugger;
    var rowCount = $('#itemTable tr').length;
    if (rowCount == 2) {
        document.getElementById("cmbItem").disabled = false;
    }
}

function CalculateItemCount() {
    var rowCount = $('#itemTable tr').length;
    $("#itemCount").text("Item Count : " + (rowCount - 2));
}

// on first focus (bubbles up to document), open the menu
$(document).on('focus', '.select2-selection.select2-selection--single', function(e) {
    $(this).closest(".select2-container").siblings('select:enabled').select2('open');
});