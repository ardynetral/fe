<?php

namespace Modules\Containedmgout\Controllers;

class Containedmgout extends \CodeIgniter\Controller
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
		$response = $this->client->request('GET', 'containerHold/list', [
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'json' => [
				'offset' => (int)$offset,
				'limit'	=> (int)$limit,
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
			$record[] = $v['chorderno'];
			$record[] = $v['crno'];
			$record[] = $v['chtype'];
			$record[] = $v['chfrom'];
			$record[] = $v['chto'];
			$record[] = $v['chnote'];

			// $btn_list .= '<a href="' . site_url() . '/Containedmgout/view/' . $v['chorderno'] . '" class="btn btn-xs btn-primary btn-tbl">View</a>';

			$record[] = '<div>' . $btn_list . '</div>';
			$no++;

			$output['data'][] = $record;
		}

		echo json_encode($output);
		die();
	}

	public function index()
	{
		check_exp_time();
		$data = [];
		$data['data'] = "";
		return view('Modules\Containedmgout\Views\index', $data);
	}

	public function view($code)
	{
		check_exp_time();
		/*
		$response = $this->client->request('GET','containerHold/listOne',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'form_params' => [
				'id' => $code,
			]
		]);

		$result = json_decode($response->getBody()->getContents(), true);	
		$data['data'] = isset($result['data'])?$result['data']:"";
		return view('Modules\Containedmgout\Views\view',$data);
		*/
	}

	public function add()
	{
		check_exp_time();
		$data = [];
		$token = get_token_item();
		$u_login = $token['username'];

		if ($this->request->isAJAX()) {

			$chorderno = $this->request->getPost('chorderno');
			$crno = $this->request->getPost('crno');
			$chtype = $this->request->getPost('chtype');
			$chfrom = $this->request->getPost('chfrom');
			$chto = $this->request->getPost('chto');
			$chnote = $this->request->getPost('chnote');

			$validate = $this->validate([
				'chorderno' 	=> 'required',
				'crno'  => 'required'
			]);


			if ($this->request->getMethod() === 'post' && $validate) {

				$response = $this->client->request('POST', 'containerHold/insertData', [
					'headers' => [
						'Accept' => 'application/json',
						'Authorization' => session()->get('login_token')
					],
					'form_params' => [
						'chorderno' => $chorderno,
						'crno' => $crno,
						'chtype' => $chtype,
						'chfrom' => $chfrom,
						'chto' => $chto,
						'chnote' => $chnote,

					]
				]);

				$result = json_decode($response->getBody()->getContents(), true);

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
				$data['message'] = \Config\Services::validation()->listErrors();
				echo json_encode($data);
				die();
			}
		}


		return view('Modules\Containedmgout\Views\add', $data);
	}

	public function delete($code)
	{
		check_exp_time();
		if ($this->request->isAJAX()) {
			$response = $this->client->request('DELETE', 'Containedmgouts/deleteConHold', [
				'headers' => [
					'Accept' => 'application/json',
					'Authorization' => session()->get('login_token')
				],
				'form_params' => [
					'id' => $code,
				]
			]);

			$result = json_decode($response->getBody()->getContents(), true);
			if (isset($result['status']) && ($result['status'] == "Failled")) {
				$data['message'] = $result['message'];
				echo json_encode($data);
				die();
			}

			session()->setFlashdata('sukses', 'Success, Containedmgout ' . $code . ' Deleted.');
			$data['message'] = "success";
			echo json_encode($data);
			die();
		}
	}
}
