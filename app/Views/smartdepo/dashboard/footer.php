<script src="<?php echo base_url();?>/public/themes/smartdepo/js/jquery/jquery-2.1.0.min.js"></script>
<script src="<?php echo base_url();?>/public/themes/smartdepo/js/bootstrap/bootstrap.js"></script>
<script src="<?php echo base_url();?>/public/themes/smartdepo/js/plugins/modernizr/modernizr.js"></script>
<script src="<?php echo base_url();?>/public/themes/smartdepo/js/plugins/bootstrap-tour/bootstrap-tour.custom.js"></script>
<script src="<?php echo base_url();?>/public/themes/smartdepo/js/plugins/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<script src="<?php echo base_url();?>/public/themes/smartdepo/js/king-common.js"></script>
<script src="<?php echo base_url();?>/public/themes/smartdepo/js/plugins/stat/jquery.easypiechart.min.js"></script>
<script src="<?php echo base_url();?>/public/themes/smartdepo/js/plugins/raphael/raphael-2.1.0.min.js"></script>
<script src="<?php echo base_url();?>/public/themes/smartdepo/js/plugins/stat/flot/jquery.flot.min.js"></script>
<script src="<?php echo base_url();?>/public/themes/smartdepo/js/plugins/stat/flot/jquery.flot.resize.min.js"></script>
<script src="<?php echo base_url();?>/public/themes/smartdepo/js/plugins/stat/flot/jquery.flot.time.min.js"></script>
<script src="<?php echo base_url();?>/public/themes/smartdepo/js/plugins/stat/flot/jquery.flot.pie.min.js"></script>
<script src="<?php echo base_url();?>/public/themes/smartdepo/js/plugins/stat/flot/jquery.flot.tooltip.min.js"></script>
<script src="<?php echo base_url();?>/public/themes/smartdepo/js/plugins/jquery-sparkline/jquery.sparkline.min.js"></script>
<script src="<?php echo base_url();?>/public/themes/smartdepo/js/plugins/datatable/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url();?>/public/themes/smartdepo/js/plugins/datatable/dataTables.bootstrap.js"></script>
<script src="<?php echo base_url();?>/public/themes/smartdepo/js/plugins/jquery-mapael/jquery.mapael.js"></script>
<script src="<?php echo base_url();?>/public/themes/smartdepo/js/plugins/raphael/maps/usa_states.js"></script>
<script src="<?php echo base_url();?>/public/themes/smartdepo/js/plugins/select2/select2.min.js"></script>
<script src="<?php echo base_url();?>/public/themes/smartdepo/js/plugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
<script src="<?php echo base_url();?>/public/themes/smartdepo/js/king-chart-stat.js"></script>
<script src="<?php echo base_url();?>/public/themes/smartdepo/js/king-table.js"></script>
<script src="<?php echo base_url();?>/public/themes/smartdepo/js/king-components.js"></script>
<script src="<?php echo base_url();?>/public/themes/smartdepo/js/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js"></script>
<script src="<?php echo base_url();?>/public/themes/smartdepo/js/plugins/jquery-dateformat/jquery-dateformat.min.js"></script>

<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="<?php echo base_url();?>/public/themes/smartdepo/js/plugins/fontawesome-iconpicker/dist/js/fontawesome-iconpicker.min.js"></script>

<script>
	$(document).ready(function(){
		$(".sub-menu li.active").parent().parent().addClass('active');
		$(".sub-menu li.active").parent().show();

		// check session timeout
		function checkSession() {
			$.ajax({
				url: "<?=site_url('is_timeout')?>",
				type:"POST",
				dataType:"json",
				success: function(json) {
					if(json==true) {
						window.location.href = "<?=site_url('logout')?>";
					}
				}
			});
		}
		setInterval(function(){
			checkSession();
		}, 60000);
	});
</script>

<?= $this->renderSection('script_js'); ?>