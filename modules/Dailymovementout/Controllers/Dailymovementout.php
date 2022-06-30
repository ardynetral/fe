<?php

namespace Modules\Dailymovementout\Controllers;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;

class Dailymovementout extends \CodeIgniter\Controller
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
		return view('Modules\Dailymovementout\Views\index', $data);
	}


	public function getAllData($prcode, $date_from, $date_to, $hour_from, $hour_to)
	{
		$prcd = ($prcode == 'All')?"":$prcode;
		$dFrom = date("Y-m-d", strtotime($date_from));		
		$dTo = date("Y-m-d", strtotime($date_to));		
		$hFrom = ($hour_from == "")?"00:00":$hour_to;
		$hTo = ($hour_to == "")?"23:59":$hour_from;
		$query_params = [
			'prcode' => $prcd,
			'date_from' => $dFrom,
			'date_to'  => $dTo,
			'hour_from' => $hFrom,
			'hour_to'  => $hTo
		];

		$response = $this->client->request('GET', 'reports/rptDailyMovementOut', [
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'query' => $query_params,
		]);
		$result = json_decode($response->getBody()->getContents(), true);
		$data['data'] = $result['data']['datas'][0];
		// dd($data);
		// die;
		return $data;
	}

	public function reportPdf($prcode = '', $date_from = '', $date_to = '', $hour_from = '', $hour_to = '')
	{
		check_exp_time();
		$hour_from = '';
		$hour_to = '';
		$result =   $this->getAllData($prcode, $date_from, $date_to, $hour_from, $hour_to);
		// print_r($result['data'][0]);die();
		$mpdf = new \Mpdf\Mpdf();
		$html = '';
		$html .= '
		<html>
			<head>
				<title>Daily Movement Out</title>

				<style>
					body{font-family: Arial;font-size:10px;}
					.page-header{display:block;padding:0;margin-bottom:10px;font-family: Arial;font-size:18px;}
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
					.tbl_det_prain { 
						
					}
					.line-space{border-bottom:1px solid #dddddd;margin:30px 0;}
				</style>
			</head>
		';

		$html .= '<body>

		';

		$html .= '
			<table class="tbl_head_prain" width="100%">
				<tbody>
					
				</tbody>
			</table>
		';

		if($prcode=="All") 
		{
			$no = 1;
			$grouping = array();
			foreach ($result['data'] as $key=>$row) {
				$grouping[$row['cpopr']][]=$row;
			}
			$j=0;
			foreach ($grouping as $pr_code) {
				$html .= '
				<div clas="row">
				<h3>&nbsp;</h3>
					<div class="head-left">
							Depot : CONTINDO - PADANG
					</div>
					<div class="head-right">	
					</div>
				</div>
				<div clas="row">
					<div class="head-left">
						Container Operator :   ' . $pr_code[0]['cpopr'] . '
					</div>
					<div class="head-right">
					Gate Out Date :' . date('d/m/y', strtotime($date_from)) . ' to ' . date('d/m/y', strtotime($date_to)) . '</p>		
					</div>					
				</div>
						
				<table class="tbl_det_prain">
					<thead>
						<tr>
							<th>NO</th>
							<th>Container No.</th>
							<th>ID Code</th>
							<th>Type</th>
							<th>L</th>
							<th>H</th>
							<th>Loading Vessel/Voyage</th>
							<th>Dest</th>
							<th>Date In</th>
							<th>Con</th>
							<th>Receiver</th>
							<th>DO No / Seal</th>
							<th>Time</th>
							<th>Trucker</th>
							<th>Vehicle Id</th>
						</tr>
					</thead>
					<tbody>';
				$n=1;
				foreach($pr_code as $prc) {
					$debitur = get_debitur($prc['receiver']);

					$html .= '
						<tr>
							<td>' . $n . '</td>
							<td>' . strtoupper($prc['crno']) . '</td>
							<td>' . $prc['id_code'] . '</td>
							<td>' . $prc['ctype'] . '</td>
							<td>' . $prc['clength'] . '</td>
							<td>' . $prc['cheight'] . '</td>
							<td>' . $prc['loading_ves'] . ' / ' . $prc['loading_voy'] . '</td>
							<td>' . $prc['dest'] . '</td>
							<td>' . $prc['date_in'] . '</td>
							<td>' . $prc['cond'] . '</td>
							<td>' . $debitur['name'] . '</td>
							<td>' . $prc['do_no'] . ' / ' . $prc['seal'] . '</td>
							<td>' . $prc['time'] . '</td>
							<td>' . $prc['trucker'] . '</td>
							<td>' . $prc['vehicle_id'] . '</td>
						</tr>';
					$n++;
				}
				$html .= '
						</tbody>
					</table>';	
				$len = count($grouping);
				// echo $len;die();
				if($no<$len){
					$html .= '<p style="page-break-after: always;"></p>';
				}
			$no++;
			$j++;
			}
		} 

		else 
		{
			$html .= '
			<div clas="row">
			<h3>&nbsp;</h3>
				<div class="head-left">
						Depot : CONTINDO - PADANG
				</div>
				<div class="head-right">	
				</div>
			</div>
			<div clas="row">
				<div class="head-left">
					Container Operator :   ' . $prcode . '
				</div>
				<div class="head-right">
				Gate Out Date :' . date('d/m/y', strtotime($date_from)) . ' to ' . date('d/m/y', strtotime($date_to)) . '</p>		
				</div>					
			</div>
					
			<table class="tbl_det_prain">
				<thead>
					<tr>
						<th>NO</th>
						<th>Container No.</th>
						<th>ID Code</th>
						<th>Type</th>
						<th>L</th>
						<th>H</th>
						<th>Loading Vessel/Voyage</th>
						<th>Dest</th>
						<th>Date In</th>
						<th>Con</th>
						<th>Receiver</th>
						<th>DO No / Seal</th>
						<th>Time</th>
						<th>Trucker</th>
						<th>Vehicle Id</th>
					</tr>
				</thead>
				<tbody>';
			$no = 1;
			foreach ($result['data'] as $row) {
				$debitur = get_debitur($row['receiver']);			
				$html .= '
				<tr>
					<td>' . $no . '</td>
					<td>' . strtoupper($row['crno']) . '</td>
					<td>' . $row['id_code'] . '</td>
					<td>' . $row['ctype'] . '</td>
					<td>' . $row['clength'] . '</td>
					<td>' . $row['cheight'] . '</td>
					<td>' . $row['loading_ves'] . ' / ' . $row['loading_voy'] . '</td>
					<td>' . $row['dest'] . '</td>
					<td>' . $row['date_in'] . '</td>
					<td>' . $row['cond'] . '</td>
					<td>' . $debitur['name'] . '</td>
					<td>' . $row['do_no'] . ' / ' . $row['seal'] . '</td>
					<td>' . $row['time'] . '</td>
					<td>' . $row['trucker'] . '</td>
					<td>' . $row['vehicle_id'] . '</td>
				</tr>';
				$no++;				
			}
			$html .= '
			</tbody>
			</table>';

		}

		$html .= '
			</body>
		</html>';
		$mpdf->setHeader('|<h3>PT. CONTINDO RAYA<br>
					DAILY MOVEMENT REPORT (OUT)</h3>|PAGE {PAGENO}');
		$mpdf->WriteHTML($html);
		$mpdf->Output();
		die();
	}

	public function reportExcel($prcode = '', $date_from = '', $date_to = '', $hour_from = '', $hour_to = '')
	{
		check_exp_time();
		$hour_from = '';
		$hour_to = '';
		$result =   $this->getAllData($prcode, $date_from, $date_to, $hour_from, $hour_to);

		$exl = new Spreadsheet();
		// Judul Dokumen

		// Body Tabel

		if($prcode=="All") 
		{
			$exl->setActiveSheetIndex(0)
				->setCellValue('A1', 'DAILY MOVEMENT REPORT(OUT)');
			$exl->getActiveSheet()->mergeCells("A1:O1");
			$exl->getActiveSheet()->getStyle('A1:O1')->getFont()->setSize(20);

			$exl->getActiveSheet(0)->setCellValue('A3', 'Depot : CONTINDO - PADANG');
			$exl->getActiveSheet()->mergeCells("A3:O3");
			$exl->getActiveSheet(0)->setCellValue('A4', 'Gate In Date : ' . date('d/m/y', strtotime($date_from)) . ' to ' . date('d/m/y', strtotime($date_to)));
			$exl->getActiveSheet()->mergeCells("A4:O4");

			$exl->setActiveSheetIndex(0)
				->setCellValue('A5', 'NO')
				->setCellValue('B5', 'Container No.')
				->setCellValue('C5', 'ID Code')
				->setCellValue('D5', 'Type')
				->setCellValue('E5', 'L')
				->setCellValue('F5', 'H')
				->setCellValue('G5', 'Loading Vessel/Voyage')
				->setCellValue('H5', 'Dest')
				->setCellValue('I5', 'Date In')
				->setCellValue('J5', 'Con')
				->setCellValue('K5', 'Receiver')
				->setCellValue('L5', 'DO No / Seal')
				->setCellValue('M5', 'Time')
				->setCellValue('N5', 'Trucker')
				->setCellValue('O5', 'Vehicle Id');

			$grouping = array();
			foreach ($result['data'] as $row) {
				$grouping[$row['cpopr']][]=$row;
			}
			
			$j=0;
			$colhead = 3;
			$col=6;
			foreach ($grouping as $key=>$pr_code) {		
				if($col>6) {
					$col=$rows_subtot+1;
				}						
				$j++;
				$no = 0;
				foreach($pr_code as $prc) {
					$no++;
					$col++;
					$debitur = get_debitur($row['receiver']);	

					$exl->setActiveSheetIndex(0)
						->setCellValue('A' . $col, $no)
						->setCellValue('B' . $col, strtoupper($prc['crno']))
						->setCellValue('C' . $col, $prc['id_code'])
						->setCellValue('D' . $col, $prc['ctype'])
						->setCellValue('E' . $col, $prc['clength'])
						->setCellValue('F' . $col, $prc['cheight'])
						->setCellValue('G' . $col, $prc['loading_ves'] . ' / ' . $prc['loading_voy'])
						->setCellValue('H' . $col, $prc['dest'])
						->setCellValue('I' . $col, $prc['date_in'])
						->setCellValue('J' . $col, $prc['cond'])		
						->setCellValue('K' . $col, $debitur['name'])		
						->setCellValue('L' . $col, $prc['do_no'] . ' / ' . $prc['seal'])
						->setCellValue('M' . $col, $prc['time'])		
						->setCellValue('N' . $col, $prc['trucker'])		
						->setCellValue('O' . $col, $prc['vehicle_id']);		
					$rows_subtot=$col;
				}
				$row_prcode = $rows_subtot-count($pr_code);
				$exl->getActiveSheet()->setCellValue('A'.$row_prcode, $key);			
				$exl->getActiveSheet()->mergeCells("A" . $row_prcode . ":O" . $row_prcode);
				$exl->getActiveSheet()->getStyle("A" . $row_prcode . ":O" . $row_prcode)->getAlignment()->applyFromArray(['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT, 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, 'textRotation' => 0, 'wrapText' => TRUE]);							
				$exl->getActiveSheet()->getStyle("A" . $row_prcode . ":O" . $row_prcode)->getFont()->applyFromArray(['name' => 'Arial', 'bold' => TRUE, 'color' => ['rgb' => '000000']]);	
			}

		} else {
			$exl->setActiveSheetIndex(0)
				->setCellValue('A1', 'DAILY MOVEMENT REPORT(OUT)');
			$exl->getActiveSheet()->mergeCells("A1:O1");
			$exl->getActiveSheet()->getStyle('A1:O1')->getFont()->setSize(20);
			$exl->getActiveSheet(0)->setCellValue('A2', 'Container Operator : ' . $prcode);
			$exl->getActiveSheet()->mergeCells("A2:O2");

			$exl->getActiveSheet(0)->setCellValue('A3', 'Depot : CONTINDO - PADANG');
			$exl->getActiveSheet()->mergeCells("A3:O3");
			$exl->getActiveSheet(0)->setCellValue('A4', 'Gate In Date : ' . date('d/m/y', strtotime($date_from)) . ' to ' . date('d/m/y', strtotime($date_to)));
			$exl->getActiveSheet()->mergeCells("A4:O4");
			$exl->setActiveSheetIndex(0)
				->setCellValue('A5', 'NO')
				->setCellValue('B5', 'Container No.')
				->setCellValue('C5', 'ID Code')
				->setCellValue('D5', 'Type')
				->setCellValue('E5', 'L')
				->setCellValue('F5', 'H')
				->setCellValue('G5', 'Loading Vessel/Voyage')
				->setCellValue('H5', 'Dest')
				->setCellValue('I5', 'Date In')
				->setCellValue('J5', 'Con')
				->setCellValue('K5', 'Receiver')
				->setCellValue('L5', 'DO No / Seal')
				->setCellValue('M5', 'Time')
				->setCellValue('N5', 'Trucker')
				->setCellValue('O5', 'Vehicle Id');
			$no=0;
			$col=5;
			foreach ($result['data'] as $prc) {
				$no++;
				$col++;
				$debitur = get_debitur($prc['receiver']);

				$exl->setActiveSheetIndex(0)
					->setCellValue('A' . $col, $no)
					->setCellValue('B' . $col, strtoupper($prc['crno']))
					->setCellValue('C' . $col, $prc['id_code'])
					->setCellValue('D' . $col, $prc['ctype'])
					->setCellValue('E' . $col, $prc['clength'])
					->setCellValue('F' . $col, $prc['cheight'])
					->setCellValue('G' . $col, $prc['loading_ves'] . ' / ' . $prc['loading_voy'])
					->setCellValue('H' . $col, $prc['dest'])
					->setCellValue('I' . $col, $prc['date_in'])
					->setCellValue('J' . $col, $prc['cond'])		
					->setCellValue('K' . $col, $debitur['name'])		
					->setCellValue('L' . $col, $prc['do_no'] . ' / ' . $prc['seal'])
					->setCellValue('M' . $col, $prc['time'])		
					->setCellValue('N' . $col, $prc['trucker'])		
					->setCellValue('O' . $col, $prc['vehicle_id']);	
			}
		}

		//Style font
		
		$exl->getActiveSheet()->getStyle('A5:O5')->getFont()->applyFromArray(['name' => 'Arial', 'bold' => TRUE, 'color' => ['rgb' => '000000']]);

		// autosize Column

		foreach (range('A', 'O') as $columnID) {
			$exl->getActiveSheet()->getColumnDimension($columnID)
				->setAutoSize(true);
		}

		// style border
		// tabel_header

		$exl->getActiveSheet()->getStyle('A1:O1')->getAlignment()->applyFromArray([
			'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
			'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, 'textRotation' => 0, 'wrapText' => FALSE
		]);
		$exl->getActiveSheet()->getStyle('A2:O4')->getAlignment()->applyFromArray([
			'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
			'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, 'textRotation' => 0, 'wrapText' => FALSE
		]);

		// Tabel_content

		$exl->getActiveSheet()->getStyle('A5:O' . $col)->getAlignment()->applyFromArray([
			'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
			'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, 'textRotation' => 0, 'wrapText' => FALSE
		]);

		$exl->getActiveSheet()->getStyle('A5:O' . $col)->getBorders()->getAllBorders()->applyFromArray(['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]);

		$writer = new Xlsx($exl);
		$filename = 'DailyMovOutReport-' . date('Y-m-d-His');
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename=' . $filename . '.xlsx');
		header('Cache-Control: max-age=0');
		$writer->save('php://output');
		die();
	}
	// public function reportExcel($prcode = '', $date_from = '', $date_to = '', $hour_from = '', $hour_to = '')
	// {
	// 	check_exp_time();
	// 	$hour_from = '';
	// 	$hour_to = '';
	// 	$result =   $this->getAllData($prcode, $date_from, $date_to, $hour_from, $hour_to);
	// 	$exl = new Spreadsheet();
	// 	// Judul Dokumen
	// 	$exl->setActiveSheetIndex(0)
	// 		->setCellValue('A1', 'DAILY MOVEMENT REPORT (OUT)');
	// 	$exl->getActiveSheet()->mergeCells("A1:O1");
	// 	$exl->getActiveSheet()->getStyle('A1:O1')->getFont()->setSize(20);
	// 	$exl->getActiveSheet(0)->setCellValue('A2', 'Depot : CONTINDO - PADANG');
	// 	$exl->getActiveSheet()->mergeCells("A2:O2");
	// 	$exl->getActiveSheet(0)->setCellValue('A3', 'Container Operator : ' . $prcode);
	// 	$exl->getActiveSheet()->mergeCells("A3:O3");
	// 	$exl->getActiveSheet(0)->setCellValue('A4', 'Gate Out Date : ' . date('d/m/y', strtotime($date_from)) . ' to ' . date('d/m/y', strtotime($date_to)));
	// 	$exl->getActiveSheet()->mergeCells("A4:O4");
	// 	// Header Tabel
	// 	$exl->setActiveSheetIndex(0)
	// 		->setCellValue('A5', 'No')
	// 		->setCellValue('B5', 'Container No.')
	// 		->setCellValue('C5', 'ID Code')
	// 		->setCellValue('D5', 'Type')
	// 		->setCellValue('E5', 'L')
	// 		->setCellValue('F5', 'H')
	// 		->setCellValue('G5', 'Loading Vessel/Voyage')
	// 		->setCellValue('H5', 'Dest')
	// 		->setCellValue('I5', 'Date In')
	// 		->setCellValue('J5', 'Con')
	// 		->setCellValue('K5', 'Receiver')
	// 		->setCellValue('L5', 'DO No. / Seal')
	// 		->setCellValue('M5', 'Time')
	// 		->setCellValue('N5', 'Trucker')
	// 		->setCellValue('O5', 'Vehicle Id');
	// 	// Body Tabel	
	// 	$col = 6;
	// 	$i = 1;
	// 	$num = 5;
	// 	foreach ($result['data'] as $row) {
	// 		$debitur = get_debitur($row['receiver']);
	// 		$exl->setActiveSheetIndex(0)
	// 			->setCellValue('A' . $col, $i)
	// 			->setCellValue('B' . $col, strtoupper($row['crno']))
	// 			->setCellValue('C' . $col, $row['id_code'])
	// 			->setCellValue('D' . $col, $row['ctype'])
	// 			->setCellValue('E' . $col, $row['clength'])
	// 			->setCellValue('F' . $col, $row['cheight'])
	// 			->setCellValue('G' . $col, $row['loading_ves'] . ' / ' . $row['loading_voy'])
	// 			->setCellValue('H' . $col, $row['dest'])
	// 			->setCellValue('I' . $col, $row['date_in'])
	// 			->setCellValue('J' . $col, $row['cond'])
	// 			->setCellValue('K' . $col, $debitur['name'])
	// 			->setCellValue('L' . $col, $row['do_no'])
	// 			->setCellValue('M' . $col, $row['time'])
	// 			->setCellValue('N' . $col, $row['trucker'])
	// 			->setCellValue('O' . $col, $row['vehicle_id']);			
	// 		$col++;
	// 		$i++;
	// 		$num = $num + 1;
	// 	}

	// 	//Style font
	// 	$exl->getActiveSheet()->getStyle('A5:O5')->getFont()->applyFromArray(['name' => 'Arial', 'bold' => TRUE, 'color' => ['rgb' => '000000']]);
	// 	// autosize Column
	// 	foreach (range('A', 'O') as $columnID) {
	// 		$exl->getActiveSheet()->getColumnDimension($columnID)
	// 			->setAutoSize(true);
	// 	}
	// 	// style border
	// 	// tabel_header
	// 	$exl->getActiveSheet()->getStyle('A1:O1')->getAlignment()->applyFromArray([
	// 		'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
	// 		'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, 'textRotation' => 0, 'wrapText' => FALSE
	// 	]);
	// 	$exl->getActiveSheet()->getStyle('A2:O4')->getAlignment()->applyFromArray([
	// 		'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
	// 		'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, 'textRotation' => 0, 'wrapText' => FALSE
	// 	]);
	// 	// Tabel_content
	// 	$exl->getActiveSheet()->getStyle('A5:O' . $num)->getAlignment()->applyFromArray([
	// 		'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
	// 		'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, 'textRotation' => 0, 'wrapText' => FALSE
	// 	]);

	// 	$exl->getActiveSheet()->getStyle('A5:O' . $num)->getBorders()->getAllBorders()->applyFromArray(['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]);

	// 	$writer = new Xlsx($exl);
	// 	$filename = 'DailyMovInReport-' . date('Y-m-d-His');
	// 	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	// 	header('Content-Disposition: attachment;filename=' . $filename . '.xlsx');
	// 	header('Cache-Control: max-age=0');
	// 	$writer->save('php://output');
	// 	die();
	// }

}
