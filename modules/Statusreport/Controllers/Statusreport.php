<?php

namespace Modules\Statusreport\Controllers;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;

class Statusreport extends \CodeIgniter\Controller
{
	private $client;
	public function __construct()
	{
		helper(['url', 'form', 'app']);
		$this->client = api_connect();
	}

	public function index()
	{
		$module = service('uri')->getSegment(1);
		has_privilege($module, "_view");

		$token = get_token_item();
		$user_id = $token['id'];
		$group_id = $token['groupId'];
		$prcode = $token['prcode'];

		if (($group_id == 1) || ($group_id == 2)) {
			$data['prcode'] = $prcode;
			$data['cucode'] = $prcode;
		} else {
			$data['prcode'] = '0';
			$data['cucode'] = '0';
		}
		$data['group_id'] = $group_id;
		return view('Modules\Statusreport\Views\add', $data);
	}

	public function view($code)
	{
		$token = get_token_item();
		$user_id = $token['id'];
		$group_id = $token['groupId'];
		$prcode = $token['prcode'];

		if (($group_id == 1) || ($group_id == 2)) {
			$data['prcode'] = $prcode;
			$data['cucode'] = $prcode;
		} else {
			$data['prcode'] = '0';
			$data['cucode'] = '0';
		}
		$data['group_id'] = $group_id;
		return view('Modules\Statusreport\Views\view', $data);
	}


	public function reportPdf()
	{
		$db = \Config\Database::connect();
		$sql_body = "
		select 
		cp.cpopr as principal,
		month(cp.cpitgl) as dinfo_month,
		day(cp.cpitgl) as dinfo_daily,
		count(cp.crno) as total
		from container_process cp
		where year(cp.cpitgl)=year(now())
		group by cp.cpopr, month(cp.cpitgl), day(cp.cpitgl)
		";

		$data_body = $db->query($sql_body)->getResultArray();

		$mpdf = new \Mpdf\Mpdf();
		$html = '';

		$html .= '
		<html>
			<head>
				<title>Status Report</title>

				<style>
					body{font-family: Arial;font-size:12px;}
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
			</head>
		';

		$html .= '<body>
			<div class="page-header">
				<div class="head-left">
					<h4>PT. CONTINDO RAYA</h4>
				</div>
				<div class="head-right">
					<p>PADANG, ' . date('d/m/Y') . '</p>		
				</div>
			</div>
		';
		$html .= '
			<table class="tbl_head_prain" width="100%">
				<tbody>
					
				</tbody>
			</table>
		';

		$html .= '
			<h4>Status Report</h4>
			<table class="tbl_det_prain">
				<thead>
					<tr>
						<th>NO</th>
						<th>Principal</th>
						<th>Info Daily</th>
						<th>Info Month</th>
						<th>Total</th>
					</tr>
				</thead>
				<tbody>';
		$no = 1;
		foreach ($data_body as $row) {
			$html .= '
					<tr>
						<td>' . $no . '</td>
						<td>' . $row['principal'] . '</td>
						<td>' . $row['dinfo_month'] . '</td>
						<td>' . $row['dinfo_daily'] . '</td>
						<td>' . $row['total'] . '</td>
					</tr>';

			$no++;
		}
		$html .= '
				</tbody>
			</table>

		</body>
		</html>
		';
		$mpdf->WriteHTML($html);
		$mpdf->Output();
		die();
	}

	public function reportExcel()
	{
		$db = \Config\Database::connect();
		$sql_body = "
		select 
		cp.cpopr as principal,
		month(cp.cpitgl) as dinfo_month,
		day(cp.cpitgl) as dinfo_daily,
		count(cp.crno) as total
		from container_process cp
		where year(cp.cpitgl)=year(now())
		group by cp.cpopr, month(cp.cpitgl), day(cp.cpitgl)
		";

		$data_body = $db->query($sql_body)->getResultArray();

		$exl = new Spreadsheet();
		// Judul Dokumen
		$exl->setActiveSheetIndex(0)
			->setCellValue('A1', 'Status Report');
		$exl->getActiveSheet()->mergeCells("A1:E1");
		$exl->getActiveSheet()->getStyle('A1:E1')->getFont()->setSize(20);
		// Header Tabel
		$exl->setActiveSheetIndex(0)
			->setCellValue('A2', 'No')
			->setCellValue('B2', 'Principal')
			->setCellValue('C2', 'Info Month')
			->setCellValue('D2', 'Info Daily')
			->setCellValue('E2', 'Total');

		// Body Tabel
		$col = 3;
		$i = 0;
		$num = 2;
		foreach ($data_body as $row) {
			$exl->setActiveSheetIndex(0)
				->setCellValue('A' . $col, $i)
				->setCellValue('B' . $col, $row['principal'])
				->setCellValue('C' . $col, $row['dinfo_month'])
				->setCellValue('D' . $col, $row['dinfo_daily'])
				->setCellValue('E' . $col, $row['total']);
			$col++;
			$i++;
			$num = $num + 1;
		}

		//Style font
		$exl->getActiveSheet()->getStyle('A2:E2')->getFont()->applyFromArray(['name' => 'Arial', 'bold' => TRUE, 'color' => ['rgb' => '000000']]);
		// style border
		$exl->getActiveSheet()->getStyle('A1:E' . $num)->getAlignment()->applyFromArray(['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, 'textRotation' => 0, 'wrapText' => TRUE]);
		$exl->getActiveSheet()->getStyle('A2:E' . $num)->getBorders()->getAllBorders()->applyFromArray(['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]);

		$writer = new Xlsx($exl);
		$filename = 'StatusReport-' . date('Y-m-d-His');
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename=' . $filename . '.xlsx');
		header('Cache-Control: max-age=0');
		$writer->save('php://output');
		die();
	}
}
