<?php

namespace Modules\SecurityReport\Controllers;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;

class SecurityReport extends \CodeIgniter\Controller
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
		return view('Modules\SecurityReport\Views\index', $data);
	}


	public function getAllData($date_from, $date_to)
	{
		$query_params = [
			'tgl1' => $date_from,
			'tgl2'  => $date_to
		];

		$response = $this->client->request('GET', 'reports/reportSecurity', [
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'query' => $query_params,
		]);
		$result = json_decode($response->getBody()->getContents(), true);
		$data['data'] = $result['data']['resultData'];
		return $data;
	}

	public function reportPdf($date_from = '', $date_to = '')
	{
		check_exp_time();

		$result =   $this->getAllData($date_from, $date_to);
		// dd($result);
		$mpdf = new \Mpdf\Mpdf();
		$html = '';
		$html .= '
		<html>
			<head>
				<title>Security Daily Report</title>

				<style>
					body{font-family: Arial;font-size:10px;}
					.page-header{display:block;border-bottom:2px solid #aaa;padding:0;min-height:30px;margin-bottom:30px;font-family: Arial;font-size:18px;}
					.head-left{float:left;width:200px;padding:0px;}
					.head-right{float:left;padding:0px;margin-left:200px;text-align: right;}

					.tbl_head_prain, .tbl_det_prain{border-spacing: 0;border-collapse: collapse;}
					.tbl_head_prain td{border-collapse: collapse;}
					.t-right{text-align:right;}
					.t-left{text-align:left;}

					.tbl_det_prain th,.tbl_det_prain td {
						border:0.2px solid #666666!important;
						border-spacing: 0;
						border-collapse: collapse;
						padding:5px;

					}
					.line-space{border-bottom:1px solid #dddddd;margin:30px 0;}
				</style>
			</head>
		';

		$html .= '<body>
			<div class="page-header center">
				<div>
					<h3>SECURITY DAILY REPORT</h3>
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
		<div clas="row">
			<div class="head-left">
					Depot : CONTINDO - PADANG
			</div>
			<div class="head-right">
			Gate In Date :' . date('d/m/y', strtotime($date_from)) . ' to ' . date('d/m/y', strtotime($date_to)) . '</p>		
			</div>			
		</div>
				
		<table class="tbl_det_prain" width="100%">
			<thead>
				<tr>
					<th>NO</th>
					<th>Container No.</th>
					<th>Security Name</th>
					<th>Date</th>
					<th>Gate</th>
					<th>Vehicle ID</th>
					<th>Seal No.</th>
				</tr>
			</thead>
			<tbody>';

		$no = 1;
		foreach ($result['data'] as $row) {
			$datetime = ($row['securitydatetime']!=NULL) ? date('d/m/Y',strtotime($row['securitydatetime'])) .' '. date('H:i:s', strtotime($row['securitydatetime'])) : "";  
			$html .= '
					<tr>
						<td>' . $no . '</td>
						<td>' . $row['crno'] . '</td>
						<td>' . $row['securityname'] . '</td>
						<td>' . $datetime . '</td>
						<td>' . $row['gate'] . '</td>
						<td>' . $row['nopol'] . '</td>
						<td>' . $row['seal'] . '</td>
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


	public function reportExcel($date_from = '', $date_to = '')
	{
		check_exp_time();

		$result =   $this->getAllData($date_from, $date_to);

		$exl = new Spreadsheet();
		// Judul Dokumen
		$exl->setActiveSheetIndex(0)
			->setCellValue('A1', 'SECURITY DAILY REPORT');
		$exl->getActiveSheet()->mergeCells("A1:G1");
		$exl->getActiveSheet()->getStyle('A1:G1')->getFont()->setSize(20);
		$exl->getActiveSheet(0)->setCellValue('A2', 'Depot : CONTINDO - PADANG');
		$exl->getActiveSheet()->mergeCells("A2:G2");
		$exl->getActiveSheet(0)->setCellValue('A3', 'Gate In Date : ' . date('d/m/y', strtotime($date_from)) . ' to ' . date('d/m/y', strtotime($date_to)));
		$exl->getActiveSheet()->mergeCells("A3:G3");
		// Header Tabel
		$exl->setActiveSheetIndex(0)
			->setCellValue('A4', 'No')
			->setCellValue('B4', 'Container No.')
			->setCellValue('C4', 'Security Name')
			->setCellValue('D4', 'Date')
			->setCellValue('E4', 'Gate')
			->setCellValue('F4', 'Vehicle ID')
			->setCellValue('G4', 'Seal No.');		
		// Body Tabel
		$col = 5;
		$i = 1;
		$num = 4;
		foreach ($result['data'] as $row) {
			$datetime = ($row['securitydatetime']!=NULL) ? date('d/m/Y',strtotime($row['securitydatetime'])) .' '. date('H:i:s', strtotime($row['securitydatetime'])) : "";  
			$exl->setActiveSheetIndex(0)
				->setCellValue('A' . $col, $i)
				->setCellValue('B' . $col, $row['crno'])
				->setCellValue('C' . $col, $row['securityname'])
				->setCellValue('D' . $col, $datetime)
				->setCellValue('E' . $col, $row['gate'])
				->setCellValue('F' . $col, $row['nopol'])
				->setCellValue('G' . $col, $row['seal']);			
			$col++;
			$i++;
			$num = $num + 1;
		}

		//Style font
		$exl->getActiveSheet()->getStyle('A4:G4')->getFont()->applyFromArray(['name' => 'Arial', 'bold' => TRUE, 'color' => ['rgb' => '000000']]);
		// autosize Column
		foreach (range('A', 'J') as $columnID) {
			$exl->getActiveSheet()->getColumnDimension($columnID)
				->setAutoSize(true);
		}
		// style border
		// tabel_header
		$exl->getActiveSheet()->getStyle('A1:G1')->getAlignment()->applyFromArray([
			'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
			'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, 'textRotation' => 0, 'wrapText' => FALSE
		]);
		$exl->getActiveSheet()->getStyle('A2:G3')->getAlignment()->applyFromArray([
			'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
			'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, 'textRotation' => 0, 'wrapText' => FALSE
		]);
		// Tabel_content
		$exl->getActiveSheet()->getStyle('A4:G' . $num)->getAlignment()->applyFromArray([
			'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
			'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, 'textRotation' => 0, 'wrapText' => FALSE
		]);

		$exl->getActiveSheet()->getStyle('A4:G' . $num)->getBorders()->getAllBorders()->applyFromArray(['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]);

		$writer = new Xlsx($exl);
		$filename = 'Security_Report-' . date('Y-m-d-His');
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename=' . $filename . '.xlsx');
		header('Cache-Control: max-age=0');
		$writer->save('php://output');
		die();
	}
}
