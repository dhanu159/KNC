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



    .first-tr {
        /* background-color: #c2c7d0; */
        border: 2px solid #3d9970;
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
        background: #3d9970;
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

    input[type=text]:disabled {
        background: #ffffff;
        border: 1px solid #ced4da !important;

    }

    input[type=text]:read-only {
        background: #ffffff;
        border-color: #ffffff;
    }
</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper arcadia-main-container">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Create Issue</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Issue</a></li>
                        <li class="breadcrumb-item active">Create Issue</li>
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
                <form role="form" class="add-form" method="post" action="<?= base_url('Issue/SaveIssue') ?>" id="createIssue">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="customer">Customer</label>
                            <select class="form-control select2" style="width: 100%;" id="cmbcustomer" name="cmbcustomer">
                                <option value="0" disabled selected hidden>Select Customer</option>
                                <?php foreach ($customer_data as $k => $v) { ?>
                                    <option value="<?= $v['intCustomerID'] ?>"><?= $v['vcCustomerName'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="credit_limit">Credit Limit</label>
                            <input type="text" class="form-control" id="credit_limit" name="credit_limit" autocomplete="off" required />
                        </div>
                        <div class="form-group col-md-3">
                            <label for="credit_limit">Available Credit</label>
                            <input type="text" class="form-control" id="available_limit" name="available_limit" autocomplete="off" required />
                        </div>
                    </div>
                    <div class="row">
                        <!-- <div class="btn-group col-md-6" data-toggle="buttons">
                            <label for="customer">Payment Mode </label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option1">
                                <label class="form-check-label" for="inlineRadio1">1</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2">
                                <label class="form-check-label" for="inlineRadio2">2</label>
                            </div> 
                        </div> -->
                        <div class="form-group col-md-6 col-sm-12">
                            
                            <label for="customer">Payment Mode </label>
                            <!-- <div class="col-sm-10"> -->
                                <div class="form-check col-xs-2">
                                    <input class="form-check-input" type="radio" name="paymentmode" id="cash" value="1" checked>
                                    <label class="form-check-label" for="gridRadios1">
                                        Cash
                                    </label>
                                </div>
                                <div class="form-check col-xs-2">
                                    <input class="form-check-input" type="radio" name="paymentmode" id="credit" value="2">
                                    <label class="form-check-label" for="gridRadios2">
                                        Credit
                                    </label>
                                </div>

                            <!-- </div> -->
                           
                        </div>
                        <div class="form-group col-md-6">
                            <label>Issued Date</label>
                            <div class="input-group date" id="dtReceivedDate" data-target-input="nearest">
                                <input type="text" class="form-control datetimepicker-input" id="issuedDate" name="issuedDate" placeholder="Select Received Date" style="pointer-events: none !important;" />
                                <div class="input-group-append" data-target="#dtReceivedDate" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <table class="table arcadia-table" id="itemTable">
                        <thead>
                            <tr>
                                <th hidden>Item ID</th>
                                <th style="text-align:center;">Item</th>
                                <th style="width: 200px; text-align:center;">Unit Price</th>
                                <th style="width: 200px; text-align:center;">Stock Qty</th>
                                <th style="width: 100px; text-align:center;">Unit</th>
                                <th style="width: 100px; text-align:center;">Qty</th>
                                <th style="width: 200px; text-align:center;">Total Price</th>
                                <th hidden>rv</th>
                                <th style="width: 100px; text-align: center;">Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            <tr class="first-tr">
                                <td class="static" hidden><input type="number" class="form-control" name="txtItemID" min="0"></td>
                                <td class="static">
                                    <!-- <input type="text" class="form-control" name="txtItem"> -->
                                    <select class="form-control select2" style="width: 100%;" id="cmbItem" name="cmbItem" onchange="getMeasureUnitByItemID();">
                                        <option value=" 0" disabled selected hidden>Select Item</option>
                                        <?php foreach ($item_data as $k => $v) { ?>
                                            <option value="<?= $v['intItemID'] ?>"><?= $v['vcItemName'] ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                                <td class="static"><input type="text" class="form-control only-decimal add-item" name="txtUnitPrice" id="txtUnitPrice" style="text-align:right;" disabled></td>
                                <td class="static"><input type="text" class="form-control only-decimal add-item" name="txtStockQty" id="txtStockQty" style="text-align:right;" disabled></td>
                                <td class="static"><input type="text" class="form-control add-item" name="txtMeasureUnit" id="txtMeasureUnit" style="text-align:center;" disabled></td>
                                <td class="static"><input type="text" class="form-control only-decimal add-item" name="txtQty" id="txtQty" style="text-align:right;"></td>
                                <td class="static"><input type="text" class="form-control only-decimal" name="txtTotalPrice" id="txtTotalPrice" placeholder="0.00" style="text-align:right;" disabled></td>
                                <td class="static" hidden><input type="text" class="form-control" name="txtRv" id="txtRv"></td>
                                <td class="static"><button type="button" class="button green center-items" id="btnAddToGrid"><i class="fas fa-plus"></i></button></td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="row" style="border-top:1px solid #dee2e6;">
                        <div class="col-6">
                            <p style="color: #c2c7d0; position:absolute; bottom:0;" id="itemCount">Item Count : 0</p>
                        </div>
                        <!-- /.col -->
                        <div class="col-6" style="padding-right:100px;">
                            <div class="table-responsive">
                                <table class="table">
                                    <tr>
                                        <th style="border-top:0 !important; width:180px;">Sub Total:</th>
                                        <td>
                                            <input type="text" class="form-control" style="font-weight: 600; text-align:right;" id="subTotal" name="subTotal" placeholder="0.00" readonly>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th style="border-top:0 !important;">Discount:</th>
                                        <td style="border-top:0 !important;">
                                            <input type="text" class="form-control only-decimal" name="txtDiscount" id="txtDiscount" placeholder="0.00" style="font-weight: 600; text-align:right;">
                                        </td>
                                    </tr>
                                    <tr style="border-top:2px solid #dee2e6; border-bottom:2px solid #dee2e6;">
                                        <th style="font-size:1.5em;">Grand Total:</th>
                                        <td>
                                            <input type="text" class="form-control" style="font-weight: 600; text-align:right; font-size:1.5em;" id="grandTotal" name="grandTotal" placeholder="0.00" readonly>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <button type="button" id="btnSubmit" class="btn btn-lg btn-info btn-flat float-right"><i class="fas fa-calendar-check"></i>&nbsp;&nbsp;&nbsp;Submit</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>
            </div>
            <!-- /.card-body -->

        </div>
        <!-- /.card -->

    </section>
    <!-- /.content -->
</div>


<script src="<?php echo base_url('resources/pageJS/createIssue.js') ?>"></script>