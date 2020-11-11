r<style>
    .first-tr {
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

    .static {
        position: static !important;
    }

    .center-items {
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
                    <h1>Approval Request Item</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Request</a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url("Request/ViewRequest"); ?>">View Request</a></li>
                        <li class="breadcrumb-item active">Edit Request Item</li>

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
                <form role="form" class="add-form" method="post" action="<?= base_url('request/ApprovalOrRejectRequestItems/' . $request_header_data['intRequestHeaderID']) ?>" id="approvalOrRejectRequestItems">
                    <!-- <form role="form" class="add-form" method="post" action="<?= base_url('request/RejectAllRequestItems/' . $request_header_data['intRequestHeaderID']) ?>" id="rejectAllRequestItems"> -->
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="invoice_no">Request No</label>
                                <input type="text" class="form-control" id="request_no" name="request_no" autocomplete="off" required value="<?= $request_header_data['vcRequestNo']; ?>">
                            </div>

                            <div class="form-group col-md-6">
                                <label for="invoice_no">Request Date</label>
                                <input type="text" class="form-control" id="request_date" name="request_date" autocomplete="off" required value="<?= $request_header_data['dtCreatedDate']; ?>">
                            </div>
                        </div> 

                        <table class="table arcadia-table overflow" id="itemTable">
                            <thead>
                                <tr>
                                    <th style="width:400px; text-align:center;">Item</th>
                                    <th style="width: 100px; text-align:center;">Unit</th>
                                    <th style="width: 100px; text-align:center;">Stock Qty</th>
                                    <th style="width: 100px; text-align:center;">Request Qty</th>
                                    <th style="width: 100px; text-align:center;">Action</th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php
                                $row = 0;
                                foreach ($request_detail_data as $k => $v) { ?>
                                    <tr>
                                        <td hidden><input type="text" class="form-control intRequestDetailID disable-typing" name="intRequestDetailID[]" id="intRequestDetailID_<?= $row ?>" value="<?= $v['intRequestDetailID'] ?>" readonly></td>
                                        <td hidden><input type="text" class="form-control itemID disable-typing" name="itemID[]" id="itemID_<?= $row ?>" value="<?= $v['intItemID'] ?>" readonly></td>
                                        <td hidden><input type="text" class="form-control rv disable-typing" name="rv[]" id="rv_<?= $row ?>" value="<?= $v['rv'] ?>" readonly></td>
                                        <td><input type="text" class="form-control itemName disable-typing" name="itemName[]" id="itemName_<?= $row ?>" value="<?= $v['vcItemName'] ?>" readonly></td>
                                        <td><input type="text" class="form-control disable-typing" style="text-align:center;" name="unit[]" id="unit_<?= $row ?>" value="<?= $v['vcMeasureUnit'] ?>" readonly></td>
                                        <td><input type="text" class="form-control disable-typing" style="text-align:center;" name="stockInHand[]" id="stockInHand_<?= $row ?>" value="<?= $v['decMainStock'] ?>" readonly></td>
                                        <td><input type="text" class="form-control disable-typing" style="text-align:right;" name="itemQty[]" id="itemQty_<?= $row ?>" value="<?= $v['decQty'] ?>" readonly></td>
                                        <td class="static">
                                            <?php
                                            if ($v['IsRejected'] == 0 && ($v['IsApproved'] == 0)) {
                                            ?>
                                                <button type="button" class="button red center-items" style="position: relative; float:left;" id="btnReject" onclick="RejectRequestByDetailID(<?= $v['intRequestDetailID'] ?>,<?= $v['intItemID'] ?>,'<?= $v['rv'] ?>')"><i class="fas fa-times"></i></button>
                                                <button type="button" class="button green center-items" style="position: relative; float:right;" id="btnApproval" onclick="ApprovalRequestByDetailID(<?= $v['intRequestDetailID'] ?>,<?= $v['intItemID'] ?>,'<?= $v['rv'] ?>')"><i class="fas fa-check"></i></button>

                                            <?php }

                                            ?>
                                        </td>
                                    </tr>
                                <?php
                                    $row++;
                                } ?>
                            </tbody>
                        </table>

                        <div class="row" style="border-top:1px solid #dee2e6;">
                            <div class="col-8">
                                <p style="color: #c2c7d0; position:absolute; bottom:0;" id="itemCount">Item Count : 0</p>
                            </div>
                            <!-- /.col -->
                            <div class="col-4">
                                <button type="button" id="btnApprovalAll" class="btn btn-lg btn-info btn-flat float-right col-6" style="display: inline-block;"><i class="fas fa-calendar-check"></i>&nbsp;&nbsp;&nbsp;Accept & Issue All</button>
                                <button type="button" id="btnRejectAll" class="btn btn-lg btn-info btn-flat float-right col-6"><i class="fas fa-calendar-times"></i>&nbsp;&nbsp;&nbsp;Reject All</button>
                            </div>
                            <!-- /.col -->
                        </div>
                    <!-- </form> -->
                </form>
            </div>
            <!-- /.card-body -->

        </div>
        <!-- /.card -->

    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<script src="<?php echo base_url('resources/pageJS/approvalRequestItem.js') ?>"></script>