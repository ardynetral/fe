<?php

namespace Modules\RptReceipt\Controllers;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;

class RptReceipt extends \CodeIgniter\Controller
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
		// has_privilege($module, "_view");
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
		return view('Modules\RptReceipt\Views\index', $data);
	}


	public function getAllData($date_from, $date_to)
	{
		$query_params = [
			'tgl1' => $date_from,
			'tgl2'  => $date_to
		];

		$response = $this->client->request('GET', 'reports/reportKwitansi', [
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'query' => $query_params,
		]);
		$result = json_decode($response->getBody()->getContents(), true);
		if(isset($result['status']) && $result['status']=="Failled") {
			$data['data'] = [];
		} else {
			$data['data'] = $result['data']['resultData'];
		}

		return $data;
	}

	public function reportPdf($date_from = '', $date_to = '')
	{
		check_exp_time();

		$result =   $this->getAllData($date_from, $date_to);
		// dd($result);die();
		$mpdf = new \Mpdf\Mpdf(['setAutoTopMargin' => 'stretch']);
		$html = '';
		$html .= '
		<html>
			<head>
				<title>Receipt Report</title>

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
						vertical-align: text-top;
					}
					.line-space{border-bottom:1px solid #dddddd;margin:30px 0;}
				</style>
			</head>
		';

		$html .= '<body>

		<table class="tbl_det_prain" width="100%">
			<thead>
				<tr>
					<th>NO</th>
					<th>NAMA DEBITUR</th>
					<th>DATE</th>
					<th>NO KWITANSI</th>
					<th>NO FAKTUR</th>
					<th>URAIAN</th>
					<th>CUR.</th>
					<th>JUMLAH</th>
					<th>SKADA</th>
				</tr>
			</thead>
			<tbody>';

		$no = 1;
		$grandTotal = 0;
		foreach ($result['data'] as $row) {
			$orderdate = ($row['cpipratgl']!=NULL) ? date('d/m/Y',strtotime($row['cpipratgl'])):"";  
			$receptdate = ($row['receptdate']!=NULL) ? date('d/m/Y',strtotime($row['receptdate'])):"";  
			$invoice_number = 'KW' . date("Ymd", strtotime($row['cpipratgl'])) . str_repeat("0", 8 - strlen($row['praid'])) . $row['praid'];
			// $invoice_number = 'KW' . date("Ymd", strtotime($row['cpipratgl']));
			$subtotal = $row['tot_lolo']+$row['biaya_adm']+$row['total_pajak'];
			$html .= '
					<tr>
						<td rowspan="4">' . $no . '</td>
						<td rowspan="4">' . $row['cpideliver'] . '</td>
						<td rowspan="4">' . $receptdate . '</td>
						<td rowspan="4">' . $invoice_number . '</td>
						<td rowspan="4"></td>
					</tr>
					<tr>
						<td>LIFT OFF - 20FT</td>
						<td>IDR</td>
						<td>' . number_format($row['tot_lolo'],2) . '</td>
						<td></td>
					</tr>
					<tr>
						<td>ADMINISTRATION</td>
						<td>IDR</td>
						<td>' . number_format($row['biaya_adm'],2) . '</td>
						<td></td>
					</tr>
					<tr>
						<td>PPN 10%</td>
						<td>IDR</td>
						<td>' . number_format($row['total_pajak'],2) . '</td>
						<td></td>
					</tr>
					<tr>
						<td colspan="7" class="t-right">SUBTOTAL</td><td colspan="2">' .number_format($subtotal,2). '</td>
					</tr>';
			$no++;
			$grandTotal = $grandTotal+$subtotal;
		}
		$html .= '
					<tr>
						<th colspan="7" class="t-right">TOTAL</th>
						<th colspan="2">'.number_format($grandTotal,2).'</th>
					</tr>
				</tbody>
			</table>

		</body>
		</html>
		';
		$mpdf->setHeader('|<h3>
			PT. CONTINDO RAYA<br>
			DAFTAR KWITANSI<br>
			PERIODE ' . date('d-m-Y', strtotime($date_from)) . ' S/D ' . date('d-m-Y', strtotime($date_to)) .'</h3>|PAGE {PAGENO}');
		$mpdf->setFooter('PRINTED ON ' . date('d/m/Y'));		
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
			->setCellValue('A1', 'DAFTAR KWITANSI');
		$exl->getActiveSheet()->mergeCells("A1:I1");
		$exl->getActiveSheet()->getStyle('A1:I1')->getFont()->setSize(20);
		$exl->getActiveSheet(0)->setCellValue('A2', 'DEPOT : CONTINDO - PADANG');
		$exl->getActiveSheet()->mergeCells("A2:I2");
		$exl->getActiveSheet(0)->setCellValue('A3', 'PERIODE : ' . date('d/m/y', strtotime($date_from)) . ' to ' . date('d/m/y', strtotime($date_to)));
		$exl->getActiveSheet()->mergeCells("A3:I3");
		// Header Tabel
		$exl->setActiveSheetIndex(0)
			->setCellValue('A4', 'No')
			->setCellValue('B4', 'NAMA DEBITUR')
			->setCellValue('C4', 'DATE')
			->setCellValue('D4', 'NO KWITANSI')
			->setCellValue('E4', 'NO FAKTUR')
			->setCellValue('F4', 'URAIAN')
			->setCellValue('G4', 'CUR.')		
			->setCellValue('H4', 'JUMLAH')		
			->setCellValue('I4', 'SKADA');	

		// Body Tabel
		$col = 5;
		$i = 1;
		$num = 4;
		$row_lolo = 5;
		$row_adm = 6;
		$row_ppn = 7;
		$row_subtot = 8;
		$subTotal = 0;
		$grandTotal = 0;
		foreach ($result['data'] as $row) {
			$orderdate = ($row['cpipratgl']!=NULL) ? date('d/m/Y',strtotime($row['cpipratgl'])):"";  
			$receptdate = ($row['receptdate']!=NULL) ? date('d/m/Y',strtotime($row['receptdate'])):"";  
			// $invoice_number = 'KW' . date("Ymd", strtotime($row['cpipratgl'])) . str_repeat("0", 8 - strlen($row['praid'])) . $row['praid'];
			$invoice_number = 'KW' . date("Ymd", strtotime($row['cpipratgl']));
			$subtotal = $row['tot_lolo']+$row['biaya_adm']+$row['total_pajak'];
			if($row_lolo>5) {
				$row_lolo = $row_subtot+1;
				$row_adm = $row_lolo+1;
				$row_ppn = $row_adm+1;
				$row_subtot = $row_ppn+1;
			}									
								
			$exl->setActiveSheetIndex(0)
				->setCellValue('A' . $row_lolo, $i)
				->setCellValue('A' . $row_adm, "")
				->setCellValue('A' . $row_ppn, "")
				->setCellValue('A' . $row_subtot, "SUB TOTAL")

				->setCellValue('B' . $row_lolo, $row['cpideliver'])
				->setCellValue('B' . $row_adm, "")
				->setCellValue('B' . $row_ppn, "")

				->setCellValue('C' . $row_lolo, $receptdate)
				->setCellValue('C' . $row_adm, "")
				->setCellValue('C' . $row_ppn, "")

				->setCellValue('D' . $row_lolo, $invoice_number)
				->setCellValue('D' . $row_adm, "")
				->setCellValue('D' . $row_ppn, "")

				->setCellValue('E' . $row_lolo, "")
				->setCellValue('E' . $row_adm, "")
				->setCellValue('E' . $row_ppn, "")

				->setCellValue('F' . $row_lolo, "LIFT OFF - 20FT")
				->setCellValue('F' . $row_adm, "ADMINISTRATION")
				->setCellValue('F' . $row_ppn, "PPN 10%")

				->setCellValue('G' . $row_lolo, "IDR")			
				->setCellValue('G' . $row_adm, "IDR")			
				->setCellValue('G' . $row_ppn, "IDR")

				->setCellValue('H' . $row_lolo, number_format($row['tot_lolo'],2))
				->setCellValue('H' . $row_adm, number_format($row['biaya_adm'],2))			
				->setCellValue('H' . $row_ppn, number_format($row['total_pajak'],2))		
				->setCellValue('H' . $row_subtot, number_format($subtotal,2))

				->setCellValue('I' . $row_lolo, "")	
				->setCellValue('I' . $row_adm, "")	
				->setCellValue('I' . $row_ppn, "")	
				->setCellValue('I' . $row_subtot, "");	

			$col++;
			$i++;
		
			$row_lolo++;
			$grandTotal = $grandTotal+$subtotal;	
			$exl->getActiveSheet()->mergeCells("A".$row_subtot.":G".$row_subtot);
			$exl->getActiveSheet()->getStyle("A".$row_subtot.":G".$row_subtot)->getAlignment()->applyFromArray([
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
				'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, 'textRotation' => 0, 'wrapText' => FALSE
			]);
			$exl->getActiveSheet()->getStyle("H".$col.":H".$col)->getAlignment()->applyFromArray([
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
				'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, 'textRotation' => 0, 'wrapText' => FALSE
			]);					

		}
		$row_total = $row_subtot+1;
		$exl->getActiveSheet()->mergeCells("A".$row_total.":G".$row_total);
		$exl->getActiveSheet()->getStyle("A".$row_total.":G".$row_total)->getAlignment()->applyFromArray([
			'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
			'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, 'textRotation' => 0, 'wrapText' => FALSE
		]);
		$exl->getActiveSheet()->getStyle("A".$row_total.":I".$row_total)->getFont()->applyFromArray(['name' => 'Arial', 'bold' => TRUE, 'color' => ['rgb' => '000000']]);
		$exl->getActiveSheet()->getStyle("A".$row_total.":I".$row_total)->getBorders()->getAllBorders()->applyFromArray(['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]);
		$exl->setActiveSheetIndex(0)
				->setCellValue('A' . $row_total, "TOTAL")
				->setCellValue('H' . $row_total, number_format($grandTotal,2));

		//Style font
		$exl->getActiveSheet()->getStyle('A4:I4')->getFont()->applyFromArray(['name' => 'Arial', 'bold' => TRUE, 'color' => ['rgb' => '000000']]);
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
		$exl->getActiveSheet()->getStyle('A2:I3')->getAlignment()->applyFromArray([
			'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
			'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, 'textRotation' => 0, 'wrapText' => FALSE
		]);
		// Tabel_content

		$exl->getActiveSheet()->getStyle('A4:I' . $row_subtot)->getBorders()->getAllBorders()->applyFromArray(['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]);

		$writer = new Xlsx($exl);
		$filename = 'Receipt_Report-' . date('Y-m-d-His');
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename=' . $filename . '.xlsx');
		header('Cache-Control: max-age=0');
		$writer->save('php://output');
		die();
	}
}
