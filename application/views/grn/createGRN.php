<style>
    /* body {
        background: #f6f6f6;
    } */

    /* #table {
        position: relative;
        overflow: hidden;
        margin-top: 50px;
    } */

    /* table {
        background: black;
        box-shadow: 0 10px 30px rgba(225, 225, 225, 0.5);
    } */

    thead tr {
        background-color: #17a2b8;
        color: #FFFFFF;
        border: 1px solid #17a2b8;
    }

    .first-tr {
        /* background-color: #c2c7d0; */
        border: 2px solid #17a2b8;
    }

    .button {
        width: 35px;
        height: 35px;
        color: #fff;
        display: flex;
        align-items: center;
        cursor: pointer;
        justify-content: center;
        box-shadow: 0 10px 30px rgba(225, 225, 225, 0.4);
        border: none;
    }

    .red,
    .remove {
        background: #e74c3c;
    }

    .green {
        background: #2ecc71;
    }

    /* tr>td {
        position: relative;
    } */

    .static {
        position: static !important;
    }

    .center-items {
        /* align-items: center; */
        margin: 0 auto;

    }
</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper arcadia-main-container">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Create GRN</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">GRN</a></li>
                        <li class="breadcrumb-item active">Create GRN</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-md-12">
                        <label for="supplier">Supplier</label>
                        <select class="form-control select2" style="width: 100%;" id="supplier" name="measure_unit">
                            <option value="0" disabled selected hidden>Select Supplier</option>
                            <?php foreach ($supplier_data as $k => $v) { ?>
                                <option value="<?= $v['intSupplierID'] ?>"><?= $v['vcSupplierName'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="invoice_no">Invoice No</label>
                        <input type="text" class="form-control" id="invoice_no" name="invoice_no" placeholder="Enter Invoice Number" autocomplete="off" required />
                    </div>
                    <div class="form-group col-md-6">
                        <label>Received Date</label>
                        <div class="input-group date" id="receivedDate" data-target-input="nearest">
                            <input type="text" class="form-control datetimepicker-input" id="dtReceivedDate" placeholder="Select Received Date" />
                            <div class="input-group-append" data-target="#receivedDate" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                        </div>
                    </div>
                    <!-- <div id="target" style="position:relative" data-target-input="nearest">
                        <input type="text" class="form-control datetimepicker-input" id="Datetimepicker" data-toggle="datetimepicker" data-target="#target" autocomplete="off" style="width: 200px;" />
                    </div> -->
                </div>

                <table class="table" id="itemTable">
                    <thead>
                        <tr>
                            <th hidden>Item ID</th>
                            <th style="text-align:center;">Item</th>
                            <th style="width: 200px; text-align:center;">Unit Price</th>
                            <th style="width: 100px; text-align:center;">Qty</th>
                            <th style="width: 200px; text-align:center;">Total Price</th>
                            <th style="width: 100px; text-align: center;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <form class="add-form">
                            <tr class="first-tr">
                                <td class="static" hidden><input type="number" class="form-control" name="txtItemID" min="0"></td>
                                <td class="static">
                                    <!-- <input type="text" class="form-control" name="txtItem"> -->
                                    <select class="form-control select2" style="width: 100%;" id="cmbItem" name="cmbItem">
                                        <option value="0" disabled selected hidden>Select Item</option>
                                        <?php foreach ($item_data as $k => $v) { ?>
                                            <option value="<?= $v['intItemID'] ?>"><?= $v['vcItemName'] ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                                <td class="static"><input type="text" class="form-control only-decimal" name="txtUnitPrice" id="txtUnitPrice" style="text-align:right;"></td>
                                <td class="static"><input type="text" class="form-control only-decimal" name="txtQty" id="txtQty" style="text-align:right;"></td>
                                <td class="static"><input type="text" class="form-control only-decimal" name="txtTotalPrice" id="txtTotalPrice" placeholder="0.00" style="text-align:right;" disabled></td>
                                <td class="static"><button type="submit" class="button green center-items"><i class="fas fa-plus"></i></button></td>
                            </tr>
                        </form>
                    </tbody>
                </table>

                <!-- <div class="col-md-6" style="background-color: blue;">
                    dds</div> -->
                <div class="row" style="border-top:1px solid #dee2e6;">
                    <div class="col-6">
                        <p style="color: #c2c7d0; position:absolute; bottom:0;" id="itemCount">Item Count : 0</p>
                        <!-- <p class="lead">Payment Methods:</p>
                        <img src="../../dist/img/credit/visa.png" alt="Visa">
                        <img src="../../dist/img/credit/mastercard.png" alt="Mastercard">
                        <img src="../../dist/img/credit/american-express.png" alt="American Express">
                        <img src="../../dist/img/credit/paypal2.png" alt="Paypal">

                        <p class="text-muted well well-sm shadow-none" style="margin-top: 10px;">
                            Etsy doostang zoodles disqus groupon greplin oooj voxy zoodles, weebly ning heekya handango imeem
                            plugg
                            dopplr jibjab, movity jajah plickers sifteo edmodo ifttt zimbra.
                        </p> -->
                    </div>
                    <!-- /.col -->
                    <div class="col-6" style="padding-right:100px;">
                        <div class="table-responsive">
                            <table class="table">
                                <tr>
                                    <th style="border-top:0 !important;">Sub Total:</th>
                                    <td id="subTotal" style="width: 200px; font-weight: 600; text-align:right; border-top:0 !important;">0.00</td>
                                </tr>
                                <tr>
                                    <th style="border-top:0 !important;">Discount:</th>
                                    <td style="border-top:0 !important;"><input type="text" class="form-control only-decimal" name="txtDiscount" id="txtDiscount" placeholder="0.00" style="font-weight: 600; text-align:right;"></td>
                                </tr>
                                <tr style="border-top:2px solid #dee2e6; border-bottom:2px solid #dee2e6;">
                                    <th style="font-size:1.5em;">Grand Total:</th>
                                    <td id="grandTotal" style="font-weight: 600; text-align:right; font-size:1.5em;">0.00</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <!-- /.col -->
                </div>
            </div>
            <!-- /.card-body -->

        </div>
        <!-- /.card -->

    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<script>
    $(document).ready(function() {

        // $("#target").datetimepicker({
        //     format: 'L',
        //     defaultDate: new Date()
        // });

        // $("#target").on("change.datetimepicker", function() {

        //     alert("Changed date");


        // })

        // $("#target").datetimepicker({
        //     format: 'L',
        //     defaultDate: new Date()
        // });

        // $("#receivedDate").on("change.datetimepicker", function() {

        //     alert("Changed date");


        // })

        $('#cmbItem').on('select2:select', function(e) {
            $('#txtUnitPrice').focus();
        });

        $('#txtQty,#txtUnitPrice').keyup(function(event) {
            CalculateTotal();
        });


        $('#txtDiscount').keyup(function(event) {
            CalculateGrandTotal();
        });

        function CalculateTotal() {
            var unitPrice = $("#txtUnitPrice").val();
            var qty = $("#txtQty").val()

            if (unitPrice != "" && qty != "") {
                var total = unitPrice * qty;
                $("#txtTotalPrice").val(parseFloat(total).toFixed(2));
            }
        }

        function CalculateGrandTotal() {
            if ($('#itemTable tr').length > 2) { // Because table header and item add row in here
                var discount = $("#txtDiscount").val();
                var total = 0;
                $('#itemTable tbody tr').each(function() {
                    var value = parseInt($(this).closest("tr").find('td.total').text());
                    if (!isNaN(value)) {
                        total += value;
                    }

                });

                discount == "" ? discount = 0 : discount;

                $("#subTotal").text(parseFloat(total).toFixed(2));
                $("#grandTotal").text(parseFloat(total - discount).toFixed(2));

            } else {
                $("#subTotal").text("0.00");
                $("#txtDiscount").text("0.00");
                $("#grandTotal").text("0.00");
            }
        }

        function CalculateItemCount() {
            var rowCount = $('#itemTable tr').length;
            $("#itemCount").text("Item Count : " + (rowCount - 2));
        }


        remove();
        $(".add-form").on('submit', function(e) {
            e.preventDefault();
            if ($("input[name=cmbItem]").val(0), $("input[name=txtUnitPrice]").val(), $("input[name=txtQty]").val() == "") {
                toastr["error"]("Please fill in all fields !");
            } else {
                var itemID = $("#cmbItem option:selected").val();
                var item = $("#cmbItem option:selected").text();
                var unitPrice = $("input[name=txtUnitPrice]").val();
                var qty = $("input[name=txtQty]").val();
                var total = unitPrice * qty;

                $(".first-tr").after('<tr><td class="itemID" hidden>' + itemID + '</td><td class="itemName">' + item + '</td><td style="text-align:right;">' + parseFloat(unitPrice).toFixed(2) + '</td><td style="text-align:right;">' + qty + '</td><td class="total" style="text-align:right;">' + parseFloat(total).toFixed(2) + '</td><td class="static"><span class="button red center-items"><i class="fas fa-times"></i></span></td></tr>');
                remove();
                $("#cmbItem :selected").remove();

                $("input[name=cmbItem], input[name=txtUnitPrice], input[name=txtQty]").val("");
                $("input[name=txtTotalPrice]").val("0.00");
                CalculateItemCount();
                CalculateGrandTotal();
                $("#cmbItem").focus();

            }
        });

        function remove() {
            $(".red").click(function() {

                var itemID = $(this).closest("tr").find('td.itemID').text();
                var itemName = $(this).closest("tr").find('td.itemName').text();

                var cmbItem = $('#cmbItem');

                cmbItem.append(
                    $('<option></option>').val(itemID).html(itemName)
                );

                $(this).closest("tr").remove();
                CalculateItemCount();
                CalculateGrandTotal();
            });
        }
    });

    // on first focus (bubbles up to document), open the menu
    $(document).on('focus', '.select2-selection.select2-selection--single', function(e) {
        $(this).closest(".select2-container").siblings('select:enabled').select2('open');
    });

    // // steal focus during close - only capture once and stop propogation
    // $('select.select2').on('select2:closing', function(e) {
    //     $(e.target).data("select2").$selection.one('focus focusin', function(e) {
    //         e.stopPropagation();
    //     });
    // });
</script>