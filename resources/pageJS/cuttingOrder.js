$(document).ready(function() {


    // on first focus (bubbles up to document), open the menu
    $(document).on('keyup', 'input[type=search]', function(e) {
        $("li").attr('aria-selected', false);
    });

    $("#btnAddToGrid").click(function() {
        AddToGrid();
    });
    //Bind keypress event to textbox
    $('.add-item').keypress(function(event) {
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if (keycode == '13') {
            // getRequestFinishedByItemID();
            AddToGrid();
        }

        event.stopPropagation();
    });

    function CalculateItemCount() {
        var rowCount = $('#itemTable tr').length;
        $("#itemCount").text("Item Count : " + (rowCount - 2));
    }

    remove();

    var row_id = 1;

    function AddToGrid() {



        if ($("input[name=txtOrderDescription]").val() == "", $("input[name=txtQty]").val() == "") {
            toastr["error"]("Please fill in all fields !");
        } else {

            var orderDescription = $("#txtOrderDescription").val();
            var qty = $("input[name=txtQty]").val();

            var isAlreadyAdded = false;

            $('#itemTable tbody tr').each(function() {
                var value = $(this).closest("tr").find('.orderDescription').val();
                if (value == orderDescription) {
                    isAlreadyAdded = true;
                }
            });

            if (isAlreadyAdded) {
                toastr["error"]("Already entered this item !");

            } else {
                $(".first-tr").after('<tr>' +
                    '<td>' +
                    '<input type="text" class="form-control orderDescription disable-typing" name="description[]" id="description_' + row_id + '" value="' + orderDescription + '" readonly>' +
                    '</td>' +
                    '<td>' +
                    '<input type="text" class="form-control disable-typing" style="text-align:right;" name="qty[]" id="qty_' + row_id + '"  value="' + qty + '" readonly>' +
                    '</td>' +
                    '<td class="static">' +
                    '<span class="button red center-items"><i class="fas fa-times"></i></span>' +
                    '</td>' +
                    '</tr>');

                row_id++;
                remove();
                // $("#cmbItem :selected").remove();

                $("input[name=txtOrderDescription], input[name=txtQty]").val("");
                CalculateItemCount();

                $("li").attr('aria-selected', false);
                $("#txtOrderDescription").focus();
            }



        }
    }

    function remove() {
        $(".red").click(function() {

            $(this).closest("tr").remove();

            CalculateItemCount();
        });
    }

    $('#btnSubmit').click(function() {

        if ($("input[name=cutting_order_name]").val() == "") {
            toastr["error"]("Please enter the cutting order name !");
            $("#cutting_order_name").focus();
        } else if ($('#itemTable tr').length == 2) {
            toastr["error"]("Please enter the Description !");
            $("#txtOrderDescription").focus();
        } else {
            arcadiaConfirmAlert("You want to be able to save this !", function(button) {

                var form = $("#createCuttingOrder");

                $.ajax({
                    type: form.attr('method'),
                    url: form.attr('action'),
                    data: form.serialize(),
                    dataType: 'json',
                    async: true,
                    success: function(response) {
                        if (response.success == true) {
                            arcadiaSuccessMessage("Saved !");
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

    $("#createCuttingOrder").unbind('submit').on('submit', function(e) {});

});

// on first focus (bubbles up to document), open the menu
$(document).on('focus', '.select2-selection.select2-selection--single', function(e) {
    $(this).closest(".select2-container").siblings('select:enabled').select2('open');
});