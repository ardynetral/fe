<script type="text/javascript">
	var selectedDate;
	$(document).ready(function() {
		$("#startDate").datepicker({
			autoclose: true,
			format: 'dd/mm/yyyy',
			startDate: '-5y',
		});
		$("#endDate").datepicker({
			autoclose: true,
			format: 'dd/mm/yyyy',
			startDate: '-5y',
		});
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
			e.preventDefault();
			var prcode = $("#prcode").val();
			var startDate = $("#startDate").val();
			var endDate = $("#endDate").val();
			var hour_from = ($("#timepicker1").val() == "") ? "00:00" : $("#timepicker1").val();
			var hour_to = ($("#timepicker2").val() == "") ? "23:59" : $("#timepicker2").val();			

			var date_from = startDate.split("/").reverse().join("-");
			var date_to = endDate.split("/").reverse().join("-");

			//alert(prcode);
			window.open("<?php echo site_url('dailymovementin/reportPdf/'); ?>" + prcode + "/" + date_from + "/" + date_to + "/" + hour_from + "/" + hour_to, '_blank', 'height=900,width=600,toolbar=no,directories=no,status=no, menubar=no,scrollbars=no,resizable=no ,modal=yes');
			//window.open("<?php echo site_url('dailymovementin/reportPdf/'); ?>" + prcode + "/" + date_from, '_blank', 'height=900,width=600,toolbar=no,directories=no,status=no, menubar=no,scrollbars=no,resizable=no ,modal=yes');
		});



		$("#printExl").on("click", function(e) {
			e.preventDefault();
			var prcode = $("#prcode").val();
			var startDate = $("#startDate").val();
			var endDate = $("#endDate").val();
			var hour_from = ($("#timepicker1").val() == "") ? "00:00" : $("#timepicker1").val();
			var hour_to = ($("#timepicker2").val() == "") ? "23:59" : $("#timepicker2").val();	

			var date_from = startDate.split("/").reverse().join("-");
			var date_to = endDate.split("/").reverse().join("-");


			let date_from1 = date_from.toString();
			window.open("<?php echo site_url('dailymovementin/reportExcel/'); ?>" + prcode + "/" + date_from + "/" + date_to + "/" + hour_from + "/" + hour_to, '_blank');
		});

	});
</script>