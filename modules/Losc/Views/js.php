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
		$('.select-length').select2();
		$('.select-billtype').select2();
		$("#printPdf").on("click", function(e) {
			var prcode = $("#prcode").val();
			var startDate = $("#startDate").val();
			var date_from = startDate.split("/").reverse().join("-");
			var los = $("#los").val();
			var clength = $("#length").val();
			var ctcode = $("#ctcode").val();
			var condition = $("#condition").val();
			window.open("<?php echo site_url('losc/reportPdf/'); ?>" + prcode +"/"+ date_from +"/"+ los +"/"+ clength +"/"+ ctcode +"/"+ condition, '_blank', 'height=600,width=900,toolbar=no,directories=no,status=no, menubar=no,scrollbars=no,resizable=no ,modal=yes');
			e.preventDefault();
		});
		$("#printExl").on("click", function(e) {
			var prcode = $("#prcode").val();
			var startDate = $("#startDate").val();
			var date_from = startDate.split("/").reverse().join("-");
			var los = $("#los").val();
			var clength = $("#length").val();
			var ctcode = $("#ctcode").val();
			var condition = $("#condition").val();
			window.open("<?php echo site_url('losc/reportExcel/'); ?>" + prcode +"/"+ date_from +"/"+ los +"/"+ clength +"/"+ ctcode +"/"+ condition);
			e.preventDefault();
		});

	});
</script>