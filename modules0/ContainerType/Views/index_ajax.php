<?= $this->extend('smartdepo/dashboard/template') ?>

<?= $this->section('content') ?>

<div class="content">
	<div class="main-header">
		<h2>Container Type</h2>
		<em>container type page</em>
	</div>
	<div class="main-content">

		<div class="widget widget-table">
			<div class="widget-header">
				<h3><i class="fa fa-table"></i> Container Type</h3></div>
			<div class="widget-content">
				<div class="row">
					<div class="col-md-12">
						<a href="<?=site_url('ctype/add');?>"class="btn btn-primary"><i class="fa fa-plus-square"></i>&nbsp;Add New</a>
					</div>
				</div><br>		
				<div class="row">
					<div class="col-md-12">
						<table>
							<tbody>
								<tr><th width="100">Type</th><td width="2">:</td>
									<td></td></tr>
								<tr><th>Description</th><td>:</td>
									<td></td></tr>
							</tbody>
						</table>
						<br>
						<table id="data-table" class="table table-hover table-bordered display" style="width:100%;">
							<thead>
								<tr>
									<th>#</th>
									<th>Code</th>
									<th>Description</th>
								</tr>
							</thead>
													
						</table>
					</div>
				</div>
			</div>		
		</div>		
		
	</div>
</div>

<?= $this->endSection();?>

<!-- JS -->
<?= $this->Section('script_js');?>

<script type="text/javascript">
$(document).ready(function(){
	var table = $("#data-table").DataTable({
            processing: true,
            // serverSide: true,
            // order: [],		
			ajax:{
				url:"<?=site_url('ctype');?>",			
				type:"POST",
				dataType:"json",

			},
			dataSrc:"data",			
            columnDefs: [{
                "targets": [],
                "orderable": false,
            }]
	});
});
</script>			

<?= $this->endSection();?>