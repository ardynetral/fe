<?php

namespace Modules\Directinterchange\Controllers;

class Directinterchange extends \CodeIgniter\Controller
{
	private $client;
	public function __construct()
	{
		helper(['url', 'form', 'app']);
		$this->client = api_connect();
	}

	function list_data()
	{
		$search = ($this->request->getPost('search[value]') != "")?$this->request->getPost('search[value]'):"";
        $offset = ($this->request->getPost('start')!= 0)?$this->request->getPost('start'):0;
        $limit = ($this->request->getPost('rows') !="")? $this->request->getPost('rows'):10;

		// PULL data from API
		/*
/gateOut/listInterChange
param limit, offset, search
		*/
		$response = $this->client->request('GET', 'gateOut/listInterChange', [
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'form_params' => [
				'limit'	=> (int)$limit,
				'offset' => (int)$offset,
				'search' => (string)$search
			]
		]);

		$result = json_decode($response->getBody()->getContents(), true);
		$output = array(
			"draw" => $this->request->getPost('draw'),
			"recordsTotal" => @$result['data']['Total'],
			"recordsFiltered" => @$result['data']['Total'],
			"data" => array()
		);
		$no = ($offset != 0) ? $offset + 1 : 1;
		foreach ($result['data']['datas'] as $k => $v) {
			$btn_list = "";
			$record = array();
			$record[] = $no;
			$record[] = $v['crgno'];
			$record[] = $v['chgopr'];
			$record[] = $v['chgcust'];
			$record[] = date("d-m-Y", strtotime($v['chgdate']));
			$record[] = $v['chgnote'];
			$no++;

			$output['data'][] = $record;
		}

		// echo varrdump($result);
		echo json_encode($output);
		die();
	}

	public function index()
	{
		check_exp_time();
		$data = [];
		$data['data'] = "";
		return view('Modules\Directinterchange\Views\index', $data);
	}

	public function add()
	{
		/*
			/gateOut/interchange
			param  crno, cpopr, cpcust, onhiredate
			pakai GET
		*/
		check_exp_time();
		$data = [];
		$token = get_token_item();
		$u_login = $token['username'];

		if ($this->request->isAJAX()) {

			$form_params= [
				"crno" => $this->request->getPost('crno'),
				"cpopr" => $this->request->getPost('prcode'),
				"cpcust" => $this->request->getPost('cpcust'),
				"onhiredate" => date("Y-m-d",strtotime($this->request->getPost('onhiredate'))),
				"chgnote" => $this->request->getPost('chgnote'),
			];

			// print_r($form_params);die();

			$validate = $this->validate([
				'crno' => 'required',
				'prcode' => 'required',
				'cpcust' => 'required',
				'onhiredate' => 'required',
			]);


			if ($this->request->getMethod() === 'post' && $validate) {

				$response = $this->client->request('POST', 'gateOut/interchange', [
					'headers' => [
						'Accept' => 'application/json',
						'Authorization' => session()->get('login_token')
					],
					'json' => $form_params
				]);

				$result = json_decode($response->getBody()->getContents(), true);
				// echo var_dump($result);die();
				if (isset($result['status']) && ($result['status'] == "Failled")) {
					$data['status'] = "Failled";
					$data['message'] = $result['message'];
					echo json_encode($data);
					die();
				}

				session()->setFlashdata('sukses', 'Success,  Saved.');
				$data['status'] = "success";
				$data['message'] = "Data Saved";
				echo json_encode($data);
				die();
			} else {
				$data['status'] = "Failled";
				$data['message'] = \Config\Services::validation()->listErrors();
				echo json_encode($data);
				die();
			}
		}


		return view('Modules\Directinterchange\Views\add', $data);
	}

	public function getContainer()
	{
		$crno = $_POST['crno'];


		$getContainer = $this->client->request('GET', 'containers/containerSearch', [
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'query' => [
				'crno' => $crno
			]
		]);
		$result = json_decode($getContainer->getBody()->getContents(), true);
		// echo var_dump($result);die();
		echo json_encode($result['data'][0]);
	}
}
