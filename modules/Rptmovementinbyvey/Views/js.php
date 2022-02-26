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


		$("#startDate").on('changeDate', function(selected) {
			var startDate = new Date(selected.date.valueOf());
			$("#endDate").datepicker('setStartDate', startDate);
			if ($("#startDate").val() > $("#endDate").val()) {
				$("#endDate").val($("#startDate").val());
			}
		});
		// SELECT2
		$('.select-debitur').select2();
		$('.select-vessel').select2();

		$("#printPdf").on("click", function(e) {
			e.preventDefault();
			var cucode = $("#cucode").val();
			var startDate = $("#startDate").val();
			var endDate = $("#endDate").val();
			var cpives = $("#cpives").val();
			var date_from = startDate.split("/").reverse().join("-");
			var date_to = endDate.split("/").reverse().join("-");

			window.open("<?php echo site_url('rptmovementinbyvey/reportPdf/'); ?>" + cucode + "/" + date_from + "/" + date_to + "/" + cpives, '_blank', 'height=900,width=600,toolbar=no,directories=no,status=no, menubar=no,scrollbars=no,resizable=no ,modal=yes');
		});



		$("#printExl").on("click", function(e) {
			e.preventDefault();
			var cucode = $("#cucode").val();
			var startDate = $("#startDate").val();
			var endDate = $("#endDate").val();
			var cpives = $("#cpives").val();
			var date_from = startDate.split("/").reverse().join("-");
			var date_to = endDate.split("/").reverse().join("-");


			let date_from1 = date_from.toString();
			window.open("<?php echo site_url('rptmovementinbyvey/reportExcel/'); ?>" + cucode + "/" + date_from + "/" + date_to + "/" + cpives, '_blank');
		});

	});
</script>