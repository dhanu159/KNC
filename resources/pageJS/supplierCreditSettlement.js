var totalAllocatedAmount = 0;
var totalPayAmount = 0;
var totalAvailableAmount = 0;

var Supplier = function () {
    this.intGRNHeaderID = 0;
}

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

    $('#cmbsupplier').on('select2:close', function (e) {
        getcmbInvoiceAndGRNno();
    });

    $('#cmbGRNNo').on('select2:close', function (e) {
        $("#txtPayAmount").focus();
    });

    $('#txtAmount').on('keyup', function (e) {
        ResetGrid();
        calculateTotalAllocatedAndAvailableAmount();
    });
    
    $('#dtPDDate').datetimepicker({
        defaultDate: new Date()
    });


    $('#cmbGRNNo').on('select2:close', function (e) {
        if ($('#cmbsupplier').val() > 0) {
            if (checkPayModeSelected()) {
                var model = new Supplier();
                debugger;
                model.intGRNHeaderID = $('#cmbGRNNo').val();
                ajaxCall('Supplier/getGRNPaymentDetails', model, function (response) {
                    $("#txtTotalAmount").val(parseFloat(response.decGrandTotal).toFixed(2));
                    $("#txtPaidAmount").val(parseFloat(response.decPaidAmount).toFixed(2));
                    $("#txtOutstrandingAmount").val((parseFloat(response.decGrandTotal) - parseFloat(response.decPaidAmount)).toFixed(2));
                    $("#txtRv").val(response.rv);
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
        } else if ($("#txtPayAmount").val() != "") {
            if (parseFloat($("#txtOutstrandingAmount").val()) < parseFloat($("#txtPayAmount").val())) {
                toastr["error"]("You can't pay more than outstanding amount!");
                $("#txtPayAmount").val("");
            } else if (parseFloat($("#txtPayAmount").val()) > totalAvailableAmount) {
                toastr["error"]("You can't exceed available amount!");
                $("#txtPayAmount").val("");
            }
        }
    });

    // $('#cmbPayMode').on('select2:close', function (e) {

    //     if ($("#cmbPayMode option:selected").val() == 1)
    //     {
    //         $("#cmbBank").prop('disabled', true);
    //         $('#cmbBank').val('0'); // Select the option with a value of '0'
    //         $('#cmbBank').trigger('change'); // Notify any JS components that the value changed
    //         $("#txtChequeNo").prop('disabled', true);
    //         $("input[name=txtChequeNo]").val("");
    //         document.getElementById("txtChequeNo").placeholder = "Enter Cheque Number";
    //         $("#txtChequeNo").css('background-color', '#eee');
    //         $("#PDDate").prop('disabled', true);
    //         $("#PDDate").css('background-color', '#eee');
    //     }
    //    if ($("#cmbPayMode option:selected").val() == 2) {
    //         $("#cmbBank").prop('disabled', false);
    //         $("#txtChequeNo").prop('disabled', false);
    //         $("#txtChequeNo").css('background-color', '#ffffff');
    //         $("#PDDate").prop('disabled', false);
    //         $("#PDDate").css('background-color', '#ffffff');
    //     }
    // });

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

    $("#cmbBank").prop('disabled', true);
    $("#txtChequeNo").prop('disabled', true);
    $("#txtChequeNo").css('background-color', '#eee');
    $("#PDDate").prop('disabled', true);
    $("#PDDate").css('background-color', '#eee');

    function remove() {
        $(".red").click(function () {
            // var itemID = $(this).closest("tr").find('td.itemID').text();
            // var itemName = $(this).closest("tr").find('td.itemName').text();

            var itemID = $(this).closest("tr").find('.GRNHeaderID').val();
            var itemName = $(this).closest("tr").find('.GRNNo').val();

            var IsAlreadyIncluded = false;

            $("#cmbGRNNo option").each(function () {
                if (itemID == $(this).val()) {
                    IsAlreadyIncluded = true;
                    return false;
                }
            });

            if (!IsAlreadyIncluded) {
                var cmbItem = $('#cmbGRNNo');
                cmbItem.append(
                    $('<option></option>').val(itemID).html(itemName)
                );
                $(this).closest("tr").remove();
            }
            // CalculateItemCount();
            // CalculateGrandTotal();
        });
    }

    remove();

    $("#btnAddToGrid").click(function () {
        AddToGrid();
    });

    var row_id = 1;

    function AddToGrid() {
        if ($("input[name=txtPayAmount]").val() == "") {
            toastr["error"]("Please enter Pay amount !");
            return
        }
        if ($("#cmbGRNNo option:selected").val() > 0) {
            var GRNHeaderID = $("#cmbGRNNo option:selected").val();
            var GRNNo = $("#cmbGRNNo option:selected").text();
            var txtTotalAmount = $("input[name=txtTotalAmount]").val();
            var txtPaidAmount = $("input[name=txtPaidAmount]").val();
            var txtOutstrandingAmount = $("input[name=txtOutstrandingAmount]").val();
            var txtPayAmount = $("input[name=txtPayAmount]").val();
            var Rv = $("input[name=txtRv]").val();

            $(".first-tr").after('<tr>' +
                '<td hidden>' +
                '<input type="text" class="form-control GRNHeaderID disable-typing" name="GRNHeaderID[]" id="GRNHeaderID_' + row_id + '" value="' + GRNHeaderID + '" readonly>' +
                '</td>' +
                '<td>' +
                '<input type="text" class="form-control GRNNo disable-typing" name="GRNNo[]" id="GRNNo_' + row_id + '" value="' + GRNNo + '" readonly>' +
                '</td>' +
                '<td>' +
                '<input type="text" class="form-control disable-typing" style="text-align:right;" name="txtTotalAmount[]" id="txtTotalAmount_' + row_id + '" value="' + parseFloat(txtTotalAmount).toFixed(2) + '" readonly>' +
                '</td>' +
                '<td>' +
                '   <input type="text" class="form-control disable-typing" style="text-align:center;" name="txtPaidAmount[]" id="txtPaidAmount_' + row_id + '"  value="' + parseFloat(txtPaidAmount).toFixed(2) + '" readonly>' +
                '</td>' +
                '<td>' +
                '   <input type="text" class="form-control disable-typing" style="text-align:center;" name="txtOutstrandingAmount[]" id="txtOutstrandingAmount_' + row_id + '"  value="' + parseFloat(txtOutstrandingAmount).toFixed(2) + '" readonly>' +
                '</td>' +
                '<td>' +
                '<input type="text" class="form-control total disable-typing" style="text-align:right;" name="txtPayAmount[]" id="txtPayAmount_' + row_id + '"  value="' + txtPayAmount + '" readonly>' +
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
            $("#cmbGRNNo :selected").remove();

            $("input[name=cmbGRNNo], input[name=txtTotalAmount],input[name=txtPaidAmount],input[name=txtPaidAmount],input[name=txtOutstrandingAmount],input[name=txtPayAmount],input[name=txtRv]").val("");

            // CalculateItemCount();
            // CalculateGrandTotal();
            $("#cmbGRNNo").focus();
            $("li").attr('aria-selected', false);

        } else {
            toastr["error"]("Please select valid item !");
            $("#cmbGRNNo").focus();
            $("li").attr('aria-selected', false);
        }
    }

});

function ResetGrid() {

    $("#cmbIssueNo").val(0); // Select the option with a value of '0'
    $('#cmbIssueNo').trigger('change'); // Notify any JS components that the value changed
    $("#txtTotalAmount").val("");
    $("#txtPaidAmount").val("");
    $("#txtOutstrandingAmount").val("");
    $("#txtPayAmount").val("");
    // alert("Clear Table");
}

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

function calculateTotalAllocatedAndAvailableAmount() {

    if ($("#txtAmount").val() == "") {
        totalPayAmount = 0;
    } else {
        totalPayAmount = parseFloat($("#txtAmount").val());
    }

    $('#itemTable tbody tr').each(function () {
        var value = parseFloat($(this).closest("tr").find('.total').val());
        if (!isNaN(value)) {
            totalAllocatedAmount += value;
        }
    });

    totalAvailableAmount = totalPayAmount - totalAllocatedAmount;

    $("#txtTotalAllocated").val(parseFloat(totalAllocatedAmount).toFixed(2));
    $("#txtTotalAvailable").val(parseFloat(totalAvailableAmount).toFixed(2));


}

function getPaymentDetailsBySupplierID() {

    var ItemID = $("#cmbItem").val();
    var customerID = $("#cmbcustomer").val();

    if (ItemID > 0) {
        $.ajax({
            async: false,
            url: base_url + 'item/fetchItemDetailsByCustomerID/' + ItemID + '/' + customerID,
            type: 'post',
            dataType: 'json',
            success: function (response) {
                $("#txtUnitPrice").val(response.decUnitPrice);
                $("#txtStockQty").val(response.decStockInHand);
                $("#txtMeasureUnit").val(response.vcMeasureUnit);
                $("#txtRv").val(response.rv);

                // if (response.decStockInHand == 'N/A') {
                //     toastr["error"]("Please Check Stock");
                // }
            },
            error: function (xhr, status, error) {
                //var err = eval("(" + xhr.responseText + ")");
                arcadiaErrorMessage(error);
            }
        });
    }
}

function getcmbInvoiceAndGRNno() {
    var supplierID = $("#cmbsupplier").val();
    $.ajax({
        async: false,
        url: 'getSupplierWiseInvoiceAndGRNno/' + supplierID,
        type: 'post',
        dataType: 'json',
        success: function (response) {
            $("#cmbPayMode").prop('disabled', false);
            $("#txtAmount").prop('disabled', false);
            $("#txtAmount").css('background-color', '#FFFFFF');
            $("#txtRemark").prop('disabled', false);
            $("#txtRemark").css('background-color', '#FFFFFF');

            var issueTotal = 0;
            var paidTotal = 0;

            $("#cmbGRNNo").empty();
            $("#cmbGRNNo").append('<option value=" 0" disabled selected hidden>Select Invoice No</option>');
            for (let index = 0; index < response.length; index++) {
                $("#cmbGRNNo").append('<option value="' + response[index].intGRNHeaderID + '">' + response[index].vcGRNNo + '</option>');
                issueTotal += parseFloat(response[index].decGrandTotal);
                paidTotal += parseFloat(response[index].decPaidAmount);
            }
            $("#txtTotalOutstanding").val(parseFloat(issueTotal - paidTotal).toFixed(2));
            $("#cmbGRNNo li").attr('aria-selected', false);
        },
        error: function (xhr, status, error) {
            arcadiaErrorMessage(error);
        }
    });

}

$('#btnSubmit').click(function () {

    arcadiaConfirmAlert("You want to be able to settlement this !", function (button) {
        var form = $("#createSupplierCreditSettlement");

        $.ajax({
            async: false,
            type: form.attr('method'),
            url: form.attr('action'),
            data: form.serialize(),
            dataType: 'json',
            success: function (response) {
                debugger;
                if (response.success == true) {
                    arcadiaSuccessMessage("Settlement Saved !");
          
                } else {
                    toastr["error"](response.messages);
                }

            }
        });
    }, this);


});