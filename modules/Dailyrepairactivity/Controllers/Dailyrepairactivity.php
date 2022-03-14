<?php

namespace Modules\Dailyrepairactivity\Controllers;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;

class Dailyrepairactivity extends \CodeIgniter\Controller
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
		return view('Modules\Dailyrepairactivity\Views\index', $data);
	}

	public function getAllData($prcode='', $date_from='')
	{
		$query_params = [
			'prcode' => $prcode,
			'date_from' => $date_from
		];

		$response = $this->client->request('GET', 'reports/rptDailyRepairActivity', [
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'query' => $query_params,
		]);
		$result = json_decode($response->getBody()->getContents(), true);
		// print_r($result['message']);die();
		$data['data'] = $result['data']['datas'][0];
		return $data;
	}

	public function reportPdf($prcode='', $date_from='')
	{
		check_exp_time();
		$result =   $this->getAllData($prcode, $date_from);
		$mpdf = new \Mpdf\Mpdf();
		$html = '';

		$html .= '
		<html>
			<head>
				<title>Daily Repair Acitivity Report</title>

				<style>
					body{font-family: Arial;font-size:12px;}
					.page-header{display:block;border-bottom:2px solid #aaa;padding:0;min-height:30px;margin-bottom:10px;}
					.head-left{float:left;width:200px;padding:0px;}
					.head-right{float:left;padding:0px;margin-left:200px;text-align: right;}

					.tbl_head_prain, .tbl_det_prain{border-spacing: 0;border-collapse: collapse;}
					.tbl_head_prain td{border-collapse: collapse;}
					.t-right{text-align:right;}
					.t-left{text-align:left;}
					.t-center{text-align:center;}

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
			<h3>&nbsp;</h3>
			<table class="tbl_head_prain" width="100%">
				<tbody>
					
				</tbody>
			</table>
		';

		$html .= '
		<div clas="row">
			<div class="head-left">
				Depot : CONTINDO - PADANG
			</div>
			<div class="head-right">
				As Per Date :' . date('d/m/Y', strtotime($date_from)) . '</p>		
			</div>
		</div>
		<div clas="row">
			<div class="head-left">
				Container Operator : ' . $prcode . '
			</div>
		</div>		
		<table class="tbl_det_prain">
			<thead>
				<tr>
					<th rowspan="2">Cust</th>
					<th rowspan="2">Opr</th>
					<th rowspan="2">Container</th>
					<th colspan="2">Size</th>
					<th rowspan="2">Type</th>
					<th colspan="5">Date Workshop</th>
					<th rowspan="2">MHR</th>
					<th rowspan="2">Labour</th>
					<th colspan="3">Type of Damage</th>
					<th rowspan="2">Revenue</th>
					<th rowspan="2">Remarks</th>
				</tr>
				<tr>
					<th>20</th>
					<th>40</th>
					<th>Appv</th>
					<th>In W/S</th>
					<th>Start</th>
					<th>Finish</th>
					<th>Out W/S</th>
					<th>MM</th>
					<th>MD</th>
					<th>MJ</th>
				</tr>
			</thead>
			<tbody>';
		$no = 1;
		foreach ($result['data'] as $row) {
			$html .= '
					<tr>
						<td>' . $row['cust'] . '</td>
						<td>' . $row['opr'] . '</td>
						<td>' . $row['container'] . '</td>
						<td>' . $row['size_20'] . '</td>
						<td>' . $row['size_40'] . '</td>
						<td>' . $row['ctype'] . '</td>
						<td>' . $row['appv'] . '</td>
						<td>' . $row['in_w_s'] . '</td>						
						<td>' . $row['dw_start'] . '</td>						
						<td>' . $row['dw_finish'] . '</td>						
						<td>' . $row['out_w_s'] . '</td>						
						<td>' . $row['mhr'] . '</td>						
						<td>' . $row['labour'] . '</td>						
						<td>' . $row['tod_mm'] . '</td>						
						<td>' . $row['tod_md'] . '</td>						
						<td>' . $row['tod_mj'] . '</td>						
						<td>' . $row['revenue'] . '</td>						
						<td>' . $row['remarks'] . '</td>						
					</tr>';

			$no++;
		}
		$html .= '
			</tbody>
		</table>

		</body>
		</html>
		';
		$mpdf->setHeader('|<h3>PT. CONTINDO RAYA<br>
					DAILY REPAIR ACTIVITY</h3>|PAGE {PAGENO}');
		$mpdf->WriteHTML($html);
		$mpdf->Output();
		die();
	}

	public function reportExcel($prcode='', $date_from='')
	{

		$exl = new Spreadsheet();
		$result =   $this->getAllData($prcode, $date_from);
		// Judul Dokumen
		$exl->setActiveSheetIndex(0)
			->setCellValue('A1', 'Daily Repair Acitivity Report');
		$exl->getActiveSheet()->mergeCells("A1:R1");
		$exl->getActiveSheet()->getStyle('A1:R1')->getFont()->setSize(20);
		$exl->getActiveSheet(0)->setCellValue('A2', 'Depot : CONTINDO - PADANG');
		$exl->getActiveSheet()->mergeCells("A2:R2");
		$exl->getActiveSheet(0)->setCellValue('A3', 'Container Operator : ' . $prcode);
		$exl->getActiveSheet()->mergeCells("A3:O3");
		$exl->getActiveSheet(0)->setCellValue('P3', 'Gate In Date : ' . date('d/m/Y', strtotime($date_from)));
		$exl->getActiveSheet()->mergeCells("P3:R3");		
		$exl->getActiveSheet()->mergeCells("A4:A5");
		$exl->getActiveSheet()->mergeCells("B4:B5");
		$exl->getActiveSheet()->mergeCells("C4:C5");
		$exl->getActiveSheet()->mergeCells("D4:E4");
		$exl->getActiveSheet()->mergeCells("F4:F5");
		$exl->getActiveSheet()->mergeCells("G4:K4");
		$exl->getActiveSheet()->mergeCells("L4:L5");
		$exl->getActiveSheet()->mergeCells("M4:M5");
		$exl->getActiveSheet()->mergeCells("N4:P4");
		$exl->getActiveSheet()->mergeCells("Q4:Q5");
		$exl->getActiveSheet()->mergeCells("R4:R5");
		// Header Tabel
		$exl->setActiveSheetIndex(0)
			->setCellValue('A4', 'Cust')
			->setCellValue('B4', 'Opr')
			->setCellValue('C4', 'Container')
			->setCellValue('D4', 'Size')
			->setCellValue('D5', '20')
			->setCellValue('E5', '40')
			->setCellValue('F4', 'Type')
			->setCellValue('G4', 'Date Workshop')
			->setCellValue('G5', 'Appv')
			->setCellValue('H5', 'In W/S')
			->setCellValue('I5', 'Start')
			->setCellValue('J5', 'Finish')
			->setCellValue('K5', 'Out W/S')
			->setCellValue('L4', 'MHR')
			->setCellValue('M4', 'Labour')
			->setCellValue('N4', 'Type of Damage')
			->setCellValue('N5', 'MM')
			->setCellValue('O5', 'MD')
			->setCellValue('P5', 'MJ')
			->setCellValue('Q4', 'Revenue')
			->setCellValue('R4', 'TRemarks');

		//Style font
		// tabel_header
		$exl->getActiveSheet()->getStyle('A1:R1')->getAlignment()->applyFromArray([
			'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
			'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, 
			'textRotation' => 0, 
			'wrapText' => FALSE, 
			'bold' => TRUE
		]);
		$exl->getActiveSheet()->getStyle('A2:R3')->getAlignment()->applyFromArray([
			'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
			'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, 
			'textRotation' => 0, 
			'wrapText' => FALSE,
			'bold' => FALSE, 
		]);
		$exl->getActiveSheet()->getStyle('A4:R5')->getFont()->applyFromArray([
			'name' => 'Arial', 
			'bold' => TRUE, 
			'color' => [
				'rgb' => '000000'
			]
		]);

		// Body Tabel
		$col = 6;
		$i = 0;
		$num = 5;
		foreach ($result['data'] as $row) {
			$exl->setActiveSheetIndex(0)
				->setCellValue('A' . $col, $row['cust'])
				->setCellValue('B' . $col, $row['opr'])
				->setCellValue('C' . $col, $row['container'])
				->setCellValue('D' . $col, $row['size_20'])
				->setCellValue('E' . $col, $row['size_40'])
				->setCellValue('F' . $col, $row['ctype'])
				->setCellValue('G' . $col, $row['appv'])
				->setCellValue('H' . $col, $row['in_w_s'])
				->setCellValue('I' . $col, $row['dw_start'])
				->setCellValue('J' . $col, $row['dw_finish'])
				->setCellValue('K' . $col, $row['out_w_s'])
				->setCellValue('L' . $col, $row['mhr'])
				->setCellValue('M' . $col, $row['labour'])
				->setCellValue('N' . $col, $row['tod_mm'])
				->setCellValue('O' . $col, $row['tod_md'])
				->setCellValue('P' . $col, $row['tod_mj'])
				->setCellValue('Q' . $col, $row['revenue'])
				->setCellValue('R' . $col, $row['remarks']);

			$col++;
			$i++;
			$num = $num + 1;
		}

		// autosize Column
		foreach (range('A', 'R') as $columnID) {
			$exl->getActiveSheet()->getColumnDimension($columnID)
				->setAutoSize(true);
		}
		// style border
		// Tabel_content
		$exl->getActiveSheet()->getStyle('A4:R' . $num)->getAlignment()->applyFromArray([
			'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
			'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, 'textRotation' => 0, 'wrapText' => FALSE
		]);

		$exl->getActiveSheet()->getStyle('A4:R' . $num)->getBorders()->getAllBorders()->applyFromArray(['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]);

		$writer = new Xlsx($exl);
		$filename = 'DailyAcivity-' . date('Y-m-d-His');
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename=' . $filename . '.xlsx');
		header('Cache-Control: max-age=0');
		$writer->save('php://output');
		die();
	}
}
