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
                    <h1>Create Receipt</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Receipt</a></li>
                        <li class="breadcrumb-item active">Create Receipt</li>
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
                        <div class="col-md-6">
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label>Receipt Date</label>
                                    <div class="input-group date" id="dtReceiptDate" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input" id="ReceiptDate" name="ReceiptDate" placeholder="Select Receipt Date" style="pointer-events: none !important;" />
                                        <div class="input-group-append" data-target="#dtReceiptDate" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="cmbcustomer">Customer</label>
                                    <select class="form-control select2" style="width: 100%;" id="cmbcustomer" name="cmbcustomer">
                                        <option value="0" disabled selected hidden>Select Customer</option>
                                        <?php foreach ($customer_data as $k => $v) { ?>
                                            <option value="<?= $v['intCustomerID'] ?>"><?= $v['vcCustomerName'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="txtTotalOutstanding">Total Outstanding Amount</label>
                                    <input type="text" class="form-control" id="txtTotalOutstanding" name="txtTotalOutstanding" placeholder="N/A" style="cursor: not-allowed; color:#000000;" disabled />
                                </div>

                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>Pay Mode</label>
                                    <select class="form-control select2" style="width: 100%;" id="cmbPayMode" name="cmbPayMode">
                                        <?php foreach ($payment_data as $k => $v) { ?>
                                            <option value="<?= $v['intPaymentTypeID'] ?>"><?= $v['vcPaymentType'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="txtAmount">Amount</label>
                                    <input type="text" class="form-control" id="txtAmount" name="txtAmount" placeholder="Enter Pay Amount" required />
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>Bank</label>
                                    <select class="form-control select2" style="width: 100%;" id="cmbBank" name="cmbBank">
                                        <option value=" 0" disabled selected hidden>Select Bank</option>
                                        <?php foreach ($payment_data as $k => $v) { ?>
                                            <option value="<?= $v['intPaymentTypeID'] ?>"><?= $v['vcPaymentType'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="txtChequeNo">Cheque Number</label>
                                    <input type="text" class="form-control" id="txtChequeNo" name="txtChequeNo" placeholder="Enter Cheque Number" required />
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>Post-Dated Date</label>
                                    <div class="input-group date" id="dtPDDate" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input" id="PDDate" name="PDDate" placeholder="Select Post-Dated Date" style="pointer-events: none !important;" />
                                        <div class="input-group-append" data-target="#dtPDDate" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="txtRemark">Remark</label>
                                    <input type="text" class="form-control" id="txtRemark" name="txtRemark" autocomplete="off" placeholder="Not Required" />
                                </div>
                            </div>

                        </div>

                    </div>
                    <div class="row">
                        <table class="table arcadia-table" id="itemTable">
                            <thead>
                                <tr>
                                    <th style="text-align:center;">Issue No</th>
                                    <th style="width: 200px; text-align:center;">Total Amount</th>
                                    <th style="width: 200px; text-align:center;">Total Paid Amount</th>
                                    <th style="width: 100px; text-align:center;">Outstranding Amount</th>
                                    <th style="width: 100px; text-align:center;">Pay Amount</th>
                                    <th style="width: 200px; text-align:center;">Balance Amount</th>
                                    <th hidden>rv</th>
                                    <th style="width: 100px; text-align: center;">Action</th>
                                </tr>
                            </thead>
                            <tbody>

                                <tr class="first-tr">
                                    <td class="static" hidden><input type="number" class="form-control" name="txtItemIDx" min="0"></td>
                                    <td class="static">
                                        <select class="form-control select2" style="width: 100%;" id="cmbItem" name="cmbItem"">
                                        <option value=" 0" disabled selected hidden>Select Issue No</option>
                                            <?php foreach ($item_data as $k => $v) {
                                                if ($v['decStockInHand'] > 0) { ?>
                                                    <option value="<?= $v['intItemID'] ?>"><?= $v['vcItemName'] ?></option>
                                                <?php } else { ?>
                                                    <option value="<?= $v['intItemID'] ?>" class="icons_select2" name="icons_select2"><?= $v['vcItemName'] ?></option>
                                            <?php }
                                            } ?>
                                        </select>
                                    </td>
                                    <td class="static"><input type="text" class="form-control add-item" name="txtTotalAmount" id="txtTotalAmount" placeholder="N/A" style="text-align:right;" disabled></td>
                                    <td class="static"><input type="text" class="form-control add-item" name="txtPaidAmount" id="txtPaidAmount" placeholder="N/A" style="text-align:right;" disabled></td>
                                    <td class="static"><input type="text" class="form-control add-item" name="txtOutstrandingAmount" id="txtOutstrandingAmount" placeholder="N/A" style="text-align:center;" disabled></td>
                                    <td class="static"><input type="text" class="form-control only-decimal add-item" name="txtPayAmount" id="txtPayAmount" placeholder="0.00" style="text-align:right;"></td>
                                    <td class="static"><input type="text" class="form-control add-item" name="txtBalanceAmount" id="txtBalanceAmount" placeholder="N/A" style="text-align:right;" disabled></td>
                                    <td class="static" hidden><input type="text" class="form-control" name="txtRv" id="txtRv"></td>
                                    <td class="static"><button type="button" class="button green center-items" id="btnAddToGrid"><i class="fas fa-plus"></i></button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <button type="button" id="btnSubmit" class="btn btn-lg btn-info btn-flat float-right"><i class="fas fa-calendar-check"></i>&nbsp;&nbsp;&nbsp;Submit</button>
                </form>
            </div>
            <!-- /.card-body -->

        </div>
        <!-- /.card -->

    </section>
    <!-- /.content -->
</div>


<script src="<?php echo base_url('resources/pageJS/createRecepit.js') ?>"></script>
<script>
    function formatState(state) {
        if (!state.element) return;
        var os = $(state.element).attr('lowstock');
        return $('<span lowstock="' + os + '">' + state.text + '</span>');
    }

    $(document).ready(function() {
        $('select').select2({
            templateResult: formatState
        });
    });
</script>