<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Manage Measure Unit
        </h1>

    </section>

    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <!-- Small boxes (Stat box) -->
        <div class="card">
            <div class="card-header">
            <?php if (in_array('createMeasureUnit', $user_permission) || $isAdmin) { ?>
                <button type="button" class="btn btn-info btn-flat" data-toggle="modal" data-target="#addMeasureUnitModal"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;Add Measure Unit</button>

				<?php } ?>
            </div>
            <div class="card-body">

                <div class="box">
                    <div class="box-body">
                        <table id="manageTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Measure Unit</th>
                                    <th style="width: 300px;">Action</th>
                                </tr>
                            </thead>

                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
        <!-- /.row -->
    </section>

    <div class="modal fade" id="addMeasureUnitModal" tabindex="-1" role="dialog" aria-labelledby="addMeasureUnitModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addMeasureUnitModal">Add Measure Unit</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- Modal -->
                <form role="form" action="<?php echo base_url('Utilities/createMeasureUnit') ?>" method="post" id="createunitForm">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="Unit_name">Unit Name</label>
                            <input type="text" class="form-control" id="Unit_name" name="Unit_name" placeholder="Enter Unit Name" autofocus autocomplete="off">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success btn-flat"><i class="fas fa-download" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;Save Measure Unit</button>
                    </div>

            </div>
            </form>
        </div>
    </div><!-- /.modal-content -->
</div>
<!-- edit MeasureUni modal -->
<!-- edit Branch modal -->
<div class="modal fade" id="editMeasureUnitModal" tabindex="-1" role="dialog" aria-labelledby="editMeasureUnitModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editMeasureUnitModal">Edit Branch</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form role="form" action="<?php echo base_url('Utilities/updateMeasureUnit') ?>" method="post" id="updateMeasureUnitForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="branch_name">Unit Name</label>
                        <input type="text" class="form-control" id="edit_unit_name" name="edit_unit_name" placeholder="Enter Unit Name" autocomplete="off">
                    </div>

                </div>

                <div class="modal-footer">
                <button type="submit"  class="btn btn-success btn-flat"><i class="fas fa-download" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;Update Unit</button>
                </div>

            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

</div>
<!-- /.content-wrapper -->
<!-- /.content-wrapper -->


<!-- remove brand modal -->
<?php if (in_array('deleteMeasureUnit', $user_permission) || $isAdmin) { ?>
<div class="modal fade" tabindex="-1" role="dialog" id="removeMeasureUnithModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="removeMeasureUnithModal">Delete Unit</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form role="form" action="<?php echo base_url('Utilities/removeMeasureUnit') ?>" method="post" id="removeMeasureUnitForm">
                <div class="modal-body">
                    <p>Do you really want to remove?</p>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success btn-flat"><i class="fas fa-download" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;Delete Unit</button>
                </div>
            </form>


        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<?php } ?>

<script src="<?php echo base_url('resources/pageJS/measureunit.js') ?>"></script>