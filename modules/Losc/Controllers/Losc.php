<?php

namespace Modules\Losc\Controllers;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;

class Losc extends \CodeIgniter\Controller
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
		return view('Modules\Losc\Views\index', $data);
	}

	public function getAllData($prcode, $date_from, $los, $clength, $ctcode, $condition)
	{
		$query_params = [
			"prcode"=> $prcode, 
			"date_from"=> $date_from, 
			"los"=> $los, 
			"clength"=> $clength, 
			"ctcode"=> $ctcode,  
			"condition"=> $condition
		];

		$response = $this->client->request('GET', 'reports/rptLenghtOfStay', [
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'query' => $query_params,
		]);
		$result = json_decode($response->getBody()->getContents(), true);
		$data = isset($result['data']['datas'][0])?$result['data']['datas'][0]:"";
		return $data;
	}

	public function reportPdf($prcode='', $date_from='', $los='', $clength='', $ctcode='',  $condition='')
	{
		$mpdf = new \Mpdf\Mpdf();
		$result =   $this->getAllData($prcode, $date_from, $los, $clength, $ctcode, $condition);
		if($condition=="A") { $cond = "Avaliable"; } 
		else if($condition=="D") { $cond = "Damage"; } 

		$html = '';
		$html .= '
		<html>
			<head>
				<title>Long Stay Report</title>

				<style>
					body{font-family: Arial;font-size:12px;}
					.page-header{display:block;border-bottom:2px solid #aaa;padding:0;min-height:30px;margin-bottom:30px;}
					.head-left{float:left;width:200px;padding:0px;}
					.head-right{float:left;padding:0px;margin-left:200px;text-align: right;}
					.col{float:left;padding:0px;text-align: left;display:inline-grid;}

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
			<h3>&nbsp;</h3>
			<table class="tbl_head_prain" width="100%">
				<tbody>
					
				</tbody>
			</table>
		';
		$html .= '
			<table class="tbl_head_prain" width="100%">
				<tbody>
					<tr>
					<td>Depot : CONTINDO - PADANG</td>
					<td class="t-right">Principal : ' . $prcode . '</td>
					</tr>									
				</tbody>
			</table>
		';

		$html .= '
			<table class="tbl_det_prain" width="100%">
				<thead>
					<tr>
						<th>NO</th>
						<th>Original Vessel/Voyage</th>
						<th>Container No. </th>
						<th>Type</th>
						<th>Length</th>
						<th>Height</th>
						<th>In Depo</th>
						<th>Days</th>
						<th>Cond.</th>
						<th>Status</th>
						<th>Remarks</th>
					</tr>
				</thead>
				<tbody>';
		$no = 1;
		foreach ($result as $row) {
			$html .= '
					<tr>
						<td>' . $no . '</td>
						<td>' . $row['ves'] . '</td>
						<td>' . $row['container_no'] . '</td>
						<td>' . $row['ctype'] . '</td>
						<td>' . $row['clength'] . '</td>
						<td>' . $row['cheight'] . '</td>
						<td>' . $row['date_in'] . '</td>
						<td>' . $row['days'] . '</td>
						<td></td>
						<td>' . $row['status'] . '</td>
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
					LONG OF STAY CONTAINER</h3>|PAGE {PAGENO}');
		$mpdf->WriteHTML($html);
		$mpdf->Output();
		die();
	}

	public function reportExcel($prcode='', $date_from='', $los='', $clength='', $ctcode='',  $condition='')
	{

		$exl = new Spreadsheet();
		$result =   $this->getAllData($prcode, $date_from, $los, $clength, $ctcode, $condition);
		if($condition=="A") { $cond = "Avaliable"; } 
		else if($condition=="D") { $cond = "Damage"; } 

		// Judul Dokumen
		$exl->setActiveSheetIndex(0)
			->setCellValue('A1', ' Long of Stay Container Report');
		$exl->getActiveSheet()->mergeCells("A1:K1");
		$exl->getActiveSheet()->getStyle('A1:K1')->getFont()->setSize(20);
		$exl->getActiveSheet(0)->setCellValue('A2', 'Depot : CONTINDO - PADANG');
		$exl->getActiveSheet()->mergeCells("A2:D2");
		$exl->getActiveSheet(0)->setCellValue('H2', 'Container Operator : ' . $prcode);
		$exl->getActiveSheet()->mergeCells("H2:K2");
		// Header Tabel
		$exl->setActiveSheetIndex(0)
			->setCellValue('A3', 'No')
			->setCellValue('B3', 'Original Vessel/Voyage')
			->setCellValue('C3', 'Container No.')
			->setCellValue('D3', 'Type')
			->setCellValue('E3', 'Length')
			->setCellValue('F3', 'Height')
			->setCellValue('G3', 'In Depo')
			->setCellValue('H3', 'Days')
			->setCellValue('I3', 'Cond')
			->setCellValue('J3', 'Status')
			->setCellValue('K3', 'Remarks');
		//Style font
		// tabel_header
		$exl->getActiveSheet()->getStyle('A1:K1')->getAlignment()->applyFromArray([
			'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
			'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, 
			'textRotation' => 0, 
			'wrapText' => FALSE, 
			'bold' => TRUE
		]);
		$exl->getActiveSheet()->getStyle('A2:H2')->getAlignment()->applyFromArray([
			'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
			'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, 
			'textRotation' => 0, 
			'wrapText' => FALSE,
			'bold' => FALSE, 
		]);
		$exl->getActiveSheet()->getStyle('A3:K3')->getFont()->applyFromArray([
			'name' => 'Arial', 
			'bold' => TRUE, 
			'color' => [
				'rgb' => '000000'
			]
		]);

		// Body Tabel
		$col = 4;
		$i = 1;
		$num = 3;
		foreach ($result as $row) {
			$exl->setActiveSheetIndex(0)
				->setCellValue('A' . $col, $i)
				->setCellValue('B' . $col, $row['ves'])
				->setCellValue('C' . $col, $row['container_no'])
				->setCellValue('D' . $col, $row['ctype'])
				->setCellValue('E' . $col, $row['clength'])
				->setCellValue('F' . $col, $row['cheight'])
				->setCellValue('G' . $col, $row['date_in'])
				->setCellValue('H' . $col, $row['days'])
				->setCellValue('I' . $col, '')
				->setCellValue('J' . $col, $row['status'])
				->setCellValue('K' . $col, $row['remarks']);

			$col++;
			$i++;
			$num = $num + 1;
		}

		// autosize Column
		foreach (range('A', 'K') as $columnID) {
			$exl->getActiveSheet()->getColumnDimension($columnID)
				->setAutoSize(true);
		}
		// style border
		// Tabel_content
		$exl->getActiveSheet()->getStyle('A3:K' . $num)->getAlignment()->applyFromArray([
			'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
			'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, 'textRotation' => 0, 'wrapText' => FALSE
		]);

		$exl->getActiveSheet()->getStyle('A3:K' . $num)->getBorders()->getAllBorders()->applyFromArray(['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]);

		$writer = new Xlsx($exl);
		$filename = 'Long_of_stay_container-' . date('Y-m-d-His');
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename=' . $filename . '.xlsx');
		header('Cache-Control: max-age=0');
		$writer->save('php://output');
		die();
	}
}
