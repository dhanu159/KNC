$(document).ready(function () {

    $('#cmbsupplier').on('select2:close', function (e) {
        getcmbInvoiceAndGRNno();
    });

    $('#cmbGRNNo').on('select2:close', function (e) {
        $("#txtPayAmount").focus();
    });

    $('#cmbPayMode').on('select2:close', function (e) {
  
        if ($("#cmbPayMode option:selected").val() == 1)
        {
            $("#cmbBank").prop('disabled', true);
            $('#cmbBank').val('0'); // Select the option with a value of '0'
            $('#cmbBank').trigger('change'); // Notify any JS components that the value changed
            $("#txtChequeNo").prop('disabled', true);
            $("input[name=txtChequeNo]").val("");
            document.getElementById("txtChequeNo").placeholder = "Enter Cheque Number";
            $("#txtChequeNo").css('background-color', '#eee');
            $("#PDDate").prop('disabled', true);
            $("#PDDate").css('background-color', '#eee');
        }
       if ($("#cmbPayMode option:selected").val() == 2) {
            $("#cmbBank").prop('disabled', false);
            $("#txtChequeNo").prop('disabled', false);
            $("#txtChequeNo").css('background-color', '#ffffff');
            $("#PDDate").prop('disabled', false);
            $("#PDDate").css('background-color', '#ffffff');
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
                '<input type="text" class="form-control disable-typing" style="text-align:right;" name="txtPayAmount[]" id="txtPayAmount_' + row_id + '"  value="' + txtPayAmount + '" readonly>' +
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
            $("#cmbGRNNo").empty();
            $("#cmbGRNNo").append('<option value=" 0" disabled selected hidden>Select Invoice No</option>');
            for (let index = 0; index < response.length; index++) {
                $("#cmbGRNNo").append('<option value="' + response[index].intGRNHeaderID + '">' + response[index].vcGRNNo + '</option>');
            }
            $("#cmbGRNNo li").attr('aria-selected', false);
        },
        error: function (xhr, status, error) {
            arcadiaErrorMessage(error);
        }
    });

}