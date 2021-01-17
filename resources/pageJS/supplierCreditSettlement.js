$(document).ready(function () {

    $('#cmbsupplier').on('select2:close', function (e) {
        getcmbInvoiceAndGRNno();
    });

});


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