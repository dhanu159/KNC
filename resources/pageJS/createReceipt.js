var totalAllocatedAmount = 0;
var totalPayAmount = 0;
var totalAvailableAmount = 0;

var Customer = function () {
    this.intCustomerID = 0;
}
var Issue = function () {
    this.intIssueHeaderID = 0;
}

var row_id = 1;



$(document).ready(function () {

    $("#cmbPayMode").prop('disabled', true);
    $("#txtAmount").prop('disabled', true);
    $("#txtAmount").css('background-color', '#eee');
    $("#cmbBank").prop('disabled', true);
    $("#txtChequeNo").prop('disabled', true);
    $("#txtChequeNo").css('background-color', '#eee');
    $("#PDDate").prop('disabled', true);
    $("#PDDate").css('background-color', '#eee');
    $("#txtRemark").prop('disabled', true);
    $("#txtRemark").css('background-color', '#eee');

    $('#cmbCustomer').on('select2:close', function (e) {
        ResetGrid();
        var model = new Customer();
        model.intCustomerID = $('#cmbCustomer').val();
        ajaxCall('Receipt/getCustomerToBeSettleIssueNos', model, function (response) {
            $("#cmbPayMode").prop('disabled', false);
            $("#txtAmount").prop('disabled', false);
            $("#txtAmount").css('background-color', '#FFFFFF');
            $("#txtRemark").prop('disabled', false);
            $("#txtRemark").css('background-color', '#FFFFFF');

            var issueTotal = 0;
            var paidTotal = 0;
            var issueNoHTML = '<option value="0" disabled selected hidden>Select Issue No</option>';

            for (let index = 0; index < response.length; index++) {
                issueNoHTML += '<option value="' + response[index].intIssueHeaderID + '">' + response[index].vcIssueNo + '</option>';
                issueTotal += parseFloat(response[index].decGrandTotal);
                paidTotal += parseFloat(response[index].decPaidAmount);
            }
            $("#txtTotalOutstanding").val(parseFloat(issueTotal - paidTotal).toFixed(2));
            $("#cmbIssueNo").empty();
            $("#cmbIssueNo").append(issueNoHTML);
        });
    });

    $('#cmbPayMode').on('select2:close', function (e) {
        ResetGrid();
        $("#txtAmount").val("");

        if ($('#cmbPayMode').val() == 1) { // Cash

            $("#cmbBank").val(0); // Select the option with a value of '0'
            $('#cmbBank').trigger('change'); // Notify any JS components that the value changed
            $("#cmbBank").prop('disabled', true);

            $("#txtChequeNo").val("");
            $("#txtChequeNo").prop('disabled', true);
            $("#txtChequeNo").css('background-color', '#eee');

            $("#PDDate").prop('disabled', true);
            $("#PDDate").css('background-color', '#eee');

            $('#dtPDDate').datetimepicker({
                defaultDate: new Date()
            });

            $("#txtAmount").focus();

        } else if ($('#cmbPayMode').val() == 2) { // Cheque

            $("#cmbBank").val('0');
            $("#cmbBank").prop('disabled', false);

            $("#txtChequeNo").val("");
            $("#txtChequeNo").prop('disabled', false);
            $("#txtChequeNo").css('background-color', '#FFFFFF');

            $("#PDDate").prop('disabled', false);
            $("#PDDate").css('background-color', '#FFFFFF');

            $("#txtAmount").focus();

        }
    });

    $('#txtAmount').on('keyup', function (e) {
        ResetGrid();
        calculateTotalAllocatedAndAvailableAmount();
    });

    $('#cmbBank').on('select2:close', function (e) {
        $("#txtChequeNo").focus();
    });

    $('#cmbIssueNo').on('select2:close', function (e) {
        if ($('#cmbCustomer').val() > 0) {
            if (checkPayModeSelected()) {
                var model = new Issue();
                model.intIssueHeaderID = $('#cmbIssueNo').val();
                ajaxCall('Receipt/getIssueNotePaymentDetails', model, function (response) {
                    $("#txtTotalAmount").val(parseFloat(response.decGrandTotal).toFixed(2));
                    $("#txtPaidAmount").val(parseFloat(response.decPaidAmount).toFixed(2));
                    $("#txtOutstrandingAmount").val((parseFloat(response.decGrandTotal) - parseFloat(response.decPaidAmount)).toFixed(2));
                    $("#txtPayAmount").focus();
                });
            }
        } else {
            toastr["error"]("Please Select Customer First !");
        }
    });

    $('#txtPayAmount').on('keyup', function (e) {
        if ($("#cmbIssueNo option:selected").val() == 0) {
            toastr["error"]("Please select issue no !");
            $("#txtPayAmount").val("");
            $('#cmbIssueNo').focus();
        }else if ($("#txtPayAmount").val() != "") {
            if (parseFloat($("#txtOutstrandingAmount").val()) < parseFloat($("#txtPayAmount").val())) {
                toastr["error"]("You can't pay more than outstanding amount!");
                $("#txtPayAmount").val("");
            } else if (parseFloat($("#txtPayAmount").val()) > totalAvailableAmount) {
                toastr["error"]("You can't exceed available amount!");
                $("#txtPayAmount").val("");
            }
        }
    });

    $('.add-item').keypress(function (event) {
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if (keycode == '13') {
            if ($("#cmbIssueNo option:selected").val() == 0) {
                toastr["error"]("Please select issue no !");
            }else if ($("#txtPayAmount").val() == "") {
                toastr["error"]("Please enter pay amount first !");
            }else{
                AddToGrid(true);
            }
        }

        event.stopPropagation();
    });





    // $('#txtQty').on('keyup', function (e) {
    //     if ($('#txtStockQty').val() == 0) {
    //         $('#txtQty').val(null);
    //         toastr["error"]("You can't Issue this. Because this item stock quantity is zero!");
    //     } else if ($('#txtStockQty').val() > 0) {
    //         if (parseFloat($('#txtQty').val()) > parseFloat($('#txtStockQty').val())) {
    //             toastr["error"]("You can't exceed stock quantity  !");
    //         }
    //     }

    // });


    $("#btnAddToGrid").click(function () {

        if ($("#cmbcustomer option:selected").val() == 0) {
            toastr["error"]("Please select customer !");
            return;
        }
        if ($("input[name=txtStockQty]").val() == "N/A" || $("input[name=txtStockQty]").val() == "0.00") {
            toastr["error"]("Please can't Add Stock Qty N/A !");
            return;
        }

        if ($("input[name=txtQty]").val() == "") {
            toastr["error"]("Please Enter Issue Qty !");
            return;
        }

        if ($("#cmbpayment option:selected").val() == 2) { //Credit
            if (chkCreditLimit() == false) {
                toastr["error"]("Customer CreditLimit Exceed !");
            }
            else {

                AddToGrid(false);
            }
        }
        else {
            AddToGrid(false);
        }
    });



    $('#btnSubmit').click(function () {
        var IsAdvancePayment = document.getElementById("IsAdvancePayment");

        if ($("#cmbcustomer option:selected").val() == 0) {
            toastr["error"]("Please select customer !");
            $("#cmbcustomer").focus();
            return;
        }
        if ($('#itemTable tr').length == 2) {
            toastr["error"]("Please add the issue items !");
            $("#cmbItem").focus();
        } else {
            if ($("#cmbpayment option:selected").val() == 2 && chkCreditLimit() == false) {
                toastr["error"]("Customer CreditLimit Exceed !");
                return;
            } if (IsAdvancePayment.checked) {
                if ($("input[name=grandTotal]").val() < AdvanceAmount) {
                    toastr["error"]("Please Enter more than Advance Payment !");
                    return;
                }
            }
            if ($("#cmbpayment option:selected").val() == 2) {
                if (IsAdvancePayment.checked) {
                    if (CreditBuyAmount < $("input[name=grandTotal]").val()) {
                        toastr["error"]("Customer CreditLimit Exceed !");
                        return;
                    }
                }
                else {
                    if (AvailableCredit < $("input[name=grandTotal]").val()) {
                        toastr["error"]("Customer CreditLimit Exceed ! Please Try Apply Advance Amount!");
                        return;
                    }
                }
            }


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
                            debugger;
                            arcadiaSuccessAfterIssuePrint("Issue No : " + response.vcIssueNo, response.intIssueHeaderID);
                            // $('#printpage', window.parent.document).hide();
                        } else {
                            toastr["error"](response.messages);
                        }

                    }
                });
            }, this);
        }

    });

});

