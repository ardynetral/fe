<?= $this->extend('smartdepo/dashboard/template') ?>

<?= $this->section('content') ?>

<style>
	body{}
	.page-header{display:block;border-bottom:2px solid #aaa;padding:0;min-height:30px;margin-bottom:30px;}
	.head-left{float:left;width:200px;padding:0px;}
	.head-right{float:left;padding:0px;margin-left:200px;text-align: right;}

	.tbl_head_prain, .tbl_det_prain{border-spacing: 0;border-collapse: collapse;}
	.tbl_head_prain td{border-collapse: collapse;}
	.t-right{text-align:right;}
	.t-left{text-align:left;}

	.tbl_det_prain th,.tbl_det_prain td {
		border:1px solid #666666!important;
		border-spacing: 0;
		border-collapse: collapse;
		padding:5px;

	}
	.line-space{border-bottom:1px solid #dddddd;margin:30px 0;}
</style>

<div class="content">
	<div class="main-header">
		<h2>Pra Out</h2>
		<em>Order Pra-Out page</em>
	</div>
	<div class="main-content">

		<div class="widget widget-table" id="OrderPra">
			<div class="widget-header">
				<h3><i class="fa fa-table"></i>Order Pra Out</h3>
			</div>
			<div class="widget-content">

				<table class="tbl_head_prain" width="100%">
					<tbody>
						<tr>
							<td class="t-right" width="180">Principal</td>
							<td width="200">&nbsp;:&nbsp;<?=$header[0]['cpopr'];?></td>
							<td class="t-right" width="120">Pra In Reff</td>
							<td>&nbsp;:&nbsp;<?=$header[0]['cpiorderno'];?></td>
						</tr>
						<tr>
							<td class="t-right">Customer</td>
							<td class="t-left">&nbsp;:&nbsp;<?=$header[0]['cpcust'];?> </td>
							<td class="t-right">Pra In Date</td>
							<td class="t-left">&nbsp;:&nbsp;<?=$header[0]['cpipratgl'];?> </td>
						</tr>
						<tr>
							<td class="t-right">Discharge Port</td>
							<td class="t-left">&nbsp;:&nbsp;<?=$header[0]['cpidish'];?>  </td>
							<td class="t-right">Ref In N0 #</td>
							<td class="t-left">&nbsp;:&nbsp;<?=$header[0]['cpirefin'];?>  </td>
						</tr>
						<tr>
							<td class="t-right">Discharge Date</td>
							<td class="t-left">&nbsp;:&nbsp;<?=$header[0]['cpidisdat'];?>  </td>
							<td class="t-right">Time In</td>
							<td class="t-left">&nbsp;:&nbsp;<?=$header[0]['cpijam'];?>  </td>
						</tr>
						<tr>
							<td class="t-right">LiftOff Charges In Depot</td>
							<td class="t-left">&nbsp;:&nbsp; <?=((isset($header[0]['liftoffcharge'])&&$header[0]['liftoffcharge']==1)?"yes" : "no");?></td>
							<td class="t-right">Vessel</td>
							<td class="t-left">&nbsp;:&nbsp;<?=$header[0]['cpives'];?> </td>
						</tr>
						<tr>
							<td class="t-right">Depot</td>
							<td class="t-left">&nbsp;:&nbsp;<?=$header[0]['cpdepo'];?> </td>
							<td class="t-right">Voyage</td>
							<td class="t-left">&nbsp;:&nbsp;<?=$header[0]['voyages']['voyno'];?> </td>
						</tr>
						<tr>
							<td class="t-right">Invoice</td>
							<td class="t-left">&nbsp;:&nbsp;<?=$invoice_number;?></td>
							<td class="t-right">Vessel Operator</td>
							<td class="t-left">&nbsp;:&nbsp;<?=$header[0]['vessels']['vesopr'];?> </td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td class="t-right">Ex Cargo</td>
							<td class="t-left">&nbsp;:&nbsp;<?=$header[0]['cpicargo'];?> </td>
						</tr>	
						<tr>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td class="t-right">Redeliverer</td>
							<td class="t-left">&nbsp;:&nbsp;<?=$header[0]['cpideliver'];?> </td>
						</tr>	

					</tbody>
				</table>

				<div class="line-space"></div>
				<h3 class="legend">Container</h3>
				<table class="tbl_det_prain">
					<thead>
						<tr>
							<th>NO</th>
							<th>Container No.</th>
							<th>ID Code</th>
							<th>Type</th>
							<th>Length</th>
							<th>Height</th>
							<th>F/E</th>
							<th>Gate In Date</th>
						</tr>
					</thead>
					<tbody>
					<?php 
					$no=1;
					foreach($detail as $row):?>
						<tr>
							<td><?=$no; ?></td>
							<td><?=$row['crno']; ?></td>
							<td><?=$row['cccode']; ?></td>
							<td><?=$row['ctcode']; ?></td>
							<td><?=$row['cclength']; ?></td>
							<td><?=$row['ccheight']; ?></td>
							<td><?=((isset($row['cpife'])&&$row['cpife']==1) ? "full" : "Empty");?></td>
							<td><?= $row['cpigatedate']; ?></td>
						</tr>
					<?php $no++;
					endforeach; ?>

					</tbody>
				</table>
				
			</div>
			<div class="widget-footer">
				<a href="<?=site_url('praout');?>" class="btn btn-default">Kembali</a>
				<a href="<?=site_url('praout/edit/'.$header[0]['praid']);?>" class="btn btn-success">Edit</a>
			</div>
		</div>	
	</div>
</div>

<?= $this->endSection();?>

<!-- JS -->
<?= $this->Section('script_js');?>

	<?= $this->include('\Modules\PraOut\Views\js'); ?>	
	
<?= $this->endSection();?>