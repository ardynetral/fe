<script type="text/javascript">
	var selectedDate;
	$(document).ready(function() {


		$("#printPdf").on("click", function(e) {
			window.open("<?php echo site_url('statusreport/reportPdf'); ?>", '_blank', 'height=600,width=900,toolbar=no,directories=no,status=no, menubar=no,scrollbars=no,resizable=no ,modal=yes');
			e.preventDefault();
		});
		$("#printExl").on("click", function(e) {
			window.open("<?php echo site_url('statusreport/reportExcel'); ?>");
			e.preventDefault();
		});

	});
</script>