function checkPayModeSelected() {
    if ($("#cmbPayMode").val() == 1) {  // Cash
        if ($("#txtAmount").val() == "") {
            $("#txtAmount").val("");
            $("#txtAmount").focus();
            toastr["error"]("Please Enter Cash Amount First !");
            return false;
        } else {
            if (parseFloat($("#txtAmount").val()) != 0) {
                return true;
            } else {
                $("#txtAmount").val("");
                $("#txtAmount").focus();
                toastr["error"]("Please Enter Cash Amount First !");
                return false;
            }
        }
    } else { // Cheque - 2
        if ($("#txtAmount").val() == "") {
            $("#txtAmount").val("");
            $("#txtAmount").focus();
            toastr["error"]("Please Enter Cheque Amount First !");
            return false;
        } else {
            if (parseFloat($("#txtAmount").val()) == 0) {
                $("#txtAmount").val("");
                $("#txtAmount").focus();
                toastr["error"]("Please Enter Cheque Amount First !");
                return false;
            } else if ($("#cmbBank option:selected").val() == 0) {
                $("#cmbBank").focus();
                toastr["error"]("Please Select Bank !");
                return false;
            } else if ($("#txtChequeNo").val() == "") {
                $("#txtChequeNo").focus();
                toastr["error"]("Please Enter Cheque No !");
                return false;
            } else {
                return true;
            }
        }
    }
}

