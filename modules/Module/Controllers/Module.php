<?php
namespace Modules\Module\Controllers;

class Module extends \CodeIgniter\Controller
{
	private $client;
	public function __construct()
	{
		helper(['url','form','app']);
		$this->client = api_connect();
	}

	public function index()
	{
		$data = [];
		$response = $this->client->request('GET','modules/getAllData',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			]
		]);
		
		$dt_arr = [];
		$result = json_decode($response->getBody()->getContents(),true);	
		$dt_arr = $result['data']['datas'];
		// dd($dt_arr);
		$groupId = get_token_item()['groupId'];
		$tbody = "";
		$no = 1;
		foreach ($dt_arr as $parent) {

			// if(($parent['group_id']==$groupId) && ($parent['has_view']==1)) {

				if($parent['module_parent']==0) {

					$tbody .= '<tr>'; 
					$tbody .= '<td>' . $no .'</td>';
					$tbody .= '<td>' . $parent['module_name'] .'</td>';
					$tbody .= '<td>' . $parent['module_config'] . '</td>';
					$tbody .= '<td>
						<a href="' . site_url('module/edit/'.$parent['module_id']) . '" class="btn btn-success btn-xs">edit</a>
						<a href="#" class="btn btn-danger btn-xs">delete</a>
						</td>';
					$tbody .= '</tr>'; 
					$nu=$no+1;
					foreach($dt_arr as $child) {

						if($parent['module_id']==$child['module_parent']) {

							$tbody .= '<tr>';
							$tbody .= '<td>' . $nu .'</td>';
							$tbody .= '<td style="padding-left:40px;">' . $child['module_name'] . '</td>';
							$tbody .= '<td>' . $child['module_config'] . '</td>';
							$tbody .= '<td>
								<a href="' . site_url('module/edit/'.$child['module_id']) . '" class="btn btn-success btn-xs">edit</a>
								<a href="#" class="btn btn-danger btn-xs">delete</a>
								</td>';
							$tbody .= '</tr>'; 
							$nu++;
						}
					}
				}
			// }
			$no++;
		}

		$data['data'] = isset($result['data'])?$result['data']:"";
		$data['tbody'] = $tbody;
		return view('Modules\Module\Views\index',$data);
	}

	public function view($code)
	{
		$response = $this->client->request('GET','modules/getDetailData',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'form_params' => [
				'cnCode' => $code,
			]
		]);

		$result = json_decode($response->getBody()->getContents(), true);	
		$data['data'] = isset($result['data'])?$result['data']:"";
		return view('Modules\Module\Views\view',$data);
	}	

	public function add()
	{
		$data = [];

		if ($this->request->isAJAX()) 
		{
		    $form_params = [
		    	"module_parent" 	=> $_POST['module_parent'],
			    "module_var" 		=> $_POST['module_var'],
			    "module_name" 		=> $_POST['module_name'],
			    "module_description" => $_POST['module_description'],
			    "module_status" 	=> $_POST['module_status'],
			    "module_url" 		=> $_POST['module_url'],
			    "module_config" 	=> $_POST['module_config'],
			    "module_icon" 		=> $_POST['module_icon'],
			    "sort_index" 		=> $_POST['sort_index'],
			    "module_content" 	=> $_POST['module_content'],
			    "module_type" 		=> $_POST['module_type']
			];

			$validate = $this->validate([
		    	"module_parent" 	=> 'required',
			    "module_var" 		=> 'required',
			    "module_name" 		=> 'required',
			    "module_description" => 'required',
			    "module_status" 	=> 'required',
			    "module_url" 		=> 'required',
			    "module_config" 	=> 'required',
			    "module_icon" 		=> 'required',
			    "sort_index" 		=> 'required',
			    "module_content" 	=> 'required',
			    "module_type" 		=> 'required'
	        ]);			
		
		    if ($this->request->getMethod() === 'post' && $validate)
		    {

				$response = $this->client->request('POST','modules/createNewData',[
					'headers' => [
						'Accept' => 'application/json',
						'Authorization' => session()->get('login_token')
					],
					'form_params' => $form_params
				]);

				$result = json_decode($response->getBody()->getContents(), true);	

				if(isset($result['status']) && ($result['status']=="Failled"))
				{
					$data['message'] = $result['message'];
					echo json_encode($data);die();				
				}

				session()->setFlashdata('sukses','Success, Module Saved.');
				$data['message'] = "success";
				echo json_encode($data);die();

			}
			else 
			{
		    	$data['message'] = \Config\Services::validation()->listErrors();
		    	echo json_encode($data);die();			
			}			
		}		

		return view('Modules\Module\Views\add',$data);		
	}	

	public function edit($id)
	{
		$data = [];

		if ($this->request->isAJAX()) 
		{
		    $form_params = [
		    	"id" 				=> $_POST['module_id'],
		    	"module_parent" 	=> $_POST['module_parent'],
			    "module_var" 		=> $_POST['module_var'],
			    "module_name" 		=> $_POST['module_name'],
			    "module_description" => $_POST['module_description'],
			    "module_status" 	=> $_POST['module_status'],
			    "module_url" 		=> $_POST['module_url'],
			    "module_config" 	=> $_POST['module_config'],
			    "module_icon" 		=> $_POST['module_icon'],
			    "sort_index" 		=> $_POST['sort_index'],
			    "module_content" 	=> $_POST['module_content'],
			    "module_type" 		=> $_POST['module_type']
			];

			$validate = $this->validate([
		    	"module_id" 	=> 'required',
		    	"module_parent" 	=> 'required',
			    "module_var" 		=> 'required',
			    "module_name" 		=> 'required',
			    "module_description" => 'required',
			    "module_status" 	=> 'required',
			    "module_url" 		=> 'required',
			    "module_config" 	=> 'required',
			    "module_icon" 		=> 'required',
			    "sort_index" 		=> 'required',
			    "module_content" 	=> 'required',
			    "module_type" 		=> 'required'
	        ]);			
		
		    if ($this->request->getMethod() === 'post' && $validate)
		    {

				$response = $this->client->request('PUT','modules/updateData',[
					'headers' => [
						'Accept' => 'application/json',
						'Authorization' => session()->get('login_token')
					],
					'form_params' => $form_params
				]);

				$result = json_decode($response->getBody()->getContents(), true);	

				if(isset($result['status']) && ($result['status']=="Failled"))
				{
					$data['message'] = $result['message'];
					echo json_encode($data);die();				
				}

				session()->setFlashdata('sukses','Berhasil menyimpan data.');
				$data['message'] = "success";
				echo json_encode($data);die();

			}
			else 
			{
		    	$data['message'] = \Config\Services::validation()->listErrors();
		    	echo json_encode($data);die();			
			}			

		}		

		$response = $this->client->request('GET','modules/getDetailData',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'form_params' => [
				'id' => $id,
			]
		]);

		$result = json_decode($response->getBody()->getContents(), true);	
		$data['data'] = isset($result['data'])?$result['data']:"";
		return view('Modules\Module\Views\edit',$data);		
	}	

	public function delete($code)
	{
		if ($this->request->isAJAX()) 
		{		
			$response = $this->client->request('DELETE','modules/deleteData',[
				'headers' => [
					'Accept' => 'application/json',
					'Authorization' => session()->get('login_token')
				],
				'form_params' => [
					'cnCode' => $code,
				]
			]);

			$result = json_decode($response->getBody()->getContents(), true);	
			if(isset($result['status']) && ($result['status']=="Failled"))
			{
				$data['message'] = $result['message'];
				echo json_encode($data);die();				
			}

			session()->setFlashdata('sukses','Success, Country Code '.$code.' Deleted.');
			$data['message'] = "success";
			echo json_encode($data);die();
		}
	}		

}