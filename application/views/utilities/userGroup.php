<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>User Group</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Utilities</a></li>
                        <li class="breadcrumb-item active">User Group</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <?php if (in_array('createGroup', $user_permission) || $isAdmin) { ?>
                    <button type="button" class="btn btn-info btn-flat" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;Add User Group</button>
                <?php } ?>
            </div>
            <div class="card-body">
                <?php echo validation_errors(); ?>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->

    </section>
    <!-- Main content end -->

    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Add User Group</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <form role="form" action="<?php echo base_url('Utilities/createUserGroup') ?>" method="post" id="createUserGroupForm">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="group_name">User Group Name</label>
                            <input type="text" class="form-control" id="group_name" name="group_name" placeholder="Enter User Group Name">
                        </div>
                        <div class="form-group">
                            <label for="permission">Permission</label>

                            <table class="table table-responsive">
                                <thead>
                                    <tr>
                                        <th width="100%"></th>
                                        <th>Create</th>
                                        <th>Edit</th>
                                        <th>View</th>
                                        <th>Delete</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Users</td>
                                        <td class="content-center"><input type="checkbox" name="permission[]" id="permission" value="createUser" class="minimal"></td>
                                        <td class="content-center"><input type="checkbox" name="permission[]" id="permission" value="editUser" class="minimal"></td>
                                        <td class="content-center"><input type="checkbox" name="permission[]" id="permission" value="viewUser" class="minimal"></td>
                                        <td class="content-center"><input type="checkbox" name="permission[]" id="permission" value="deleteUser" class="minimal"></td>
                                    </tr>
                                    <tr>
                                        <td>User Groups</td>
                                        <td class="content-center"><input type="checkbox" name="permission[]" id="permission" value="createUserGroup" class="minimal"></td>
                                        <td class="content-center"><input type="checkbox" name="permission[]" id="permission" value="editUserGroup" class="minimal"></td>
                                        <td class="content-center"><input type="checkbox" name="permission[]" id="permission" value="viewUserGroup" class="minimal"></td>
                                        <td class="content-center"><input type="checkbox" name="permission[]" id="permission" value="deleteUserGroup" class="minimal"></td>
                                    </tr>
                                    <tr>
                                        <td>Items</td>
                                        <td class="content-center"><input type="checkbox" name="permission[]" id="permission" value="createItem" class="minimal"></td>
                                        <td class="content-center"><input type="checkbox" name="permission[]" id="permission" value="editItem" class="minimal"></td>
                                        <td class="content-center"><input type="checkbox" name="permission[]" id="permission" value="viewItem" class="minimal"></td>
                                        <td class="content-center"><input type="checkbox" name="permission[]" id="permission" value="deleteItem" class="minimal"></td>
                                    </tr>
                                    <tr>
                                        <td>Category</td>
                                        <td class="content-center"><input type="checkbox" name="permission[]" id="permission" value="createCategory" class="minimal"></td>
                                        <td class="content-center"><input type="checkbox" name="permission[]" id="permission" value="updateCategory" class="minimal"></td>
                                        <td class="content-center"><input type="checkbox" name="permission[]" id="permission" value="viewCategory" class="minimal"></td>
                                        <td class="content-center"><input type="checkbox" name="permission[]" id="permission" value="deleteCategory" class="minimal"></td>
                                    </tr>
                                    <tr>
                                        <td>Stores</td>
                                        <td class="content-center"><input type="checkbox" name="permission[]" id="permission" value="createStore" class="minimal"></td>
                                        <td class="content-center"><input type="checkbox" name="permission[]" id="permission" value="updateStore" class="minimal"></td>
                                        <td class="content-center"><input type="checkbox" name="permission[]" id="permission" value="viewStore" class="minimal"></td>
                                        <td class="content-center"><input type="checkbox" name="permission[]" id="permission" value="deleteStore" class="minimal"></td>
                                    </tr>
                                    <tr>
                                        <td>Attributes</td>
                                        <td class="content-center"><input type="checkbox" name="permission[]" id="permission" value="createAttribute" class="minimal"></td>
                                        <td class="content-center"><input type="checkbox" name="permission[]" id="permission" value="updateAttribute" class="minimal"></td>
                                        <td class="content-center"><input type="checkbox" name="permission[]" id="permission" value="viewAttribute" class="minimal"></td>
                                        <td class="content-center"><input type="checkbox" name="permission[]" id="permission" value="deleteAttribute" class="minimal"></td>
                                    </tr>
                                    <tr>
                                        <td>Products</td>
                                        <td class="content-center"><input type="checkbox" name="permission[]" id="permission" value="createProduct" class="minimal"></td>
                                        <td class="content-center"><input type="checkbox" name="permission[]" id="permission" value="updateProduct" class="minimal"></td>
                                        <td class="content-center"><input type="checkbox" name="permission[]" id="permission" value="viewProduct" class="minimal"></td>
                                        <td class="content-center"><input type="checkbox" name="permission[]" id="permission" value="deleteProduct" class="minimal"></td>
                                    </tr>
                                    <tr>
                                        <td>Reports</td>
                                        <td class="content-center"> - </td>
                                        <td class="content-center"> - </td>
                                        <td class="content-center"><input type="checkbox" name="permission[]" id="permission" value="viewReports" class="minimal"></td>
                                        <td class="content-center"> - </td>
                                    </tr>
                                    <tr>
                                        <td>Profile</td>
                                        <td class="content-center"> - </td>
                                        <td class="content-center"> - </td>
                                        <td class="content-center"><input type="checkbox" name="permission[]" id="permission" value="viewProfile" class="minimal"></td>
                                        <td class="content-center"> - </td>
                                    </tr>
                                    <tr>
                                        <td>Setting</td>
                                        <td class="content-center">-</td>
                                        <td class="content-center"><input type="checkbox" name="permission[]" id="permission" value="updateSetting" class="minimal"></td>
                                        <td class="content-center"> - </td>
                                        <td class="content-center"> - </td>
                                    </tr>
                                </tbody>
                            </table>

                        </div>
                    </div>
                    <div class="modal-footer">

                        <button type="submit" class="btn btn-success btn-flat"><i class="fas fa-download" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;Save User Group</button>
                    </div>
            </div>
        </div>
    </div>

</div>
<!-- /.content-wrapper -->


<!-- <script src="<?php echo base_url('resources/pageJS/item.js') ?>"></script> -->