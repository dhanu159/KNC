<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, OPTIONS");
?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>K N C | Business Management System</title>
	<!-- Tell the browser to be responsive to screen width -->
	<meta name="viewport" content="width=device-width, initial-scale=1">


	<!-- Font Awesome -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css">
	<!-- Ionicons -->
	<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
	<!-- Select2 -->
	<link rel="stylesheet" href="<?php echo base_url('resources/tempcss/select2.min.css') ?>">
	<!-- Theme style -->
	<link rel="stylesheet" href="<?php echo base_url('resources/tempcss/adminlte.min.css') ?>">
	<!-- daterange picker -->
	<link rel="stylesheet" href="<?php echo base_url('resources/tempcss/daterangepicker.css') ?>">
	<!-- Google Font: Source Sans Pro -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700">

	<!-- Common -->
	<link rel="stylesheet" href="<?php echo base_url('resources/tempcss/common.css') ?>">
	<!-- Toster message -->
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
	<!-- jquery.dataTables -->
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.css">
	<!-- Tempusdominus Bbootstrap 4 -->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('resources/tempcss/tempusdominus-bootstrap-4.min.css') ?>">


	<script src="<?php echo base_url('resources/tempjs/jquery.min.js') ?>"></script>

	<!-- <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script> -->

	<script type="text/javascript" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>

	<script type="text/javascript" src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

	<script>
		$.fn.DataTable.ext.pager.numbers_length = 5;
		var base_url = "<?php echo base_url(); ?>";
	</script>

</head>

