$(document).ready(function () {


    $('#cmbIssueNo').on('select2:select', function (e) {
        getDispatchedDetails();
    });


});

var Issue = function () {
    this.intIssueHeaderID = 0;
}

function getDispatchedDetails(){
    var IssueHeaderID = $("#cmbIssueNo").val();
    if (IssueHeaderID > 0) {

        $('#IssueItemTable tbody').empty();
     

        var model = new Issue(); 
        model.intIssueHeaderID = IssueHeaderID;


        ajaxCall('Issue/ViewIssueDetailsToTable', model, function (response) {
            // debugger;
            for (let index = 0; index < response.length; index++) {

                // var htmlElement = '';
                // var badge = '<span class="badge badge-success" style="padding: 4px 10px; float:right; margin-right:10px;">Fully Received</span>';



                // if(parseFloat(response[index].ExpectedQty) > parseFloat(response[index].decReceivedQty)){
                //     htmlElement = '<input type="text" class="form-control only-decimal" name="txtReceiveQty[]" id="txtReceiveQty" style="text-align:right;" placeholder="0.00">'
                //     badge = '<span class="badge badge-secondary" style="padding: 4px 10px; float:right; margin-right:10px;">Partially Received</span>';
                // }

                // if(response[index].decReceivedQty == 0){
                //     badge = '<span class="badge badge-warning" style="padding: 4px 10px; float:right; margin-right:10px;">TOTAL PENDING</span>';
                // }  
              

                $("#IssueItemTable tbody").append('<tr>'+
                '<td hidden><input type="number" class="form-control" name="txtItemID[]" value="'+response[index].vcItemName+'"></td>'+
                '<td><input type="text" class="form-control" name="txtMeasureUnit[]" id="txtMeasureUnit" style="text-align:center;" value="'+response[index].vcItemName+'" disabled></td>'+
                '<td><input type="text" class="form-control" name="txtExpectedQty[]" id="txtExpectedQty" style="text-align:right;" value="'+response[index].decIssueQty+'" disabled></td>'+
                '<td><input type="text" class="form-control" name="txtReceivedQty[]" id="txtReceivedQty" style="text-align:right;" value="'+response[index].decUnitPrice+'" disabled></td>'+
                '<td><input type="text" class="form-control" name="txtBalanceQty[]" id="txtBalanceQty" style="text-align:right;" value="'+response[index].decTotalPrice +'" disabled></td>'+
                // '<td>'+htmlElement+'</td>'+
                '<td hidden><input type="text" class="form-control" name="txtRv[]" id="txtRv"></td>'+
            '</tr>');
            }
      
        });
    }
}

$('#btnSubmit').click(function () {

    if ($("#cmbIssueNo option:selected").val() == 0) {
        toastr["error"]("Please select Issue No !");
        $("#cmbIssueNo").focus();
        return;
    }
        arcadiaConfirmAlert("You want to be able to return this issue note !", function (button) {

            var form = $("#issueNote");

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


