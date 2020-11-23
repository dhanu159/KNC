$(document).ready(function () {

});

function getCuttingOrdersByItemID(){
    getMeasureUnitByItemID();

    var ItemID = $("#cmbItem").val();
    
    if (ItemID > 0) {
        $.ajax({
            url: base_url + 'Utilities/getCuttingOrdersByItemID/' + ItemID, 
            type: 'post',
            dataType: 'json',
            success: function (response) {
                $("#cmbCuttingOrder").empty();
                for (let index = 0; index < response.length; index++) {
                    $("#cmbCuttingOrder").append('<option value="' + response[index].intCuttingOrderHeaderID+'">' + response[index].vcOrderName+'</option>');
                }
            },
            error: function (xhr, status, error) {
                arcadiaErrorMessage(error);
            }
        });
    }
}

function getMeasureUnitByItemID() {
    var ItemID = $("#cmbItem").val();
    if (ItemID > 0) {
        $.ajax({
            url: base_url + 'GRN/getMeasureUnitByItemID/' + ItemID,
            type: 'post',
            dataType: 'json',
            success: function (response) {
                $("#txtMeasureUnit").val(response.vcMeasureUnit);
            },
            error: function (xhr, status, error) {
                //var err = eval("(" + xhr.responseText + ")");
                arcadiaErrorMessage(error);
            }
        });
    }
}