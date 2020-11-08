$(document).ready(function () {

    CalculateItemCount();
});

function CalculateItemCount() {
    var rowCount = $('#itemTable tr').length;
    $("#itemCount").text("Item Count : " + (rowCount - 1));
}


function approveGRN(GRNHeaderID) {
    arcadiaConfirmAlert("You want to be able to approve this !", function (button) {

        $.ajax({
            async: true,
            url: base_url + 'GRN/ApproveGRN',
            type: 'post',
            data: {
                intGRNHeaderID: GRNHeaderID
            },
            dataType: 'json',
            success: function (response) {
                if (response.success == true) {
                    arcadiaSuccessMessage("Approved !", "GRN/ViewGRN");
                } else {
                    toastr["error"](response.messages);
                }
            },
            error: function (request, status, error) {
                arcadiaErrorMessage(error);
            }
        });
    }, this);
}