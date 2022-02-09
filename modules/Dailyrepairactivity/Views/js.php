<script type="text/javascript">
	var selectedDate;
	$(document).ready(function() {

		$(".tanggal").datepicker({
			autoclose: true,
		});

		// SELECT2
		$('.select-pr').select2();

		$("#printPdf").on("click", function(e) {
			window.open("<?php echo site_url('dailyrepairactivity/reportPdf'); ?>", '_blank', 'height=600,width=900,toolbar=no,directories=no,status=no, menubar=no,scrollbars=no,resizable=no ,modal=yes');
			e.preventDefault();
		});
		$("#printExl").on("click", function(e) {
			window.open("<?php echo site_url('dailyrepairactivity/reportExcel'); ?>");
			e.preventDefault();
		});

	});
</script>