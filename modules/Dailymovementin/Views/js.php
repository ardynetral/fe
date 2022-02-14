<script type="text/javascript">
	var selectedDate;
	$(document).ready(function() {

		$("#startDate").datepicker('setDate', new Date());
		$("#endDate").datepicker('setDate', new Date());

		$('#timepicker1').timepicker({
			defaultTime: false,
			showMeridian: false,
			explicitMode: false
		});
		$('#timepicker2').timepicker({
			defaultTime: false,
			showMeridian: false,
			explicitMode: false

		});

		$("#startDate").on('changeDate', function(selected) {
			var startDate = new Date(selected.date.valueOf());
			$("#endDate").datepicker('setStartDate', startDate);
			if ($("#startDate").val() > $("#endDate").val()) {
				$("#endDate").val($("#startDate").val());
			}
		});
		// SELECT2
		$('.select-pr').select2();

		$("#printPdf").on("click", function(e) {
			window.open("<?php echo site_url('dailymovementin/reportPdf'); ?>", '_blank', 'height=600,width=900,toolbar=no,directories=no,status=no, menubar=no,scrollbars=no,resizable=no ,modal=yes');
			e.preventDefault();
		});
		$("#printExl").on("click", function(e) {
			window.open("<?php echo site_url('dailymovementin/reportExcel'); ?>");
			e.preventDefault();
		});

	});
</script>