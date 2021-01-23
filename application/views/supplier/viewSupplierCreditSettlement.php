<!-- <style type="text/css">
    table {
        display: block;
        overflow-x: auto;
        white-space: nowrap;
    } 
</style> -->

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper arcadia-main-container ">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>View Supplier Credit Settlement</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Supplier Credit Settlement</a></li>
                        <li class="breadcrumb-item active">View Supplier Credit Settlement</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->

    </section>
    <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Payment Mode :</label>
                            <select class="form-control select2" style="width: 100%;" id="cmbPayMode" name="cmbPayMode">
                                <option value="0" selected hidden>All Payment Modes</option>
                                <?php foreach ($payment_data as $k => $v) { ?>
                                    <option value="<?= $v['intPayModeID'] ?>"><?= $v['vcPayMode'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="cmbsupplier">Supplier</label>
                            <select class="form-control select2" style="width: 100%;" id="cmbsupplier" name="cmbsupplier">
                               <option value="0" selected hidden>All Suppliers</option>
                                <?php foreach ($supplier_data as $k => $v) { ?>
                                    <option value="<?= $v['intSupplierID'] ?>"><?= $v['vcSupplierName'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <!-- Date range -->
                        <div class="form-group">
                            <label>Date Range :</label>

                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="far fa-calendar-alt"></i>
                                    </span>
                                </div>
                                <input type="text" class="form-control float-right" name="daterange">
                            </div>
                            <!-- /.input group -->
                        </div>
                    </div>
                </div>

            </div>
            <div class="card-body">

                <div class="box">
                    <div class="box-body">
                        <!-- <div>
                            Toggle column: <a class="toggle-vis" data-column="0">ID</a> - <a class="toggle-vis" data-column="1">GRN No</a> - <a class="toggle-vis" data-column="2">Office</a> - <a class="toggle-vis" data-column="3">Age</a> - <a class="toggle-vis" data-column="4">Start date</a> - <a class="toggle-vis" data-column="5">Salary</a>
                        </div> -->
                        <table id="manageTable" class="table table-bordered table-striped">
                            <!-- style="display:block !important;" -->
                            <thead>
                                <tr>
                                    <th>Settlement No</th>
                                    <th>Supplier Name</th>
                                    <th>Payment Mode</th>
                                    <th>Paid Amount</th>
                                    <th>Paid Date</th>
                                    <th>Created User</th>
                                    <th>Created Date</th>
                                    <th>Bank</th>
                                    <th>Cheque No</th>
                                    <th>PD Date</th>
                                    <th>Remark</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                        <hr>
                        Color tags : <span class="badge badge-pill badge-warning">Pending Approvals</span> <span class="badge badge-pill badge-light" style="border: 1px #000000 solid;">Approved GRNs</span> <span class="badge badge-pill badge-danger">Rejected GRNs</span>
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
        <!-- /.row -->
    </section>


</div>

<script src="<?php echo base_url('resources/pageJS/viewSupplierCreditSettlement.js') ?>"></script>