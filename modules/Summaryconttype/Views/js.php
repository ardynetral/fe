<script type="text/javascript">
	var selectedDate;
	// prcode, date_from,  hour_from, hour_to
	$("#startDate").datepicker({
		autoclose: true,
		format: 'dd/mm/yyyy'
	});
	$("#startDate").datepicker('setDate', new Date());
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

	$(document).ready(function() {
		$("#startDate").datepicker('setDate', new Date());
		// SELECT2
		$('.select-pr').select2();
		$("#printPdf").on("click", function(e) {
			var prcode = $("#prcode").val();
			var startDate = $("#startDate").val();
			var hour_from = $("#timepicker1").val();
			var hour_to = $("#timepicker2").val();
			var date_from = startDate.split("/").reverse().join("-");
			window.open("<?php echo site_url('summaryconttype/reportPdf/'); ?>" + prcode + "/" + date_from + "/" + hour_from + "/" + hour_to, '_blank', 'height=600,width=900,toolbar=no,directories=no,status=no, menubar=no,scrollbars=no,resizable=no ,modal=yes');
			e.preventDefault();
		});
		$("#printExl").on("click", function(e) {
			var prcode = $("#prcode").val();
			var startDate = $("#startDate").val();
			var hour_from = $("#timepicker1").val();
			var hour_to = $("#timepicker2").val();
			var date_from = startDate.split("/").reverse().join("-");
			window.open("<?php echo site_url('summaryconttype/reportExcel/'); ?>" + prcode + "/" + date_from + "/" + hour_from + "/" + hour_to);
			e.preventDefault();
		});

	});
</script>