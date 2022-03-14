<script type="text/javascript">
	var selectedDate;
	$(document).ready(function() {
		$("#startDate").datepicker({
			autoclose: true,
			format: 'dd/mm/yyyy'
		});
		$("#startDate").datepicker('setDate', new Date());

		// SELECT2
		$('.select-pr').select2();

		$("#printPdf").on("click", function(e) {
			var prcode = $("#prcode").val();
			var startDate = $("#startDate").val();
			var date_from = startDate.split("/").reverse().join("-");
			window.open("<?php echo site_url('dailyrepairactivity/reportPdf/'); ?>" + prcode +"/"+ date_from, '_blank', 'height=600,width=900,toolbar=no,directories=no,status=no, menubar=no,scrollbars=no,resizable=no ,modal=yes');
			e.preventDefault();
		});
		$("#printExl").on("click", function(e) {
			var prcode = $("#prcode").val();
			var startDate = $("#startDate").val();
			var date_from = startDate.split("/").reverse().join("-");
			window.open("<?php echo site_url('dailyrepairactivity/reportExcel/'); ?>" + prcode +"/"+ date_from);
			e.preventDefault();
		});

	});
</script>