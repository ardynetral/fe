<script type="text/javascript">
	$(document).ready(function() {
		// SELECT2
		$('.select-cncode').select2();
		// print PDF
		$("#printPdf").on("click", function(e) {
			window.open("<?php echo site_url('did/reportPdf'); ?>", '_blank', 'height=600,width=900,toolbar=no,directories=no,status=no, menubar=no,scrollbars=no,resizable=no ,modal=yes');
			e.preventDefault();
		});
		$("#printExl").on("click", function(e) {
			window.open("<?php echo site_url('did/reportExcel'); ?>");
			e.preventDefault();
		});
	});
</script>