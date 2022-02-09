<?php

namespace Modules\Edi2\Controllers;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;

class Edi2 extends \CodeIgniter\Controller
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
		return view('Modules\Edi2\Views\add', $data);
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
		return view('Modules\Edi2\Views\view', $data);
	}


	public function printEDI()
	{
	}
}