function ResetGrid() {

    $("#cmbIssueNo").val(0); // Select the option with a value of '0'
    $('#cmbIssueNo').trigger('change'); // Notify any JS components that the value changed
    $("#txtTotalAmount").val("");
    $("#txtPaidAmount").val("");
    $("#txtOutstrandingAmount").val("");
    $("#txtPayAmount").val("");
    alert("Clear Table");
}

function calculateTotalAllocatedAndAvailableAmount() {

    if ($("#txtAmount").val() == "") {
        totalPayAmount = 0;
    } else {
        totalPayAmount = parseFloat($("#txtAmount").val());
    }

    $('#receiptTable tbody tr').each(function () {
        var value = parseFloat($(this).closest("tr").find('.total').val());
        if (!isNaN(value)) {
            totalAllocatedAmount += value;
        }
    });

    totalAvailableAmount = totalPayAmount - totalAllocatedAmount;

    $("#txtTotalAllocated").val(parseFloat(totalAllocatedAmount).toFixed(2));
    $("#txtTotalAvailable").val(parseFloat(totalAvailableAmount).toFixed(2));


}


function AddToGrid(IsMouseClick = false) {

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

                    $("input[name=cmbItem], input[name=txtMeasureUnit],input[name=txtUnitPrice],input[name=txtQty],input[name=txtStockQty],input[name=txtTotalPrice]").val("");
                    // $("input[name=txtTotalPrice]").val("0.00");
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

// function CalculateGrandTotal() {
//     if ($('#itemTable tr').length > 2) { // Because table header and item add row in here
//         var discount = $("#txtDiscount").val();
//         var total = 0;
//         $('#itemTable tbody tr').each(function () {
//             var value = parseFloat($(this).closest("tr").find('.total').val());
//             if (!isNaN(value)) {
//                 total += value;
//             }
//         });

//         discount == "" ? discount = 0 : discount;

//         $("#subTotal").val(currencyFormat(total));
//         $("#grandTotal").val(currencyFormat(total - discount));

//     } else {
//         debugger;
//         $("#subTotal").val("0.00");
//         $("#txtDiscount").val("0.00");
//         $("#grandTotal").val("0.00");
//     }
// }

function CalculateItemCount() {
    var rowCount = $('#itemTable tr').length;
    $("#itemCount").text("Item Count : " + (rowCount - 2));
}

// function CalculateTotal() {
//     // getMeasureUnitByItemID();
//     var unitPrice = $("#txtUnitPrice").val();
//     var qty = $("#txtQty").val()

//     if (unitPrice != "" && qty != "") {
//         var total = unitPrice * qty;
//         $("#txtTotalPrice").val(currencyFormat(total));
//     }
// }


// on first focus (bubbles up to document), open the menu
$(document).on('focus', '.select2-selection.select2-selection--single', function (e) {
    $(this).closest(".select2-container").siblings('select:enabled').select2('open');
});