<body onload="Preloader()" class="hold-transition sidebar-mini" style="font-size: 0.9rem !important;">
	<!-- Preloader Start-->
	<div id="Preloader">
		<div class="dl">
			<div class="dl__container">
				<div class="dl__corner--top"></div>
				<div class="dl__corner--bottom"></div>
			</div>
			<div class="dl__square"></div>
		</div>
	</div>
	<!-- Preloader End -->


	<!-- Site wrapper -->
	<div class="wrapper">
		<!-- Navbar -->
		<nav class="main-header navbar navbar-expand navbar-white navbar-light">
			<!-- Left navbar links -->
			<ul class="navbar-nav">
				<li class="nav-item">
					<a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
				</li>
			</ul>
			<!-- Right navbar links -->
			<ul class="navbar-nav ml-auto">
				<!-- Messages Dropdown Menu -->
				<li class="nav-item dropdown">
					<a class="nav-link" data-toggle="dropdown" href="#">
						<i class="far fa-comments"></i>
						<span class="badge badge-danger navbar-badge">3</span>
					</a>
					<div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
						<a href="#" class="dropdown-item">
							<!-- Message Start -->
							<div class="media">
								<img src="<?= base_url('resources/img/user1-128x128.jpg') ?>" alt="User Avatar" class="img-size-50 mr-3 img-circle">
								<div class="media-body">
									<h3 class="dropdown-item-title">
										Brad Diesel
										<span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
									</h3>
									<p class="text-sm">Call me whenever you can...</p>
									<p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
								</div>
							</div>
							<!-- Message End -->
						</a>
						<div class="dropdown-divider"></div>
						<a href="#" class="dropdown-item">
							<!-- Message Start -->
							<div class="media">
								<img src="<?= base_url('resources/img/user1-128x128.jpg') ?>" alt="User Avatar" class="img-size-50 mr-3 img-circle">
								<div class="media-body">
									<h3 class="dropdown-item-title">
										John Pierce
										<span class="float-right text-sm text-muted"><i class="fas fa-star"></i></span>
									</h3>
									<p class="text-sm">I got your message bro</p>
									<p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
								</div>
							</div>
							<!-- Message End -->
						</a>
						<div class="dropdown-divider"></div>
						<a href="#" class="dropdown-item">
							<!-- Message Start -->
							<div class="media">
								<img src="<?= base_url('resources/img/user1-128x128.jpg') ?>" alt="User Avatar" class="img-size-50 mr-3 img-circle">
								<div class="media-body">
									<h3 class="dropdown-item-title">
										Nora Silvester
										<span class="float-right text-sm text-warning"><i class="fas fa-star"></i></span>
									</h3>
									<p class="text-sm">The subject goes here</p>
									<p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
								</div>
							</div>
							<!-- Message End -->
						</a>
						<div class="dropdown-divider"></div>
						<a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
					</div>
				</li>
				<!-- Notifications Dropdown Menu -->
				<li class="nav-item dropdown">
					<a class="nav-link" data-toggle="dropdown" href="#">
						<i class="far fa-bell"></i>
						<span class="badge badge-warning navbar-badge">15</span>
					</a>
					<div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
						<span class="dropdown-item dropdown-header">15 Notifications</span>
						<div class="dropdown-divider"></div>
						<a href="#" class="dropdown-item">
							<i class="fas fa-envelope mr-2"></i> 4 new messages
							<span class="float-right text-muted text-sm">3 mins</span>
						</a>
						<div class="dropdown-divider"></div>
						<a href="#" class="dropdown-item">
							<i class="fas fa-users mr-2"></i> 8 friend requests
							<span class="float-right text-muted text-sm">12 hours</span>
						</a>
						<div class="dropdown-divider"></div>
						<a href="#" class="dropdown-item">
							<i class="fas fa-file mr-2"></i> 3 new reports
							<span class="float-right text-muted text-sm">2 days</span>
						</a>
						<div class="dropdown-divider"></div>
						<a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
					</div>
				</li>

			</ul>
		</nav>
		<!-- /.navbar -->
		<!-- Main Sidebar Container -->
		<aside class="main-sidebar sidebar-dark-primary elevation-4">
			<!-- Brand Logo -->
			<!-- <a href="#" class="brand-link">
				<img src="<?php echo base_url('resources/img/AdminLTELogo.png') ?>" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
				<span class="brand-text font-weight-light">AdminLTE 3</span>
			</a> -->


			<a href="#" class="brand-link">
				<div class="user-panel pb-3 d-flex">
					<!-- <div class="image">
						<img src="<?php echo base_url('resources/img/AdminLTELogo.png') ?>" alt="AdminLTE Logo" class="img-circle elevation-2" style="opacity: .8">
					</div> -->
					<div class="info">
						<span class="brand-text font-weight-light d-block">KNC</span>
						<span class="brand-text font-weight-light d-block" style="font-size: 0.8em;">Business Management</span>
						<span class="brand-text font-weight-light d-block" style="font-size: 0.8em;">System</span>
					</div>
				</div>
			</a>

			<!-- Sidebar -->
			<div class="sidebar">

				<!-- Sidebar Menu -->
				<nav class="mt-2">
					<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">



						<li class="nav-item has-treeview">
							<a href="#" class="nav-link">
								<div class="user-panel pb-3 mb-3 d-flex">
									<div class="image">
										<img src="<?php echo base_url('resources/img/user2-160x160.jpg') ?>" class="img-circle elevation-2" alt="User Image">
									</div>
									<div class="info">
										<span href="#" class="d-block"><?= $_SESSION['full_name'] ?>
											<span class="d-block small"><?= $_SESSION['group_name'] ?></span>
										</span>
									</div>
								</div>
							</a>
							<ul class="nav nav-treeview">
								<li class="nav-item">
									<a href="<?php echo base_url('Customer/addCustomer') ?>" class="nav-link">
										<i class="fas fa-user-edit"></i>
										<p>&nbsp;&nbsp;Change Account Details</p>
									</a>
								</li>

							</ul>
						</li>

						<li class="nav-item">
							<a href="<?= base_url('dashboard') ?>" class="nav-link">
								<i class="fas fa-tachometer-alt"></i>
								<p>&nbsp;&nbsp;&nbsp;Dashboard</p>
							</a>
						</li>

						<li class="nav-item has-treeview">
							<a href="#" class="nav-link">
								<i class="fas fa-tools"></i>
								<p>&nbsp;&nbsp;&nbsp;Utilities
									<i class="right fas fa-angle-left"></i>
								</p>
							</a>
							<ul class="nav nav-treeview">
								<li class="nav-item">
									<a href="<?php echo base_url('User/index') ?>" class="nav-link">
										<i class="fas fa-user"></i>
										<p>&nbsp;&nbsp;&nbsp;User</p>
									</a>
								</li>
								<li class="nav-item">
									<a href="<?= base_url('Utilities/UserGroup') ?>" class="nav-link">
										<i class="fas fa-users"></i>
										<p>&nbsp;&nbsp;User Group</p>
									</a>
								</li>
								<li class="nav-item">
									<a href="<?= base_url('Utilities/MeasureUnit') ?>" class="nav-link">
										<i class="fas fa-balance-scale-right"></i>
										<p>&nbsp;&nbsp;Measure Unit</p>
									</a>
								</li>
							</ul>
						</li>
						<li class="nav-item">
							<a href="<?php echo base_url('Customer/index') ?>" class="nav-link">
								<i class="fa fa-users" aria-hidden="true"></i>
								<p>&nbsp;&nbsp;Customer</p>
							</a>
						</li>

						<li class="nav-item">
							<a href="<?php echo base_url('Supplier/index') ?>" class="nav-link">
								<i class="fa fa-truck" aria-hidden="true"></i>
								<p>&nbsp;&nbsp;Supplier</p>
							</a>
						</li>

						<li class="nav-item">
							<a href="<?php echo base_url('Branch/index') ?>" class="nav-link">
								<i class="fas fa-store"></i>
								<p>&nbsp;&nbsp;Branch</p>
							</a>
						</li>

						<li class="nav-item has-treeview">
							<a href="<?php echo base_url('Item/index') ?>" class="nav-link">
								<i class="fa fa-industry" aria-hidden="true"></i>
								<p>&nbsp;&nbsp;&nbsp;Item</p>
							</a>
						</li>
						<li class="nav-item has-treeview">
							<a href="#" class="nav-link">
								<i class="fas fa-layer-group"></i>
								<p>&nbsp;&nbsp;&nbsp;Request
									<i class="right fas fa-angle-left"></i>
								</p>
							</a>
							<ul class="nav nav-treeview">
								<li class="nav-item">
									<a href="<?php echo base_url('Request/RequestItem') ?>" class="nav-link">
										<i class="fas fa-cart-plus"></i>
										<p>&nbsp;&nbsp;Request Item</p>
									</a>
								</li>
								<li class="nav-item">
									<a href="<?php echo base_url('Request/ViewRequest') ?>" class="nav-link">
										<i class="fas fa-search"></i>
										<p>&nbsp;&nbsp;View Request</p>
									</a>
								</li>
							</ul>
						</li>

						<li class="nav-item has-treeview">
							<a href="#" class="nav-link">
								<i class="fas fa-layer-group"></i>
								<p>&nbsp;&nbsp;&nbsp;Stock
									<i class="right fas fa-angle-left"></i>
								</p>
							</a>
							<ul class="nav nav-treeview">
								<li class="nav-item">
									<a href="<?php echo base_url('GRN/CreateGRN') ?>" class="nav-link">
										<i class="fas fa-cart-plus"></i>
										<p>&nbsp;&nbsp;Create GRN</p>
									</a>
								</li>
								<li class="nav-item">
									<a href="<?php echo base_url('GRN/ViewGRN') ?>" class="nav-link">
										<i class="fas fa-search"></i>
										<p>&nbsp;&nbsp;View GRN</p>
									</a>
								</li>
							</ul>
						</li>
						<li class="nav-item">
							<a href="<?= base_url('auth/logout') ?>" class="nav-link">
								<i class="fas fa-power-off"></i>
								<p>&nbsp;&nbsp;&nbsp;Logout</p>
							</a>
						</li>
						<!-- <li class="nav-item">
							<a href="<?php echo base_url('User/forgotPassword') ?>" class="nav-link">
								<i class="far fa-circle nav-icon"></i>
								<p>Forgot Password</p>
							</a>
						</li> -->
						</li>
					</ul>
				</nav>
				<!-- /.sidebar-menu -->
			</div>
			<!-- /.sidebar -->
		</aside>