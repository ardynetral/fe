<?= $this->extend('smartdepo/dashboard/template') ?>

<?= $this->section('content') ?>

<div class="content">
	<div class="main-header">
		<h2>Principal</h2>
		<em>Principal page</em>
	</div>
	<div class="main-content">

		<div class="widget widget-table">
			<div class="widget-header">
				<h3><i class="fa fa-table"></i> Principal Detail</h3></div>
			<div class="widget-content">	
				<div class="row">
					<div class="col-md-12">
						<?php if($data!=""): ?>
						<table class="table">
							<tbody>
								<tr><th width="130">Principal Code</th><td width="2">:</td><td><?=$data['prcode'];?></td></tr>
								<tr><th width="100">Customer Code</th><td width="2">:</td><td><?=$data['cucode'];?></td></tr>
								<tr><th width="100">Name</th><td width="2">:</td><td><?=$data['prname'];?></td></tr>
								<tr><th width="100">Address</th><td width="2">:</td><td><?=$data['praddr'];?></td></tr>
								<tr><th width="100">Country</th><td width="2">:</td><td><?=$data['cncode'];?></td></tr>
								<tr><th width="100">Remark</th><td width="2">:</td><td><?=$data['prremark'];?></td></tr>
								<tr><th width="100">prflag1</th><td width="2">:</td><td><?=$data['prflag1'];?></td></tr>
								<tr><th width="100">prflag2</th><td width="2">:</td><td><?=$data['prflag2'];?></td></tr>
								<tr><th width="100">prautapp</th><td width="2">:</td><td><?=$data['prautapp'];?></td></tr>
								<tr><th width="100">prautbb</th><td width="2">:</td><td><?=$data['prautbb'];?></td></tr>
								<tr><th width="100">prautbm</th><td width="2">:</td><td><?=$data['prautbm'];?></td></tr>
								<tr><th width="100">przip</th><td width="2">:</td><td><?=$data['przip'];?></td></tr>
								<tr><th width="100">prphone</th><td width="2">:</td><td><?=$data['prphone'];?></td></tr>
								<tr><th width="100">prfax</th><td width="2">:</td><td><?=$data['prfax'];?></td></tr>
								<tr><th width="100">premail</th><td width="2">:</td><td><?=$data['premail'];?></td></tr>
								<tr><th width="100">prcontractno</th><td width="2">:</td><td><?=$data['prcontractno'];?></td></tr>
								<tr><th width="100">prrepono</th><td width="2">:</td><td><?=$data['prrepono'];?></td></tr>
								<tr><th width="100">prdamageno</th><td width="2">:</td><td><?=$data['prdamageno'];?></td></tr>
								<tr><th width="100">prexp</th><td width="2">:</td><td><?=date('d-m-Y',strtotime($data['prexp']));?></td></tr>
								<tr><th width="100">prtocust1</th><td width="2">:</td><td><?=$data['prtocust1'];?></td></tr>
								<tr><th width="100">prtocust2</th><td width="2">:</td><td><?=$data['prtocust2'];?></td></tr>
								<tr><th width="100">prinfocu</th><td width="2">:</td><td><?=$data['prinfocu'];?></td></tr>
								<tr><th width="100">prphcust</th><td width="2">:</td><td><?=$data['prphcust'];?></td></tr>
								<tr><th width="100">prfacust</th><td width="2">:</td><td><?=$data['prfacust'];?></td></tr>
								<tr><th width="100">prcccust1</th><td width="2">:</td><td><?=$data['prcccust1'];?></td></tr>
								<tr><th width="100">prcccust2</th><td width="2">:</td><td><?=$data['prcccust2'];?></td></tr>
								<tr><th width="100">prinfocc</th><td width="2">:</td><td><?=$data['prinfocc'];?></td></tr>
								<tr><th width="100">prfmmtc</th><td width="2">:</td><td><?=$data['prfmmtc'];?></td></tr>
								<tr><th width="100">prccmtc</th><td width="2">:</td><td><?=$data['prccmtc'];?></td></tr>
								<tr><th width="100">prfield1</th><td width="2">:</td><td><?=$data['prfield1'];?></td></tr>
								<tr><th width="100">eirnocedex</th><td width="2">:</td><td><?=$data['eirnocedex'];?></td></tr>
								<tr><th width="100">prcono</th><td width="2">:</td><td><?=$data['prcono'];?></td></tr>
								<tr><th width="100">prdmno</th><td width="2">:</td><td><?=$data['prdmno'];?></td></tr>
								<tr><th width="100">prrtno</th><td width="2">:</td><td><?=$data['prrtno'];?></td></tr>
							</tbody>
						</table>
						<?php else: ?>
							<p class="alert alert-warning">Something wrong!. Data not found.</p>
						<?php endif; ?>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<a href="<?=site_url('principal');?>" class="btn btn-default">Back</a>
						<a href="<?=site_url('principal/edit/'.$data['prcode']);?>" class="btn btn-success">Edit</a>
					</div>
				</div>
			</div>		
		</div>		
		
	</div>
</div>

<?= $this->endSection();?>
