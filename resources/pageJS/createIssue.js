$(document).ready(function () {


    $('#cmbcustomer').on('select2:select', function (e) {
        getDetailByCustomerID();
    });

    $('#cmbItem').on('select2:select', function (e) {
        $('#txtQty').focus();
    });

    $('#txtQty,#txtUnitPrice').keyup(function (event) {
        CalculateTotal();
    });

    $('#txtDiscount').keyup(function (event) {
        CalculateGrandTotal();
    });

    $('.add-item').keypress(function(event) {
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if (keycode == '13') {
            // getRequestFinishedByItemID();
            if ($("#cmbcustomer option:selected").val() == 0) {
                toastr["error"]("Please select customer !");
                return;
            }
            if ($("input[name=txtStockQty]").val() == "N/A" || $("input[name=txtStockQty]").val() == "0.00") {
                toastr["error"]("Please can't Add Stock Qty N/A");
                return;
            }
            AddToGrid();
        }

        event.stopPropagation();
    });
    // $('input[type=radio][name=paymentmode]').change(function () {
    // if (document.getElementById('credit').checked) {
    //     if ($("#cmbcustomer option:selected").val() == 0) {
    //         toastr["error"]("Please select customer !"); 
    //         return;
    //     }
    //     if (chkCreditLimit() == true) {
    //         alert("can Check");
    //     }
    //     else {
    //         alert("block");
    //     }
    // }
    // });


    function CalculateTotal() {
        // getMeasureUnitByItemID();
        var unitPrice = $("#txtUnitPrice").val();
        var qty = $("#txtQty").val()

        if (unitPrice != "" && qty != "") {
            var total = unitPrice * qty;
            $("#txtTotalPrice").val(currencyFormat(total));
        }
    }


    $('#txtQty').on('keyup', function (e) {
        if ($('#txtStockQty').val() == 0) {
            $('#txtQty').val(null);
            toastr["error"]("You can't Issue this. Because this item stock quantity is zero!");
        } else if ($('#txtStockQty').val() > 0) {
            if (parseFloat($('#txtQty').val()) > parseFloat($('#txtStockQty').val())) {
                toastr["error"]("You can't exceed stock quantity  !");
            }
        }

    });

    function remove() {
        $(".red").click(function () {
            // var itemID = $(this).closest("tr").find('td.itemID').text();
            // var itemName = $(this).closest("tr").find('td.itemName').text();

            var itemID = $(this).closest("tr").find('.itemID').val();
            var itemName = $(this).closest("tr").find('.itemName').val();

            var IsAlreadyIncluded = false;

            $("#cmbItem option").each(function () {
                if (itemID == $(this).val()) {
                    IsAlreadyIncluded = true;
                    return false;
                }
            });

            if (!IsAlreadyIncluded) {
                var cmbItem = $('#cmbItem');
                cmbItem.append(
                    $('<option></option>').val(itemID).html(itemName)
                );
                $(this).closest("tr").remove();
            }
            CalculateItemCount();
            CalculateGrandTotal();
        });
    }

    remove();

    $("#btnAddToGrid").click(function () {

        if ($("#cmbcustomer option:selected").val() == 0) {
            toastr["error"]("Please select customer !");
            return;
        }
        if ($("input[name=txtStockQty]").val() == "N/A" || $("input[name=txtStockQty]").val() == "0.00") {
            toastr["error"]("Please can't Add Stock Qty N/A");
            return;
        }
        if (document.getElementById('credit').checked) {
            debugger;
            if (chkCreditLimit() == true) {
                AddToGrid(true);
            }
            else {

                toastr["error"]("Please Check CreditLimit Exceed");
            }

        }
        else {
            AddToGrid(true);
        }

    });


    var row_id = 1;

    function AddToGrid(IsMouseClick = false) {
        debugger;
        if ($("#cmbcustomer option:selected").val() == 0) {
            toastr["error"]("Please select customer !");
            return;
        }
        else {
            if ($("#txtQty").val() > 0) {
    
                if ($('#txtStockQty').val() == 0) {
                    if (IsMouseClick) {
                        $('#txtQty').val(null);
                        toastr["error"]("You can't dispatch this. Because this item stock quantity is zero!");
                    }
                } else if (parseFloat($('#txtQty').val()) > parseFloat($('#txtStockQty').val())) {
                    if (IsMouseClick) {
                        toastr["error"]("You can't exceed stock quantity  !");
                    }
                } else {
                    if ($("#cmbItem option:selected").val() > 0) {
                        var itemID = $("#cmbItem option:selected").val();
                        var item = $("#cmbItem option:selected").text();
                        var measureUnit = $("input[name=txtMeasureUnit]").val();
                        var stockQty = $("input[name=txtStockQty]").val();
                        var unitPrice = $("input[name=txtUnitPrice]").val();
                        var qty = $("input[name=txtQty]").val();
                        var Rv = $("input[name=txtRv]").val();
                        var total = unitPrice * qty;
    
                        $(".first-tr").after('<tr>' +
                            '<td hidden>' +
                            '<input type="text" class="form-control itemID disable-typing" name="itemID[]" id="itemID_' + row_id + '" value="' + itemID + '" readonly>' +
                            '</td>' +
                            '<td>' +
                            '<input type="text" class="form-control itemName disable-typing" name="itemName[]" id="itemName_' + row_id + '" value="' + item + '" readonly>' +
                            '</td>' +
                            '<td>' +
                            '<input type="text" class="form-control disable-typing" style="text-align:right;" name="unitPrice[]" id="unitPrice_' + row_id + '" value="' + parseFloat(unitPrice).toFixed(2) + '" readonly>' +
                            '</td>' +
                            '<td>' +
                            '   <input type="text" class="form-control disable-typing" style="text-align:center;" name="stockQty[]" id="stockQty_' + row_id + '"  value="' + stockQty + '" readonly>' +
                            '</td>' +
                            '<td>' +
                            '   <input type="text" class="form-control disable-typing" style="text-align:center;" name="unit[]" id="unit_' + row_id + '"  value="' + measureUnit + '" readonly>' +
                            '</td>' +
                            '<td>' +
                            '<input type="text" class="form-control disable-typing" style="text-align:right;" name="itemQty[]" id="itemQty_' + row_id + '"  value="' + qty + '" readonly>' +
                            '</td>' +
                            '<td>' +
                            '<input type="text" class="form-control total disable-typing" style="text-align:right;" name="totalPrice[]" id="totalPrice_' + row_id + '"  value="' + parseFloat(total).toFixed(2) + '" readonly>' +
                            '</td>' +
                            '<td hidden>' +
                            '<input type="text" style="cursor: pointer;" class="form-control Rv disable-typing" name="Rv[]" id="Rv_' + row_id + '" value="' + Rv + '" readonly>' +
                            '</td>' +
                            '<td class="static">' +
                            '<span class="button red center-items"><i class="fas fa-times"></i></span>' +
                            '</td>' +
                            '</tr>');
    
                        row_id++;
                        remove();
                        $("#cmbItem :selected").remove();
    
                        $("input[name=cmbItem], input[name=txtMeasureUnit],input[name=txtUnitPrice], input[name=txtQty],input[name=txtStockQty]").val("");
                        $("input[name=txtTotalPrice]").val("0.00");
                        CalculateItemCount();
                        CalculateGrandTotal();
                        $("#cmbItem").focus();
                        $("li").attr('aria-selected', false);
    
                    } else {
                        toastr["error"]("Please select valid item !");
                        $("#cmbItem").focus();
                        $("li").attr('aria-selected', false);
                    }
                }
            }
        }
    }
    

    function CalculateGrandTotal() {
        if ($('#itemTable tr').length > 2) { // Because table header and item add row in here
            var discount = $("#txtDiscount").val();
            var total = 0;
            $('#itemTable tbody tr').each(function () {
                var value = parseInt($(this).closest("tr").find('.total').val());
                if (!isNaN(value)) {
                    total += value;
                }
            });

            discount == "" ? discount = 0 : discount;

            $("#subTotal").val(currencyFormat(total));
            $("#grandTotal").val(currencyFormat(total - discount));

        } else {
            debugger;
            $("#subTotal").val("0.00");
            $("#txtDiscount").val("0.00");
            $("#grandTotal").val("0.00");
        }
    }
});

