<?php

namespace Modules\Edi1\Controllers;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;

class Edi1 extends \CodeIgniter\Controller
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
		//print_r($data);
		//die();

		return view('Modules\Edi1\Views\add', $data);
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
		return view('Modules\Edi1\Views\view', $data);
	}


	public function printEDI()
	{
		/*
		{{base_url}}/api/v1/edi/codecoin?prcode=MRT&vdate=2022-02-17&starthour=00:00:00&endhour=23:59:00

		{{base_url}}/api/v1/edi/codecopti?prcode=MRT&vdate=2022-02-17

		{{base_url}}/api/v1/edi/codecoupdate?prcode=MRT&vdate=2022-02-17&starthour=00:00:00&endhour=23:59:00

		{{base_url}}/api/v1/edi/codecoout?prcode=MRT&vdate=2022-02-17
		*/

		$token = get_token_item();
		$user_id = $token['id'];
		$group_id = $token['groupId'];
		$prcode = $token['prcode'];

		if($this->request->isAJAX())
		{
			// echo var_dump(date('Y-m-d', strtotime($_POST['startDate'])));die();
			/*******************
				Extracting IN
			*******************/
			$req1 = $this->client->request('GET','edi/codecoin',[
				'headers' => [
					'Accept' => 'application/json',
					'Authorization' => session()->get('login_token')
				],
				'query' => [
					'prcode' => $_POST['prcode'],
					'vdate' => date('Y-m-d', strtotime($_POST['startDate'])),
					'starthour' => $_POST['startHour'],
					'endhour' => $_POST['endHour']
				]
			]);
			$res1 = json_decode($req1->getBody()->getContents(), true);
			if(isset($res1['status']) && $res1['status']=="Failled") 
			{
				$data['status'] = "Failled";
				$data['message'] = $res1['status'];
				echo json_encode($data);die();
			}
			else 
			{
				/*******************
					Extracting PTI
				*******************/
				$req2 = $this->client->request('GET','edi/codecopti',[
					'headers' => [
						'Accept' => 'application/json',
						'Authorization' => session()->get('login_token')
					],
					'query' => [
						'prcode' => $_POST['prcode'],
						'vdate' => date('Y-m-d', strtotime($_POST['startDate']))
					]
				]);
				$res2 = json_decode($req2->getBody()->getContents(), true);
				if(isset($res2['status']) && $res2['status']=="Failled") 
				{
					$data['status'] = "Failled";
					$data['message'] = $res2['status'];
					echo json_encode($data);die();
				}
				else 
				{
					/*******************
						Extracting UPDATE
					*******************/
					$req3 = $this->client->request('GET','edi/codecoupdate',[
						'headers' => [
							'Accept' => 'application/json',
							'Authorization' => session()->get('login_token')
						],
						'query' => [
							'prcode' => $_POST['prcode'],
							'vdate' => date('Y-m-d', strtotime($_POST['startDate'])),
							'starthour' => $_POST['startHour'],
							'endhour' => $_POST['endHour']
						]
					]);
					$res3 = json_decode($req3->getBody()->getContents(), true);
					if(isset($res3['status']) && $res3['status']=="Failled") 
					{
						$data['status'] = "Failled";
						$data['message'] = $res3['status'];
						echo json_encode($data);die();
					}
					else 
					{
						/******************
							Extracting OUT
						*******************/
						$req4 = $this->client->request('GET','edi/codecoout',[
							'headers' => [
								'Accept' => 'application/json',
								'Authorization' => session()->get('login_token')
							],
							'query' => [
								'prcode' => $_POST['prcode'],
								'vdate' => date('Y-m-d', strtotime($_POST['startDate']))
							]
						]);	
						$res4 = json_decode($req4->getBody()->getContents(), true);
						if(isset($res4['status']) && $res4['status']=="Failled") 
						{
							$data['status'] = "Failled";
							$data['message'] = $res4['status'];
							echo json_encode($data);die();
						} else {
							$data['status'] = "success";
							$data['message'] = "PROCESSING DATA SUCCESS";
							// $data['data'] = $datanya;
							echo json_encode($data);die();						
						}					
					}
				}		
			}
		}

	}
}
