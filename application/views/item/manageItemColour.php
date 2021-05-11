<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Manage Item Colour
        </h1>

    </section>

    <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-md-12 col-xs-12">
                <div class="card">
                    <div class="card-header">

                        <button type="button" class="btn btn-info btn-flat" data-toggle="modal" data-target="#addColourModal"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;Add Colour</button>

                    </div>
                    <div class="card-body">

                        <div class="box">
                            <div class="box-body">
                                <table id="manageTable" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Colour Name</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>

                                </table>
                            </div>
                            <!-- /.box-body -->
                        </div>
                        <!-- /.box -->
                    </div>
                    <!-- col-md-12 -->
                </div>
                <!-- /.row -->
    </section>

    <div class="modal fade" id="addColourModal" tabindex="-1" role="dialog" aria-labelledby="addColourModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addColourModal">Add Colour</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form role="form" action="<?php echo base_url('item/createItemColour') ?>" method="post" id="createColourForm">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="Colour_name">Colour Name</label>
                            <input type="text" class="form-control" id="colour_name" name="colour_name" placeholder="Colour Name" autocomplete="off">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success btn-flat"><i class="fas fa-download" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;Save Colour</button>
                    </div>

                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

</div>
<!-- /.content-wrapper -->
<!-- edit Colour modal -->
<div class="modal fade" id="editColourModal" tabindex="-1" role="dialog" aria-labelledby="editColourModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editColourModal">Edit Colour</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form role="form" action="<?php echo base_url('item/updateItemColour') ?>" method="post" id="updateColourForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="Colour_name">Colour Name</label>
                        <input type="text" class="form-control" id="edit_Colour_name" name="edit_Colour_name" placeholder="Enter Colour Name" autocomplete="off">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success btn-flat"><i class="fas fa-download" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;Update Colour</button>
                </div>

            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

</div>
<!-- /.content-wrapper -->

<!-- remove brand modal -->
<?php if (in_array('deleteColour', $user_permission) || $isAdmin) { ?>
    <div class="modal fade" tabindex="-1" role="dialog" id="removeColourModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="removeColourModal">Delete Colour</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form role="form" action="<?php echo base_url('item/removeItemColour') ?>" method="post" id="removeColourForm">
                    <div class="modal-body">
                        <p>Do you really want to remove?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success btn-flat"><i class="fas fa-download" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;Delete Colour</button>
                    </div>
                </form>


            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
<?php } ?>

<script src="<?php echo base_url('resources/pageJS/manageItemColour.js') ?>"></script>