function getDetailByCustomerID() {
    var customerID = $("#cmbcustomer").val();
    if (customerID > 0) {
        $.ajax({
            async: false,
            url: base_url + 'customer/fetchCustomerDataById/' + customerID,
            type: 'post',
            dataType: 'json',
            success: function (response) {
                $("#credit_limit").val(response.decCreditLimit);
                $("#available_limit").val(response.decCreditLimit);

                if (response.decCreditLimit < $("#grandTotal").val()) {
                    toastr["error"]("Please Check CreditLimit Exceed");
                }

            },
            error: function (xhr, status, error) {
                //var err = eval("(" + xhr.responseText + ")");
                alert(xhr.responseText);
            }
        });
    }
}

function getMeasureUnitByItemID() {

    var ItemID = $("#cmbItem").val();
    if (ItemID > 0) {
        $.ajax({
            url: base_url + 'item/fetchItemDataById/' + ItemID,
            type: 'post',
            dataType: 'json',
            success: function (response) {
                $("#txtUnitPrice").val(response.decUnitPrice);
                $("#txtStockQty").val(response.decStockInHand);
                $("#txtMeasureUnit").val(response.vcMeasureUnit);
                $("#txtRv").val(response.rv);

                if (response.decStockInHand == 'N/A') {
                    toastr["error"]("Please Check Stock");
                }
            },
            error: function (xhr, status, error) {
                //var err = eval("(" + xhr.responseText + ")");
                arcadiaErrorMessage(error);
            }
        });
    }
}

