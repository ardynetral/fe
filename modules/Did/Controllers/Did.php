<?php

namespace Modules\Did\Controllers;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;

class Did extends \CodeIgniter\Controller
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
		$data = [];
		return view('Modules\Did\Views\add', $data);
	}

	public function view($code)
	{
		$data = [];
		return view('Modules\Did\Views\view', $data);
	}

	public function getAllData()
	{
		$response = $this->client->request('GET', 'reports/rptDepoInfoDaily', [
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			]
		]);
		$result = json_decode($response->getBody()->getContents(), true);
		$data = $result['data']['datas'][0];
		return $data;
	}

	public function reportPdf()
	{

		$result =   $this->getAllData();
		$mpdf = new \Mpdf\Mpdf(['setAutoTopMargin' => 'pad']);
		$html = '';

		$html .= '
		<html>
			<head>
				<title>Report Depo Info Daily</title>

				<style>
					body{font-family: Arial;font-size:12px;}
					.page-header{display:block;border-bottom:2px solid #aaa;padding:0;min-height:30px;margin-bottom:30px;}
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

		<table class="tbl_det_prain" width="100%">
			<thead>
				<tr>
					<th>Principal</th>
					<th>Month</th>
					<th>Daily</th>
					<th>Total</th>
				</tr>
			</thead>
			<tbody>';
		$no = 1;
		$grouping = [];
		foreach ($result as $key=>$row) {
			$grouping[$row['principal']][]=$row;
		}
		
		$grandtotal = 0;
		$j=0;
		foreach ($grouping as $pr_code) {
			$subtotal = 0;
			foreach($pr_code as $prc) {
				$html .= '
				<tr>
					<td class="t-center">'.$prc['principal'].'</td>
					<td class="t-center">'.$prc['dinfo_month'].'</td>
					<td class="t-center">'.$prc['dinfo_daily'].'</td>
					<td class="t-center">'.$prc['total'].'</td>
				</tr>';
				$subtotal=$subtotal+$prc['total'];
			}
			$grandtotal=$grandtotal+$subtotal;
			$html .= '<tr><th class="t-right" colspan="3">SUB TOTAL</th><th>'.$subtotal.'</th></tr>';
		$j++;
		}

		$html .= '<tr><th class="t-right" colspan="3">GRAND TOTAL</th><th>' . $grandtotal . '</th></tr>';		
		$html .= '
			</tbody>
		</table>

		</body>
		</html>
		';
		$mpdf->setHeader('|<h3>PT. CONTINDO RAYA<br>
					DEPO INFO DAILY REPORT</h3>|PAGE {PAGENO}');
		$mpdf->setFooter('PRINTED ON ' . date('d/m/Y'));		
		$mpdf->WriteHTML($html);
		$mpdf->Output();
		die();
	}

	public function reportExcel()
	{
		$result =   $this->getAllData();
		$exl = new Spreadsheet();
		// Judul Dokumen
		$exl->setActiveSheetIndex(0)
			->setCellValue('A1', 'Depo Info Daily Report');
		$exl->getActiveSheet()->mergeCells("A1:D1");
		$exl->getActiveSheet()->getStyle('A1:D1')->getFont()->setSize(20);
		// Header Tabel
		$exl->setActiveSheetIndex(0)
			->setCellValue('A2', 'Principal')
			->setCellValue('B2', 'Info Month')
			->setCellValue('C2', 'Info Daily')
			->setCellValue('D2', 'Total');
		$exl->getActiveSheet()->getStyle('A1:D1')->getAlignment()->applyFromArray(['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, 'textRotation' => 0, 'wrapText' => TRUE]);
		// Body Tabel
		$grouping = [];
		foreach ($result as $key=>$row) {
			$grouping[$row['principal']][]=$row;
		}

		$col = 3;
		$rows=3;
		$grandtotal = 0;

		foreach ($grouping as $pr_code) {
			if($col>3) {
				$col=$rows_subtot+1;
			}
			$subtotal = 0;

			foreach ($pr_code as $key=>$row) {
				$exl->getActiveSheet()
					->setCellValue('A' . $col, $row['principal'])
					->setCellValue('B' . $col, $row['dinfo_month'])
					->setCellValue('C' . $col, $row['dinfo_daily'])
					->setCellValue('D' . $col, $row['total']);
				$col++;
				$subtotal=$subtotal+$row['total'];	
				$rows_subtot=$col;
				$exl->getActiveSheet()->setCellValue('A'.$rows_subtot, 'SUB TOTAL');
				$exl->getActiveSheet()->setCellValue('D'.$rows_subtot, $subtotal);
			}
			$exl->getActiveSheet()->mergeCells("A" . $rows_subtot . ":C" . $rows_subtot);
			$exl->getActiveSheet()->getStyle("A" . $rows_subtot . ":D" . $rows_subtot)->getAlignment()->applyFromArray(['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT, 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, 'textRotation' => 0, 'wrapText' => TRUE]);					
			$grandtotal=$grandtotal+$subtotal;
		}

		$endrow = $rows_subtot+1;
		$exl->setActiveSheetIndex(0)->setCellValue('A' . $endrow, 'GRAND TOTAL');
		$exl->setActiveSheetIndex(0)->setCellValue('D' . $endrow, $grandtotal);
		$exl->getActiveSheet()->mergeCells("A" . $endrow . ":C" . $endrow);
		$exl->getActiveSheet()->getStyle("A" . $endrow . ":D" . $endrow)->getFont()->applyFromArray(['name' => 'Arial', 'bold' => TRUE, 'color' => ['rgb' => '000000']]);
		$exl->getActiveSheet()->getStyle("A" . $endrow . ":D" . $endrow)->getAlignment()->applyFromArray(['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT, 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, 'textRotation' => 0, 'wrapText' => TRUE]);	

		// autosize Column
		$sheet = $exl->getActiveSheet();
		foreach ($sheet->getColumnIterator() as $column) {
		    $sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
		}
		// font
		$exl->getActiveSheet()->getStyle('A2:D2')->getFont()->applyFromArray(['name' => 'Arial', 'bold' => TRUE, 'color' => ['rgb' => '000000']]);

		// border
		$exl->getActiveSheet()->getStyle('A2:D' . $endrow)->getBorders()->getAllBorders()->applyFromArray(['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]);

		$writer = new Xlsx($exl);
		$filename = 'DailyReport-' . date('Y-m-d-His');
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename=' . $filename . '.xlsx');
		header('Cache-Control: max-age=0');
		$writer->save('php://output');
		die();
	}
}
