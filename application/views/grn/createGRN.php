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
                        <div class="input-group date" id="reservationdate" data-target-input="nearest">
                            <input type="text" class="form-control datetimepicker-input" data-target="#reservationdate" />
                            <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                        </div>
                    </div>
                </div>

                <table class="table">
                    <thead>
                        <tr>
                            <!-- <th></th> -->
                            <th>Item</th>
                            <th style="width: 200px;">Unit Price</th>
                            <th style="width: 100px;">Qty</th>
                            <th style="width: 200px;">Total Price</th>
                            <th style="width: 100px; text-align: center;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <form class="add-form">
                            <tr class="first-tr">
                                <td class="static">
                                    <!-- <input type="text" class="form-control" name="txtItem"> -->
                                    <select class="form-control select2" style="width: 100%;" id="cmbItem" name="measure_unit">
                                        <option value="0" disabled selected hidden>Select Item</option>
                                        <?php foreach ($item_data as $k => $v) { ?>
                                            <option value="<?= $v['intItemID'] ?>"><?= $v['vcItemName'] ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                                <td class="static"><input type="number" class="form-control" name="txtUnitPrice" min="0"></td>
                                <td class="static"><input type="number" class="form-control" name="txtQty" min="0"></td>
                                <td class="static"><input type="text" class="form-control" name="txtTotalPrice" placeholder="0.00" disabled></td>
                                <td class="static"><button type="submit" class="button green center-items"><i class="fas fa-plus"></i></button></td>
                            </tr>
                        </form>
                    </tbody>
                </table>
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

        $('#txtUnitPrice').focus();

        function remove() {
            $(".red").click(function() {
                $(this).closest("tr").remove();
            });
        }
        remove();
        $(".add-form").on('submit', function(e) {
            e.preventDefault();
            if ($("input[name=cmbItem]").val(0), $("input[name=txtUnitPrice]").val(), $("input[name=txtQty]").val() == "") {
                toastr["error"]("Please fill in all fields !");
                // $(".alert").addClass("active");
                // $(".remove").click(function() {
                //     $(".alert").removeClass("active");
                // });
            } else {
                var item = $("#cmbItem option:selected").text();
                var unitPrice = $("input[name=txtUnitPrice]").val();
                var qty = $("input[name=txtQty]").val();
                var total = unitPrice * qty;

                $(".first-tr").after('<tr><td>' + item + '</td><td>' + parseFloat(unitPrice).toFixed(2) + '</td><td>' + qty + '</td><td>' + parseFloat(total).toFixed(2) + '</td><td class="static"><span class="button red center-items"><i class="fas fa-times"></i></span></td></tr>');
                remove();
                $("input[name=cmbItem], input[name=txtUnitPrice], input[name=txtQty]").val("");
                $("input[name=txtTotalPrice]").val("0.00");


            }
        });
    });
</script>