function chkCreditLimit() {
    var canAdd = false;
    debugger;
    if ($("#grandTotal").val("0.00")) {
        var Total = parseFloat($("#txtTotalPrice").val().replace(/,/g, ''));
    }
    else {
        var Total = $("#grandTotal").val()
    }

    var customerID = $("#cmbcustomer").val();

    if (customerID > 0) {

        $.ajax({
            async: false,
            url: base_url + 'customer/fetchCustomerDataById/' + customerID,
            type: 'post',
            dataType: 'json',
            success: function (response) {
                if (response.decCreditLimit < Total) {
                    canAdd = false;
                }
                else {
                    canAdd = true;
                }
            },
            error: function (xhr, status, error) {
                //var err = eval("(" + xhr.responseText + ")");
                alert(xhr.responseText);
            }
        });
    }
    return (canAdd == true) ? true : false;
}


function CalculateItemCount() {
    var rowCount = $('#itemTable tr').length;
    $("#itemCount").text("Item Count : " + (rowCount - 2));
}

$('#btnSubmit').click(function () {
    if ($("#cmbcustomer option:selected").val() == 0) {
        toastr["error"]("Please select customer !");
        $("#cmbcustomer").focus();
        return;
    }
    if ($('#itemTable tr').length == 2) {
        toastr["error"]("Please add the issue items !");
        $("#cmbItem").focus();
    } else {
        arcadiaConfirmAlert("You want to be able to create this !", function (button) {
            var form = $("#createIssue");

            $.ajax({
                async: false,
                type: form.attr('method'),
                url: form.attr('action'),
                data: form.serialize(),
                dataType: 'json',
                success: function (response) {
                    if (response.success == true) {
                        arcadiaSuccessMessagePrint("Issue No : "+ response.vcIssueNo, response.intIssueHeaderID);
                        // $('#printpage', window.parent.document).hide();
                    } else {
                        toastr["error"](response.messages);
                    }

                }
            });
        }, this);
    }

});


// on first focus (bubbles up to document), open the menu
$(document).on('focus', '.select2-selection.select2-selection--single', function (e) {
    $(this).closest(".select2-container").siblings('select:enabled').select2('open');
});