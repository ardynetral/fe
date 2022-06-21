<?php

namespace Modules\Dailymovementin\Controllers;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;

class Dailymovementin extends \CodeIgniter\Controller
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
		return view('Modules\Dailymovementin\Views\index', $data);
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

		$response = $this->client->request('GET', 'reports/rptDailyMovementIn', [
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'query' => $query_params,
		]);
		$result = json_decode($response->getBody()->getContents(), true);
		$data['data'] = $result['data']['datas'][0];
		return $data;
	}

	public function reportPdf($prcode = '', $date_from = '', $date_to = '', $hour_from = '', $hour_to = '')
	{
		check_exp_time();
		$hour_from = '';
		$hour_to = '';
		$result =   $this->getAllData($prcode, $date_from, $date_to, $hour_from, $hour_to);

		$mpdf = new \Mpdf\Mpdf(['setAutoTopMargin' => 'pad']);
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

		$html .= '<body>';

		if($prcode=="All") 
		{
			$no = 1;
			$grouping = array();
			foreach ($result['data'] as $row) {
				$grouping[$row['cpopr']][]=$row;
			}
			
			$j=0;
			foreach ($grouping as $key=>$pr_code) {
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
						Container Operator :   ' . $key . '
					</div>
				</div>
				
				<table class="tbl_det_prain">
				<thead>
					<tr>
						<th>NO</th>
						
						<th>Container No.</th>
						<th>Ty</th>
						<th>L/H</th>
						<th>Original Vessel/Voyage</th>
						<th>C-Box</th>
						<th>Deliverer</th>
						<th>Date</th>
						<th>Vehicle Id</th>
						<th>Remarks</th>
					</tr>
				</thead>
				<tbody>';

				$n=1;
				foreach($pr_code as $prc) {
					$debitur = get_debitur($row['cpideliver']);
					$html .= '
					<tr>
						<td>' . $n . '</td>
						<td>' . $prc['crno'] . '</td>
						<td>' . $prc['ctcode'] . '</td>
						<td>' . $prc['cclength'] . '/' . $prc['ccheight'] . '</td>
						<td>' . $prc['vesid'] . '/' . $prc['voyid'] . '</td>
						<td>' . $prc['svcond'] . '</td>
						<td>' . $debitur['name'] . '</td>
						<td>' . $prc['cpitgl'] . '</td>
						<td>' . $prc['cpinopol'] . '</td>
						<td>' . $prc['cpiremark'] . '</td>
					</tr>';
					$n++;
				}

				$html .= '
				</tbody>
				</table>';	

				$len = count($grouping);
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
				<div class="head-left">
						Depot : CONTINDO - PADANG
				</div>
				<div class="head-right">
				Gate In Date :' . date('d/m/y', strtotime($date_from)) . ' to ' . date('d/m/y', strtotime($date_to)) . '</p>		
				</div>
			</div>
			<div clas="row">
				<div class="head-left">
					Container Operator :   ' . $prcode . '
				</div>
			</div>
					
			<table class="tbl_det_prain">
				<thead>
					<tr>
						<th>NO</th>
						
						<th>Container No.</th>
						<th>Ty</th>
						<th>L/H</th>
						<th>Original Vessel/Voyage</th>
						<th>C-Box</th>
						<th>Deliverer</th>
						<th>Date</th>
						<th>Vehicle Id</th>
						<th>Remarks</th>
					</tr>
				</thead>
				<tbody>';

			$no = 1;
			foreach ($result['data'] as $row) {
				$debitur = get_debitur($row['cpideliver']);
				$html .= '
						<tr>
							<td>' . $no . '</td>
							<td>' . $row['crno'] . '</td>
							<td>' . $row['ctcode'] . '</td>
							<td>' . $row['cclength'] . '/' . $row['ccheight'] . '</td>
							<td>' . $row['vesid'] . '/' . $row['voyid'] . '</td>
							<td>' . $row['svcond'] . '</td>
							<td>' . $debitur['name'] . '</td>
							<td>' . $row['cpitgl'] . '</td>
							<td>' . $row['cpinopol'] . '</td>
							<td>' . $row['cpiremark'] . '</td>
						</tr>';
				$no++;
			}

			$html .= '
					</tbody>
				</table>';
		}


		$html .= '</body>
		</html>
		';

		$mpdf->setHeader('|<h3>PT. CONTINDO RAYA<br>
					DAILY MOVEMENT REPORT (IN)</h3>|PAGE {PAGENO}');
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
		$exl->setActiveSheetIndex(0)
			->setCellValue('A1', 'DAILY MOVEMENT REPORT');
		$exl->getActiveSheet()->mergeCells("A1:J1");
		$exl->getActiveSheet()->getStyle('A1:J1')->getFont()->setSize(20);
		$exl->getActiveSheet(0)->setCellValue('A2', 'Depot : CONTINDO - PADANG');
		$exl->getActiveSheet()->mergeCells("A2:J2");
		$exl->getActiveSheet(0)->setCellValue('A3', 'Container Operator : ' . $prcode);
		$exl->getActiveSheet()->mergeCells("A3:J3");
		$exl->getActiveSheet(0)->setCellValue('A4', 'Gate In Date : ' . date('d/m/y', strtotime($date_from)) . ' to ' . date('d/m/y', strtotime($date_to)));
		$exl->getActiveSheet()->mergeCells("A4:J4");
		// Header Tabel
		$exl->setActiveSheetIndex(0)
			->setCellValue('A5', 'No')
			->setCellValue('B5', 'Container No.')
			->setCellValue('C5', 'Ty')
			->setCellValue('D5', 'L/H')
			->setCellValue('E5', 'Original Vessel/Voyage')
			->setCellValue('F5', 'C-Box')
			->setCellValue('G5', 'Deliverer')
			->setCellValue('H5', 'Date')
			->setCellValue('I5', 'Vehicle Id')
			->setCellValue('J5', 'Remarks');
		// Body Tabel
		$col = 6;
		$i = 1;
		$num = 5;
		foreach ($result['data'] as $row) {
			$debitur = get_debitur($row['cpideliver']);
			$exl->setActiveSheetIndex(0)
				->setCellValue('A' . $col, $i)
				->setCellValue('B' . $col, $row['crno'])
				->setCellValue('C' . $col, $row['ctcode'])
				->setCellValue('D' . $col, $row['cclength'] . '/' . $row['ccheight'])
				->setCellValue('E' . $col, $row['vesid'] . '/' . $row['voyid'])
				->setCellValue('F' . $col, $row['svcond'])
				->setCellValue('G' . $col, $debitur['name'])
				->setCellValue('H' . $col, $row['cpitgl'])
				->setCellValue('I' . $col, $row['cpinopol'])
				->setCellValue('J' . $col, $row['cpiremark']);
			$col++;
			$i++;
			$num = $num + 1;
		}

		//Style font
		$exl->getActiveSheet()->getStyle('A5:J5')->getFont()->applyFromArray(['name' => 'Arial', 'bold' => TRUE, 'color' => ['rgb' => '000000']]);
		// autosize Column
		foreach (range('A', 'J') as $columnID) {
			$exl->getActiveSheet()->getColumnDimension($columnID)
				->setAutoSize(true);
		}
		// style border
		// tabel_header
		$exl->getActiveSheet()->getStyle('A1:J1')->getAlignment()->applyFromArray([
			'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
			'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, 'textRotation' => 0, 'wrapText' => FALSE
		]);
		$exl->getActiveSheet()->getStyle('A2:J4')->getAlignment()->applyFromArray([
			'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
			'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, 'textRotation' => 0, 'wrapText' => FALSE
		]);
		// Tabel_content
		$exl->getActiveSheet()->getStyle('A5:J' . $num)->getAlignment()->applyFromArray([
			'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
			'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, 'textRotation' => 0, 'wrapText' => FALSE
		]);

		$exl->getActiveSheet()->getStyle('A5:J' . $num)->getBorders()->getAllBorders()->applyFromArray(['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]);

		$writer = new Xlsx($exl);
		$filename = 'DailyMovInReport-' . date('Y-m-d-His');
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename=' . $filename . '.xlsx');
		header('Cache-Control: max-age=0');
		$writer->save('php://output');
		die();
	}
}
