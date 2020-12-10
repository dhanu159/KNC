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

        var model = new Dispatch(); 
        model.intDispatchHeaderID = DispatchHeaderID;


        ajaxCall('Dispatch/getDispatchedItemDetails', model, function (response) {

        });
    }
}