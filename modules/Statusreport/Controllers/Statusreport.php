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
		return view('Modules\Statusreport\Views\index', $data);
	}


	public function getAllData($prcode='')
	{
		$query_params = [
			'prcode' => $prcode
		];

		$response = $this->client->request('GET', 'reports/rptStatusReport', [
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			]
		]);
		$result = json_decode($response->getBody()->getContents(), true);
		$data['data'] = $result['data']['datas'][0];
		return $data;
	}

	public function reportPdf()
	{
		check_exp_time();
		$result =   $this->getAllData();
		// dd($result);
		$mpdf = new \Mpdf\Mpdf();
		$html = '';

		$html .= '
		<html>
			<head>
				<title>Status Report</title>

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
		</div>		
		<div clas="row">
			<div class="head-left">
				Date : ' . date('d/m/Y') . '</p>
			</div>
		</div>		
		<table class="tbl_det_prain">
			<thead>
				<tr>
					<th rowspan="3">No</th>
					<th rowspan="3">Opr</th>
					<th colspan="12">Container Movement</th>
					<th colspan="25">Inventory</th>
				</tr>
				<tr>
					<th colspan="6">Gate In</th>
					<th colspan="6">Gate Out</th>
					<th colspan="3">Waiting survey</th>
					<th colspan="6">AV</th>
					<th colspan="6">DM</th>
					<th colspan="4">Total Inventory</th>
					<th colspan="3">Long stay (AV)</th>
					<th colspan="3">Long stay (DM)</th>				
				</tr>
				<tr>
					<th>20</th><th>40</th><th>45</th><th>R20</th><th>R40</th><th>R45</th>
					<th>20</th><th>40</th><th>45</th><th>R20</th><th>R40</th><th>R45</th>
					<th>20</th><th>40</th><th>45</th>
					<th>20</th><th>40</th><th>45</th><th>R20</th><th>R40</th><th>R45</th>
					<th>20</th><th>40</th><th>45</th><th>R20</th><th>R40</th><th>R45</th>							
					<th>20</th><th>40</th><th>45</th><th>Teus</th>
					<th><15</th><th><30</th><th>>=30</th>
					<th><15</th><th><30</th><th>>=30</th>
				</tr>
			</thead>
			<tbody>';
		$no = 1;
		foreach ($result['data'] as $row) {
			$html .= '
					<tr>
						<td>' . $no . '</td>
						<td>' . $row['opr'] . '</td>
						<td>' . $row['ws_20'] . '</td>
						<td>' . $row['ws_40'] . '</td>
						<td>' . $row['ws_hc'] . '</td>
						<td>' . $row['ws_r20'] . '</td>
						<td>' . $row['ws_r40'] . '</td>	
						<td>' . $row['we_20'] . '</td>
						<td>' . $row['we_40'] . '</td>
						<td>' . $row['we_hc'] . '</td>
						<td>' . $row['we_r20'] . '</td>
						<td>' . $row['we_r40'] . '</td>	
						<td>' . $row['wa_20'] . '</td>
						<td>' . $row['wa_40'] . '</td>
						<td>' . $row['wa_hc'] . '</td>
						<td>' . $row['wa_r20'] . '</td>
						<td>' . $row['wa_r40'] . '</td>	
						<td>' . $row['ww_20'] . '</td>
						<td>' . $row['ww_40'] . '</td>
						<td>' . $row['ww_hc'] . '</td>
						<td>' . $row['ww_r20'] . '</td>
						<td>' . $row['ww_r40'] . '</td>	
						<td>' . $row['wr_20'] . '</td>
						<td>' . $row['wr_40'] . '</td>
						<td>' . $row['wr_hc'] . '</td>
						<td>' . $row['wr_r20'] . '</td>
						<td>' . $row['wr_r40'] . '</td>	
						<td>' . $row['iw_20'] . '</td>
						<td>' . $row['iw_40'] . '</td>
						<td>' . $row['iw_hc'] . '</td>
						<td>' . $row['iw_r20'] . '</td>
						<td>' . $row['iw_r40'] . '</td>	
						<td>' . $row['cr_20'] . '</td>
						<td>' . $row['cr_40'] . '</td>
						<td>' . $row['cr_hc'] . '</td>
						<td>' . $row['cr_r20'] . '</td>
						<td>' . $row['cr_r40'] . '</td>	
						<td>' . $row['ti_20'] . '</td>
						<td>' . $row['ti_40'] . '</td>
						<td>' . $row['ti_hc'] . '</td>
						<td>' . $row['ti_r20'] . '</td>
						<td>' . $row['ti_r40'] . '</td>					
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
					STATUS REPORT</h3>|PAGE {PAGENO}');
		$mpdf->setFooter('PRINTED ON ' . date('d/m/Y'));
		$mpdf->WriteHTML($html);
		$mpdf->Output();
		die();
	}

	public function reportExcel()
	{
		$exl = new Spreadsheet();
		$result =   $this->getAllData();
		// Judul Dokumen
		$exl->setActiveSheetIndex(0)
			->setCellValue('A1', 'Status Report');
		$exl->getActiveSheet()->mergeCells("A1:AM1");
		$exl->getActiveSheet()->getStyle('A1:AM1')->getFont()->setSize(20);
		$exl->getActiveSheet(0)->setCellValue('A2', 'Depot : CONTINDO - PADANG');
		$exl->getActiveSheet()->mergeCells("A2:AM2");
		$exl->getActiveSheet(0)->setCellValue('A3', 'As per Date : ' . date('d/m/Y'));
		$exl->getActiveSheet()->mergeCells("A3:AM3");
		$exl->getActiveSheet()->mergeCells("A4:A6");
		$exl->getActiveSheet()->mergeCells("B4:B6");
		$exl->getActiveSheet()->mergeCells("C4:N4");
		$exl->getActiveSheet()->mergeCells("C5:H5");
		$exl->getActiveSheet()->mergeCells("I5:N5");
		$exl->getActiveSheet()->mergeCells("O4:AM4");
		$exl->getActiveSheet()->mergeCells("O5:Q5");
		$exl->getActiveSheet()->mergeCells("R5:W5");
		$exl->getActiveSheet()->mergeCells("X5:AC5");
		$exl->getActiveSheet()->mergeCells("AD5:AG5");
		$exl->getActiveSheet()->mergeCells("AH5:AJ5");
		$exl->getActiveSheet()->mergeCells("AK5:AM5");

		// Header Tabel
		$exl->setActiveSheetIndex(0)
			->setCellValue('A4', 'No')
			->setCellValue('B4', 'Opr')
			->setCellValue('C4', 'Container Movement')
			->setCellValue('C5', 'Gate In')
			->setCellValue('C6', '20')
			->setCellValue('D6', '40')
			->setCellValue('E6', '45')
			->setCellValue('F6', 'R20')
			->setCellValue('G6', 'R40')
			->setCellValue('H6', 'R45')
			->setCellValue('I5', 'Gate Out')
			->setCellValue('I6', '20')
			->setCellValue('J6', '40')
			->setCellValue('K6', '45')
			->setCellValue('L6', 'R20')
			->setCellValue('M6', 'R40')
			->setCellValue('N6', 'R45')
			->setCellValue('O4', 'Inventory')
			->setCellValue('O5', 'Waiting survey')
			->setCellValue('O6', '20')
			->setCellValue('P6', '40')
			->setCellValue('Q6', '45')
			->setCellValue('R5', 'AV')
			->setCellValue('R6', '20')
			->setCellValue('S6', '40')
			->setCellValue('T6', '45')
			->setCellValue('U6', 'R20')
			->setCellValue('V6', 'R40')
			->setCellValue('W6', 'R45')
			->setCellValue('X5', 'DM')
			->setCellValue('X6', '20')
			->setCellValue('Y6', '40')
			->setCellValue('Z6', '45')
			->setCellValue('AA6', 'R20')
			->setCellValue('AB6', 'R40')
			->setCellValue('AC6', 'R45')
			->setCellValue('AD5', 'Total Inventory')
			->setCellValue('AD6', '20')
			->setCellValue('AE6', '40')
			->setCellValue('AF6', '45')
			->setCellValue('AG6', 'Teus')
			->setCellValue('AH5', 'Long Stay(AV)')
			->setCellValue('AH6', '<15')
			->setCellValue('AI6', '<30')
			->setCellValue('AJ6', '>=30')
			->setCellValue('AK5', 'Long Stay(DM)')
			->setCellValue('AK6', '<15')
			->setCellValue('AL6', '<30')
			->setCellValue('AM6', '>=30');

		//Style font
		// tabel_header
		$exl->getActiveSheet()->getStyle('A1:AM1')->getAlignment()->applyFromArray([
			'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
			'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, 
			'textRotation' => 0, 
			'wrapText' => FALSE, 
			'bold' => TRUE
		]);
		$exl->getActiveSheet()->getStyle('A2:AM4')->getAlignment()->applyFromArray([
			'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
			'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, 
			'textRotation' => 0, 
			'wrapText' => FALSE,
			'bold' => FALSE, 
		]);
		$exl->getActiveSheet()->getStyle('A4:AM6')->getFont()->applyFromArray([
			'name' => 'Arial', 
			'bold' => TRUE, 
			'color' => [
				'rgb' => '000000'
			]
		]);

		// Body Tabel
		$col = 8;
		$i = 1;
		$num = 7;
		// foreach ($result['data'] as $row) {
		// 	$exl->setActiveSheetIndex(0)
		// 		->setCellValue('A' . $col, $i)
		// 		->setCellValue('B' . $col, $row['opr'])
		// 		->setCellValue('C' . $col, $row['ws_20'])
		// 		->setCellValue('D' . $col, $row['ws_40'])
		// 		->setCellValue('E' . $col, $row['ws_hc'])
		// 		->setCellValue('F' . $col, $row['ws_r20'])
		// 		->setCellValue('G' . $col, $row['ws_r40'])
		// 		->setCellValue('H' . $col, $row['we_20'])
		// 		->setCellValue('I' . $col, $row['we_40'])
		// 		->setCellValue('J' . $col, $row['we_hc'])
		// 		->setCellValue('K' . $col, $row['we_r20'])
		// 		->setCellValue('L' . $col, $row['we_r40'])				
		// 		->setCellValue('M' . $col, $row['wa_20'])
		// 		->setCellValue('N' . $col, $row['wa_40'])
		// 		->setCellValue('O' . $col, $row['wa_hc'])
		// 		->setCellValue('P' . $col, $row['wa_r20'])
		// 		->setCellValue('Q' . $col, $row['wa_r40'])
		// 		->setCellValue('R' . $col, $row['ww_20'])
		// 		->setCellValue('S' . $col, $row['ww_40'])
		// 		->setCellValue('T' . $col, $row['ww_hc'])
		// 		->setCellValue('U' . $col, $row['ww_r20'])
		// 		->setCellValue('V' . $col, $row['ww_r40'])
		// 		->setCellValue('W' . $col, $row['wr_20'])
		// 		->setCellValue('X' . $col, $row['wr_40'])
		// 		->setCellValue('Y' . $col, $row['wr_hc'])
		// 		->setCellValue('Z' . $col, $row['wr_r20'])
		// 		->setCellValue('AA' . $col, $row['wr_r40'])
		// 		->setCellValue('AB' . $col, $row['iw_20'])
		// 		->setCellValue('AC' . $col, $row['iw_40'])
		// 		->setCellValue('AD' . $col, $row['iw_hc'])
		// 		->setCellValue('AE' . $col, $row['iw_r20'])
		// 		->setCellValue('AF' . $col, $row['iw_r40'])	
		// 		->setCellValue('AG' . $col, $row['cr_20'])
		// 		->setCellValue('AH' . $col, $row['cr_40'])
		// 		->setCellValue('AI' . $col, $row['cr_hc'])
		// 		->setCellValue('AJ' . $col, $row['cr_r20'])
		// 		->setCellValue('AK' . $col, $row['cr_r40'])
		// 		->setCellValue('AL' . $col, $row['ti_20'])
		// 		->setCellValue('AM' . $col, $row['ti_40'])
		// 		->setCellValue('AN' . $col, $row['ti_hc'])
		// 		->setCellValue('AO' . $col, $row['ti_r20'])
		// 		->setCellValue('AP' . $col, $row['ti_r40'])
		// 		;

		// 	$col++;
		// 	$i++;
		// 	$num = $num + 1;
		// }

		// autosize Column
		$sheet = $exl->getActiveSheet();
		foreach ($sheet->getColumnIterator() as $column) {
		    $sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
		}
		// style border
		// Tabel_content
		$exl->getActiveSheet()->getStyle('A4:AM' . $num)->getAlignment()->applyFromArray([
			'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
			'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, 'textRotation' => 0, 'wrapText' => FALSE
		]);

		$exl->getActiveSheet()->getStyle('A4:AM' . $num)->getBorders()->getAllBorders()->applyFromArray(['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]);

		$writer = new Xlsx($exl);
		$filename = 'Damage_Progress-' . date('Y-m-d-His');
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename=' . $filename . '.xlsx');
		header('Cache-Control: max-age=0');
		$writer->save('php://output');
		die();		
	}
}
