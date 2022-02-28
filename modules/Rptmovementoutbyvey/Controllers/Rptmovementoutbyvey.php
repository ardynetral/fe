<?php

namespace Modules\Rptmovementoutbyvey\Controllers;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;

class Rptmovementoutbyvey extends \CodeIgniter\Controller
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
		return view('Modules\Rptmovementoutbyvey\Views\index', $data);
	}


	public function getAllData($cucode, $date_from, $date_to, $cpives)
	{
		$query_params = [
			'cporeceiv' => $cucode,
			'cpitgl1' => $date_from,
			'cpitgl2'  => $date_to,
			'cpives' => $cpives
		];

		$response = $this->client->request('GET', 'reports/laporanMuat', [
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

	public function reportPdf($cucode = '', $date_from = '', $date_to = '', $cpives = '')
	{
		check_exp_time();
		$result =   $this->getAllData($cucode, $date_from, $date_to, $cpives);
		$mpdf = new \Mpdf\Mpdf();
		$html = '';
		$html .= '
		<html>
			<head>
				<title>Daily Movement In</title>

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
					<h3>MOVEMENT CONTAINER OUT BY VEY/VOY</h3>
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
		<div clas="row">
			<div class="head-left">
				Deliverer :   ' . $cucode . '
			</div>
		</div>
				
		<table class="tbl_det_prain">
			<thead>
				<tr>
					<th>NO</th>
					<th>Vessey/Voy</th>
					<th>RO. Num</th>
					<th>Container No.</th>
					<th>Conatiner Code</th>
					<th>Lenght/Height</th>
					<th>Date</th>
					<th>Time</th>
					<th>Principal</th>
				</tr>
			</thead>
			<tbody>';

		$no = 1;
		foreach ($result['data'] as $row) {
			$html .= '
					<tr>
						<td>' . $no . '</td>
						<td>' . $row['cpoves'] . '/' . $row['cpovoyid'] . '</td>
						<td>' . $row['cporefout'] . '</td>
						<td>' . $row['crno'] . '</td>
						<td>' . $row['cccode'] . '</td>
						<td>' . $row['cclength'] . '/' . $row['ccheight'] . '</td>
						<td>' . $row['cpotgl'] . '</td>
						<td>' . $row['cpojam'] . '</td>
						<td>' . $row['cpopr'] . '</td>
					</tr>';
			$no++;
		}

		$html .= '
				</tbody>
			</table>

		</body>
		</html>
		';

		$mpdf->SetWatermarkText("Smart Depo");
		$mpdf->showWatermarkText = true;
		$mpdf->watermark_font = 'DejaVuSansCondensed';
		$mpdf->watermarkTextAlpha = 0.08;
		$mpdf->WriteHTML($html);
		$mpdf->Output();


		die();
	}


	public function reportExcel($cucode = '', $date_from = '', $date_to = '', $cpives = '')
	{
		check_exp_time();
		// echo ('cucode =  ' . $cucode . ' - date_from : ' . $date_from . ' -  date_to : ' . $date_to . ' - cpives : ' . $cpives);

		$result =   $this->getAllData($cucode, $date_from, $date_to, $cpives);


		$exl = new Spreadsheet();
		// Judul Dokumen
		$exl->setActiveSheetIndex(0)
			->setCellValue('A1', 'MOVEMENT CONTAINER OUT BY VES/VOY');
		$exl->getActiveSheet()->mergeCells("A1:I1");
		$exl->getActiveSheet()->getStyle('A1:I1')->getFont()->setSize(20);
		$exl->getActiveSheet(0)->setCellValue('A2', 'Depot : CONTINDO - PADANG');
		$exl->getActiveSheet()->mergeCells("A2:I2");
		$exl->getActiveSheet(0)->setCellValue('A3', 'Deliverer : ' . $cucode);
		$exl->getActiveSheet()->mergeCells("A3:I3");
		$exl->getActiveSheet(0)->setCellValue('A4', 'Gate In Date : ' . date('d/m/y', strtotime($date_from)) . ' to ' . date('d/m/y', strtotime($date_to)));
		$exl->getActiveSheet()->mergeCells("A4:I4");
		// Header Tabel
		$exl->setActiveSheetIndex(0)
			->setCellValue('A5', 'No')
			->setCellValue('B5', 'Vessey/Voy')
			->setCellValue('C5', 'RO. Num')
			->setCellValue('D5', 'Container No.')
			->setCellValue('E5', 'Container Code')
			->setCellValue('F5', 'Lenght/Height')
			->setCellValue('G5', 'Date')
			->setCellValue('H5', 'Time')
			->setCellValue('I5', 'Principal');
		// Body Tabel
		$col = 6;
		$i = 1;
		$num = 5;

		foreach ($result['data'] as $row) {
			$exl->setActiveSheetIndex(0)
				->setCellValue('A' . $col, $i)
				->setCellValue('B' . $col, $row['cpoves'] . '/' . $row['cpovoyid'])
				->setCellValue('C' . $col, $row['cporefout'])
				->setCellValue('D' . $col, $row['crno'])
				->setCellValue('E' . $col, $row['cccode'])
				->setCellValue('F' . $col, $row['cclength'] . '/' . $row['ccheight'])
				->setCellValue('G' . $col, $row['cpitgl'])
				->setCellValue('H' . $col, $row['cpijam'])
				->setCellValue('I' . $col, $row['cpopr']);

			$col++;
			$i++;
			$num = $num + 1;
		}

		//Style font
		$exl->getActiveSheet()->getStyle('A5:I5')->getFont()->applyFromArray(['name' => 'Arial', 'bold' => TRUE, 'color' => ['rgb' => '000000']]);
		// autosize Column
		foreach (range('A', 'I') as $columnID) {
			$exl->getActiveSheet()->getColumnDimension($columnID)
				->setAutoSize(true);
		}
		// style border
		// tabel_header
		$exl->getActiveSheet()->getStyle('A1:I1')->getAlignment()->applyFromArray([
			'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
			'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, 'textRotation' => 0, 'wrapText' => FALSE
		]);
		$exl->getActiveSheet()->getStyle('A2:I4')->getAlignment()->applyFromArray([
			'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
			'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, 'textRotation' => 0, 'wrapText' => FALSE
		]);
		// Tabel_content
		$exl->getActiveSheet()->getStyle('A5:I' . $num)->getAlignment()->applyFromArray([
			'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
			'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, 'textRotation' => 0, 'wrapText' => FALSE
		]);

		$exl->getActiveSheet()->getStyle('A5:I' . $num)->getBorders()->getAllBorders()->applyFromArray(['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]);

		$writer = new Xlsx($exl);
		$filename = 'DailyMovOutReport-' . date('Y-m-d-His');
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename=' . $filename . '.xlsx');
		header('Cache-Control: max-age=0');
		$writer->save('php://output');
		die();
	}
}
