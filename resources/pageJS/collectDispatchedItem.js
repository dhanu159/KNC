$(document).ready(function () {


    $('#cmbDispatchNo').on('select2:select', function (e) {
        getDispatchedDetails();
    });


    // $('#txtQty').on('keyup', function (e) {
    //     if ($('#txtStockQty').val() == 0) {
    //         $('#txtQty').val(null);
    //         toastr["error"]("You can't dispatch this. Because this item stock quantity is zero!");
    //     } else if ($('#txtStockQty').val() > 0) {
    //         if (parseFloat($('#txtQty').val()) > parseFloat($('#txtStockQty').val())) {
    //             toastr["error"]("You can't exceed stock quantity  !");
    //         }
    //     }

    // });
});

var Dispatch = function () {
    this.intDispatchHeaderID = 0;
}

function getDispatchedDetails(){
    var DispatchHeaderID = $("#cmbDispatchNo").val();
    if (DispatchHeaderID > 0) {

        $('#dispatchItemTable tbody').empty();
     

        var model = new Dispatch(); 
        model.intDispatchHeaderID = DispatchHeaderID;


        ajaxCall('Dispatch/getDispatchedItemDetails', model, function (response) {
            debugger;
            for (let index = 0; index < response.length; index++) {

                var htmlElement = '';
                var badge = '<span class="badge badge-success" style="padding: 4px 10px; float:right; margin-right:10px;">Fully Received</span>';



                if(parseFloat(response[index].ExpectedQty) > parseFloat(response[index].decReceivedQty)){
                    htmlElement = '<input type="text" class="form-control only-decimal" name="txtReceiveQty[]" id="txtReceiveQty" style="text-align:right;" placeholder="0.00">'
                    badge = '<span class="badge badge-secondary" style="padding: 4px 10px; float:right; margin-right:10px;">Partially Received</span>';
                }

                if(response[index].decReceivedQty == 0){
                    badge = '<span class="badge badge-warning" style="padding: 4px 10px; float:right; margin-right:10px;">TOTAL PENDING</span>';
                }  
              

                $("#dispatchItemTable tbody").append('<tr>'+
                '<td hidden><input type="number" class="form-control" name="txtDispatchDetailID[]" value="'+response[index].intDispatchDetailID+'"></td>'+
                '<td hidden><input type="number" class="form-control" name="txtItemID[]" value="'+response[index].intItemID+'"></td>'+
                '<td><div style="padding: 10px 0px; text-align:center;">'+response[index].OutputFinishItemName+badge+'</div></td>'+
                '<td><input type="text" class="form-control" name="txtMeasureUnit[]" id="txtMeasureUnit" style="text-align:center;" value="'+response[index].vcMeasureUnit+'" disabled></td>'+
                '<td hidden><input type="number" class="form-control" name="txtCuttingOrderDetailID[]" value="'+response[index].intCuttingOrderDetailID+'"></td>'+
                '<td><input type="text" class="form-control" name="txtExpectedQty[]" id="txtExpectedQty" style="text-align:right;" value="'+response[index].ExpectedQty+'" disabled></td>'+
                '<td><input type="text" class="form-control" name="txtReceivedQty[]" id="txtReceivedQty" style="text-align:right;" value="'+response[index].decReceivedQty+'" disabled></td>'+
                '<td><input type="text" class="form-control" name="txtBalanceQty[]" id="txtBalanceQty" style="text-align:right;" value="'+(response[index].ExpectedQty - response[index].decReceivedQty)+'" disabled></td>'+
                '<td>'+htmlElement+'</td>'+
                '<td hidden><input type="text" class="form-control" name="txtRv[]" id="txtRv"></td>'+
            '</tr>');
            }
      
        });
    }
}

$('#btnSubmit').click(function () {

    // if ($('#itemTable tr').length == 2) {
    //     toastr["error"]("Please add the dispatch items !");
    //     $("#cmbItem").focus();
    // } else {
        arcadiaConfirmAlert("You want to be able to collect this items !", function (button) {

            var form = $("#collectDispatchItem");

            $.ajax({
                async: false,
                type: form.attr('method'),
                url: form.attr('action'),
                data: form.serialize(), 
                dataType: 'json',
                success: function (response) {
                    debugger;
                    if (response.success == true) {
                        arcadiaSuccessMessage("Saved !");
                    } else {

                        if (response.messages instanceof Object) {
                            $.each(response.messages, function (index, value) {
                                var id = $("#" + index);

                                id.closest('.form-group')
                                    .removeClass('has-error')
                                    .removeClass('has-success')
                                    .addClass(value.length > 0 ? 'has-error' : 'has-success');

                                id.after(value);

                            });
                        } else {
                            toastr["error"](response.messages);
                            // arcadiaErrorMessage(response.messages);
                            // $(button).prop('disabled', false);
                        }
                    }
                }
            });
        }, this);
    // }

});


