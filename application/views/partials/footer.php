<!-- <footer class="main-footer">
	<div class="float-right d-none d-sm-block">
		<b>Version</b> 3.0.5
	</div>
	<strong>Copyright &copy; 2020 <a href="Arcadia360.lk">Arcadia360.lk</a>.</strong> All rights
	reserved.
</footer> -->

<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
	<!-- Control sidebar content goes here -->
</aside>
<!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="<?php echo base_url('resources/tempjs/jquery.min.js') ?>"></script>
<!-- Bootstrap 4 -->
<script src="<?php echo base_url('resources/tempjs/bootstrap.bundle.min.js') ?>"></script>
<!-- Select2 -->
<script src="<?php echo base_url('resources/tempjs/select2.full.min.js') ?>"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url('resources/tempjs/adminlte.min.js') ?>"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?php echo base_url('resources/tempjs/demo.js') ?>"></script>

<!-- 2020-09-17 Viraj -->


<!-- Page script -->
<script>
	var preLoader = document.getElementById('Preloader');

	function Preloader() {
		preLoader.style.display='none';
	}

	$(function() {
		//Initialize Select2 Elements
		$('.select2').select2()

		//Initialize Select2 Elements
		$('.select2bs4').select2({
			theme: 'bootstrap4'
		})

		// //Datemask dd/mm/yyyy
		// $('#datemask').inputmask('dd/mm/yyyy', {
		// 	'placeholder': 'dd/mm/yyyy'
		// })
		// //Datemask2 mm/dd/yyyy
		// $('#datemask2').inputmask('mm/dd/yyyy', {
		// 	'placeholder': 'mm/dd/yyyy'
		// })
		// //Money Euro
		// $('[data-mask]').inputmask()

		// //Date range picker
		// $('#reservationdate').datetimepicker({
		// 	format: 'L'
		// });
		// //Date range picker
		// $('#reservation').daterangepicker()
		// //Date range picker with time picker
		// $('#reservationtime').daterangepicker({
		// 	timePicker: true,
		// 	timePickerIncrement: 30,
		// 	locale: {
		// 		format: 'MM/DD/YYYY hh:mm A'
		// 	}
		// })
		// //Date range as a button
		// $('#daterange-btn').daterangepicker({
		// 		ranges: {
		// 			'Today': [moment(), moment()],
		// 			'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
		// 			'Last 7 Days': [moment().subtract(6, 'days'), moment()],
		// 			'Last 30 Days': [moment().subtract(29, 'days'), moment()],
		// 			'This Month': [moment().startOf('month'), moment().endOf('month')],
		// 			'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
		// 		},
		// 		startDate: moment().subtract(29, 'days'),
		// 		endDate: moment()
		// 	},
		// 	function(start, end) {
		// 		$('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
		// 	}
		// )

		// //Timepicker
		// $('#timepicker').datetimepicker({
		// 	format: 'LT'
		// })

		// //Bootstrap Duallistbox
		// $('.duallistbox').bootstrapDualListbox()

		// //Colorpicker
		// $('.my-colorpicker1').colorpicker()
		// //color picker with addon
		// $('.my-colorpicker2').colorpicker()

		// $('.my-colorpicker2').on('colorpickerChange', function(event) {
		// 	$('.my-colorpicker2 .fa-square').css('color', event.color.toString());
		// });

		// $("input[data-bootstrap-switch]").each(function() {
		// 	$(this).bootstrapSwitch('state', $(this).prop('checked'));
		// });

	});
</script>

</body>

</html>