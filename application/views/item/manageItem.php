<!-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script> -->

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1>Manage Item</h1>
				</div>
				<!-- <div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="#">Item</a></li>
						<li class="breadcrumb-item active">Manage Item</li>
					</ol>
				</div> -->
			</div>
		</div><!-- /.container-fluid -->
	</section>

	<!-- Main content -->
	<section class="content">
		<!-- Default box -->
		<div class="card">
			<div class="card-header">
				<button type="button" class="btn btn-info btn-flat" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;Add Item</button>
			</div>
			<div class="card-body">

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
					<h4 class="modal-title" id="myModalLabel">Add Item</h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				</div>
				<form role="form" action="<?php echo base_url('Item/create') ?>" method="post" id="createitemForm">
					<div class="modal-body">
						<div class="form-group">
							<label for="txtItemName">Item Name</label>
							<input type="text" class="form-control" id="Item_name" name="Item_name" placeholder="Enter Item Name" autofocus>
						</div>
						<div class="form-group">
							<label>Measure Unit</label>
							<select class="form-control select2" style="width: 100%;" id="measureunit" name="measureunit">

								<option value="0" disabled selected hidden>Select Measure Unit</option>

								<?php foreach ($measureUnit as $row) { ?>
									<option value="<?= $row->intMeasureUnitID ?>"><?= $row->vcMeasureUnit ?></option>
								<?php } ?>

								<!-- <option>Box</option>
							<option>Pcs</option> -->
							</select>
						</div>
					

						<div class="form-group">
							<label for="txtItemName">Item Re-Order Level</label>
							<input type="number" class="form-control" id="Re_Order" name="Re_Order" placeholder="Enter Re-Order Level" min=1>
						</div>
					</div>
					<div class="modal-footer">

						<button type="submit" class="btn btn-success btn-flat"><i class="fas fa-download" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;Save Item</button>
					</div>
			</div>
		</div>
	</div>

</div>
<!-- /.content-wrapper -->


<script src="<?php echo base_url('resources/pageJS/item.js') ?>"></script>