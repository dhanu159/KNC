$(document).ready(function () {
    $(document).on('keyup', 'input[type=search]', function (e) {
        $("li").attr('aria-selected', false);
    });

    $("#btnAddToGrid").click(function () {
        AddToGrid();
    });

    $('.add-item').keypress(function (event) {
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if (keycode == '13') {
            AddToGrid();
        }
        event.stopPropagation();
    });

    $('#cmbItem').on('select2:select', function (e) {
        getCuttingOrdersByItemID();
    });

    $('#cmbCuttingOrder').on('select2:select', function (e) {
        $('#txtQty').focus();
    });

    $('#txtQty').on('keyup', function (e) {
        if ($('#txtStockQty').val()==0) {
            $('#txtQty').val(null);
            toastr["error"]("You can't dispatch this. Because this item stock quantity is zero!");
        } else if ($('#txtStockQty').val() > 0){
            if (parseFloat($('#txtQty').val()) > parseFloat($('#txtStockQty').val())) {
                toastr["error"]("You can't exceed stock quantity  !");
            }
        }
       
    });
});

function getCuttingOrdersByItemID(){
    getItemData();
    $('#txtQty').val(null);
    var ItemID = $("#cmbItem").val();
    
    if (ItemID > 0) {
        $.ajax({
            url: base_url + 'Utilities/getCuttingOrdersByItemID/' + ItemID, 
            type: 'post',
            dataType: 'json',
            success: function (response) {
                $("#cmbCuttingOrder").empty();
                $("#cmbCuttingOrder").append('<option value=" 0" disabled selected hidden>Select Cutting Order</option>');
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

function getItemData() {
    var ItemID = $("#cmbItem").val(); 
    if (ItemID > 0) {
        $.ajax({
            url: base_url + 'Item/fetchItemDataById/' + ItemID,
            type: 'post',
            dataType: 'json',
            success: function (response) {
                debugger;
                $("#txtMeasureUnit").val(response.vcMeasureUnit);
                $("#txtStockQty").val(response.decStockInHand);
                $("#txtRv").val(response.rv);

            },
            error: function (xhr, status, error) {
                //var err = eval("(" + xhr.responseText + ")");
                arcadiaErrorMessage(error);
            }
        });
    }
}

var row_id = 1;

function AddToGrid() {
    if ($("#cmbItem option:selected").val() == 0 || $("#cmbCuttingOrder option:selected").val() == 0 || $("#txtQty").val() == "") {
        toastr["error"]("Please fill in all fields !");
    } else {

        if ($("#cmbItem option:selected").val() > 0) {
            var itemID = $("#cmbItem option:selected").val();
            var item = $("#cmbItem option:selected").text();
            var measureUnit = $("input[name=txtMeasureUnit]").val();
            var stockQty = $("input[name=txtStockQty]").val();
            var cuttingOrderId = $("#cmbCuttingOrder option:selected").val();
            var cuttingOrderName = $("#cmbCuttingOrder option:selected").text();
            var qty = $("input[name=txtQty]").val();
            var Rv = $("input[name=txtRv]").val();

            $(".first-tr").after('<tr>' +
                '<td hidden>' +
                '<input type="text" class="form-control itemID disable-typing" name="itemID[]" id="itemID_' + row_id + '" value="' + itemID + '" readonly>' +
                '</td>' +
                '<td>' +
                '<input type="text" class="form-control itemName disable-typing" name="itemName[]" id="itemName_' + row_id + '" value="' + item + '" readonly>' +
                '</td>' +
                '<td>' +
                '   <input type="text" class="form-control disable-typing" style="text-align:center;" name="unit[]" id="unit_' + row_id + '"  value="' + measureUnit + '" readonly>' +
                '</td>' +
                '<td>' +
                '   <input type="text" class="form-control disable-typing" style="text-align:center;" name="stockQty[]" id="stockQty_' + row_id + '"  value="' + stockQty + '" readonly>' +
                '</td>' +
                '<td hidden>' +
                '<input type="text" class="form-control cuttingOrderId disable-typing" name="cuttingOrderId[]" id="cuttingOrderId_' + row_id + '" value="' + cuttingOrderId + '" readonly>' +
                '</td>' +
                '<td>' +
                '<input type="text" class="form-control cuttingOrderName disable-typing" name="cuttingOrderName[]" id="cuttingOrderName_' + row_id + '" value="' + cuttingOrderName + '" readonly>' +
                '</td>' +
                '<td>' +
                '<input type="text" class="form-control disable-typing" style="text-align:right;" name="itemQty[]" id="itemQty_' + row_id + '"  value="' + qty + '" readonly>' +
                '</td>' +
                '<td hidden>' +
                '<input type="text" class="form-control Rv disable-typing" name="Rv[]" id="Rv_' + row_id + '" value="' + Rv + '" readonly>' +
                '</td>' +
                '<td class="static">' +
                '<span class="button red center-items"><i class="fas fa-times"></i></span>' +
                '</td>' +
                '</tr>');

            row_id++;
            remove();
            $("#cmbItem :selected").remove();

            $("input[name=cmbItem],input[name=cmbCuttingOrder], input[name=txtMeasureUnit],input[name=txtQty]").val("");
            CalculateItemCount();
            $("#cmbItem").focus();
            $("li").attr('aria-selected', false);
        } else {
            toastr["error"]("Please select valid item !");
            $("#cmbItem").focus();
            $("li").attr('aria-selected', false);
        }
    }
}

function CalculateItemCount() {
    var rowCount = $('#itemTable tr').length;
    $("#itemCount").text("Item Count : " + (rowCount - 2));
}

function remove() {
    $(".red").click(function () {

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
    });
}

// on first focus (bubbles up to document), open the menu
$(document).on('focus', '.select2-selection.select2-selection--single', function (e) {
    $(this).closest(".select2-container").siblings('select:enabled').select2('open');
});