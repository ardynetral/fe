<?php
namespace Modules\Groups\Controllers;

class Groups extends \CodeIgniter\Controller
{
	private $client;
	public function __construct()
	{
		helper(['url','form','app','\Modules\Module\Helpers\hmodule']);
		$this->client = api_connect();
	}

	// api => groups/allGroups
	public function index()
	{

		$module = service('uri')->getSegment(1);
		has_privilege($module, "_view"); 
		// echo var_dump($has_privilege);die();
		$response = $this->client->request('GET','groups/allGroups',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			]
		]);

		$result = json_decode($response->getBody()->getContents(), true);	
		$data['ugroup'] = $result['data'];
		return view('Modules\Groups\Views\index',$data);
	}

/* 
NOTE
- create by GroupId
- jika groupId belum ada privilege, create
- jika goupId sudah ada priviLege, Update
*/
	// public function insert_privilege($groupId)
	// {
	// 	/*
	// 	{
	// 	    "group_id": "4",
	// 	    "module_id": "1",
	// 	    "has_insert": "1",
	// 	    "has_update": "1",
	// 	    "has_delete": "1",
	// 	    "has_view": "1",
	// 	    "has_approval": "0",
	// 	    "has_printpdf": "1",
	// 	    "has_printxls": "1"
	// 	}
	// 	*/		
	// 	if(in_array($groupId,$this->group_in_privilege())) {
	// 		return redirect()->to('groups/edit_privilege/'.$groupId);
	// 	}

	// 	if ($this->request->isAJAX()) 
	// 	{
	// 		$module_id[] = $_POST['module_id'];
	// 		// $has_insert = (isset($_POST['has_insert'])?$_POST['has_insert']:'0');
	// 		// $has_update = (isset($_POST['has_update'])?$_POST['has_update']:'0');
	// 		// $has_delete = (isset($_POST['has_delete'])?$_POST['has_delete']:'0');
	// 		$n=0;
	// 		$nom = [];
	// 		foreach( $module_id  as $key) {
	// 		  // $has_view[$n] = (isset($_POST['has_view'][$n])?$_POST['has_view'][$n]:'0');
	// 			$nom[] = $key . (isset($_POST['has_view'][$n])?$_POST['has_view'][$n]:'0');
	// 		  $n++;
	// 		}			
	// 		  echo var_dump($nom);
	// 		die();

	// 		$group_id = $this->request->getPost('group_id');
	// 		$module_id = $this->request->getPost('module_id');
	// 		$has_view = $this->request->getPost('has_view');
	// 		$has_insert = $this->request->getPost('has_insert');
	// 		$has_update = $this->request->getPost('has_update');
	// 		$has_delete = $this->request->getPost('has_delete');
	// 		$has_approval = $this->request->getPost('has_approval');
	// 		$has_printpdf = $this->request->getPost('has_printpdf');
	// 		$has_printxls = $this->request->getPost('has_printxls');

	// 		$validate = $this->validate([
	//             'module_id'	=> 'required',
	//             'group_id'  => 'required'
	//         ]);			

	// 		if ($this->request->getMethod() === 'post' && $validate) 
	// 		{
	// 			$response = $this->client->request('POST','privilege/create',[
	// 				'headers' => [
	// 					'Accept' => 'application/json',
	// 					'Authorization' => session()->get('login_token')
	// 				],
	// 				'form_params' => [
	// 					'group_id' => (int)$group_id,
	// 					'module_id' => (int)$module_id,
	// 					'has_view' => (int)$has_view,
	// 					'has_insert' => (int)(isset($has_insert)?$has_insert:'0'),
	// 					'has_update' => (int)(isset($has_update)?$has_update:'0'),
	// 					'has_delete' => (int)(isset($has_delete)?$has_delete:'0'),
	// 					'has_approval' => (int)(isset($has_approval)?$has_approval:'0'),
	// 					'has_printpdf' => (int)(isset($has_printpdf)?$has_printpdf:'0'),
	// 					'has_printxls' => (int)(isset($has_printxls)?$has_printxls:'0'),
	// 				]
	// 			]);
				
	// 			$result = json_decode($response->getBody()->getContents(),true);	
	// 			if(isset($result['status']) && ($result['status']=="Failled"))
	// 			{
	// 				$data['status'] = $result['message'];
	// 				echo json_encode($data);die();				
	// 			}

	// 			session()->setFlashdata('sukses','Success, Privilege Saved.');
	// 			$data['message'] = "success";
	// 			$data['group_id'] = $_POST['group_id'];
	// 			echo json_encode($data);die();				
	// 		} else {
	// 			$data['message'] = \Config\Services::validation()->listErrors();
	// 			echo json_encode($data);die();						
	// 		}

	// 	}

	// 	$data = [];
	// 	$response = $this->client->request('GET','privilege/listModule',[
	// 		'headers' => [
	// 			'Accept' => 'application/json',
	// 			'Authorization' => session()->get('login_token')
	// 		]
	// 	]);
		
	// 	$dt_arr = [];
	// 	$result = json_decode($response->getBody()->getContents(),true);	
	// 	$dt_arr = $result['data'];

		
	// 	$tbody = "";
	// 	$no = 1;
	// 	foreach ($dt_arr as $parent) {

	// 			if($parent['modules']['module_parent']==0) {

	// 				$tbody .= '<tr>'; 
	// 				$tbody .= '<td>' . $parent['module_id'] .'</td>';
	// 				$tbody .= '<th>' . $parent['modules']['module_name'] .'</th>';
	// 				$tbody .= '<td>
	// 					<input type="hidden" name="module_id[]" class="module_id" value="'.$parent['module_id'].'">
	// 					<input type="checkbox" name="has_view[]" class="has_view" value="0">&nbsp;View
	// 					</td>';
	// 				$tbody .= '<td></td>';
	// 				$tbody .= '<td></td>';
	// 				$tbody .= '<td></td>';
	// 				$tbody .= '</tr>'; 
	// 				// $num=$no+1;

	// 				foreach($dt_arr as $child) {
	// 					if($parent['modules']['module_id']==$child['modules']['module_parent']) {

	// 						$tbody .= '<tr>';
	// 						$tbody .= '<td>' . $child['module_id'] .'</td>';
	// 						$tbody .= '<td style="padding-left:40px;">' . $child['modules']['module_name'] . '</td>';
	// 						$tbody .= '<td>
	// 							<input type="hidden" name="module_id[]" class="module_id" value="'.$child['module_id'].'">
	// 							<input type="checkbox" name="has_view[]" class="has_view" value="0">&nbsp;View</td>';
	// 						$tbody .= '<td>
	// 							<input type="checkbox" name="has_insert[]" class="has_insert" value="0">&nbsp;Insert</td>';
	// 						$tbody .= '<td>
	// 							<input type="checkbox" name="has_update[]" class="has_update" value="0">&nbsp;Update</td>';
	// 						$tbody .= '<td>
	// 							<input type="checkbox" name="has_delete[]" class="has_delete" value="0">&nbsp;Delete</td>';
	// 						$tbody .= '</tr>'; 
	// 					}
	// 						// $num++;
	// 				}
	// 			}
	// 		$no++;
	// 	$data['privId'] = $parent['privilege_id'];
	// 	}

	// 	$data['data'] = isset($result['data'])?$result['data']:"";
	// 	$data['ugroup'] = $groupId;
	// 	$data['tbody'] = $tbody;
	// 	return view('Modules\Groups\Views\set_privilege',$data);
	// }	

	public function set_privilege($groupId)
	{
		/*
		{
		    "id": "1",
		    "group_id": "4",
		    "module_id": "1",
		    "has_insert": "1",
		    "has_update": "1",
		    "has_delete": "1",
		    "has_approval": "0",
		    "has_view": "1",
		    "has_printpdf": "1",
		    "has_printxls": "1"
		}
		*/
		if ($this->request->isAJAX()) 
		{
			$privilege_id	= $this->request->getPost('privilege_id');
			$group_id	= $this->request->getPost('group_id');
			$module_id 	= $this->request->getPost('module_id');
			$has_view 	= $this->request->getPost('has_view');
			$has_insert = $this->request->getPost('has_insert');
			$has_update = $this->request->getPost('has_update');
			$has_delete = $this->request->getPost('has_delete');
			$has_approval = $this->request->getPost('has_approval');
			$has_printpdf = $this->request->getPost('has_printpdf');
			$has_printxls = $this->request->getPost('has_printxls');
						
			if($has_view!="") {
				$akses = 'has_view';
				$val_akses = $has_view;
			} elseif ($has_insert!="") {
				$akses = 'has_insert';
				$val_akses = $has_insert;
			} else if ($has_update!="") {
				$akses = 'has_update';
				$val_akses = $has_update;
			} else if ($has_delete!="") {
				$akses = 'has_delete';
				$val_akses = $has_delete;
			} else if ($has_approval!="") {
				$akses = 'has_approval';
				$val_akses = $has_approval;
			} else if ($has_printpdf!="") {
				$akses = 'has_printpdf';
				$val_akses = $has_printpdf;
			} else if ($has_printxls!="") {
				$akses = 'has_printxls';
				$val_akses = $has_printxls;
			}

			$form_params = [
				'id' => $privilege_id,
				'group_id' => $group_id,
				'module_id' => $module_id,
				$akses => $val_akses
			];

			$response = $this->client->request('POST','privilege/update',[
				'headers' => [
					'Accept' => 'application/json',
					'Authorization' => session()->get('login_token')
				],
				'form_params' => $form_params
			]);
			
			$result = json_decode($response->getBody()->getContents(),true);	
			if(isset($result['status']) && ($result['status']=="Failled"))
			{
				$data['status'] = $result['status'];
				$data['message'] = $result['message'];
				echo json_encode($data);die();				
			}

			session()->setFlashdata('sukses','Success, Privilege Saved.');
			$data['status'] = "success";
			$data['message'] = $result['message'];
			echo json_encode($data);die();
		}

		$data = $this->get_privilege_list($groupId);
		return view('Modules\Groups\Views\set_privilege',$data);
	}	

	// function module_access($groupId,$access) {
	// 	if ($groupId==$child['group_id']) {
	// 		switch($access) {
	// 			case 'view':

	// 		}

	// 		$set_selected = "checked";
	// 	} else {
	// 		$set_selected = "";
	// 	}

	// }
	
	public function group_in_privilege() 
	{
		$response = $this->client->request('GET','privilege/list',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			]
		]);
		
		$dt_arr = [];
		$result = json_decode($response->getBody()->getContents(),true);	
		
		foreach($result['data'] as $row) {
			$dt_arr[] = $row['group_id'];
		}
		return array_unique($dt_arr);
	}

	public function get_privilege_list($groupId)
	{
		$data = [];
		$response = $this->client->request('GET','privilege/list',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			]
		]);
		
		$dt_arr = [];
		$result = json_decode($response->getBody()->getContents(),true);	
		$dt_arr = $result['data'];

		
		$tbody = "";
		$no = 1;
		// echo var_dump()
		foreach ($dt_arr as $parent) {
			$list_group[] = $parent['group_id'];
			if($parent['group_id']==$groupId) {
				if($parent['modules']['module_parent']==0) {

					$tbody .= '<tr>'; 
					$tbody .= '<td>' . $parent['module_id'] .'</td>';
					$tbody .= '<th>' . $parent['modules']['module_name'] .'</th>';
					$tbody .= '<td></td>';
					$tbody .= '<td>
						<div class="onoffswitch">
							<input 
							type="checkbox" 
							name="has_view[]" 
							class="onoffswitch-checkbox has_view" 
							id="has_view_'.$parent['module_id'].'"
							value="'.chk_view($groupId,$parent)['val'].'" 
							data-privid="'. $parent['privilege_id'] .'"
							data-modid="'. $parent['module_id'] .'"' . 
							chk_view($groupId,$parent)['checked'] .'>
							<label class="onoffswitch-label" for="has_view_'.$parent['module_id'].'">
								<span class="onoffswitch-inner"></span>
								<span class="onoffswitch-switch"></span>
							</label>
						</div>
					</td>';
					$tbody .= '<td></td>';
					$tbody .= '<td></td>';
					$tbody .= '<td></td>';
					$tbody .= '<td></td>';
					$tbody .= '<td></td>';
					$tbody .= '</tr>'; 
					// $num=$no+1;

					foreach($dt_arr as $child) {
						if($child['group_id']==$groupId) {
						if($parent['modules']['module_id']==$child['modules']['module_parent']) {

							$tbody .= '<tr>';
							$tbody .= '<td>' . $child['module_id'] .'</td>';
							$tbody .= '<td style="padding-left:40px;">' . $child['modules']['module_name'] . '</td>';
							// $tbody .= '<td><input type="checkbox" value="" class="">&nbsp;Forbiden</td>';
							$tbody .= '<td>
								<div class="onoffswitch">
									<input 
									type="checkbox" 
									name="has_approval[]" 
									class="onoffswitch-checkbox has_approval" 
									id="has_approval_'.$child['module_id'].'"
									value="'.chk_approval($groupId,$child)['val'].'" 
									data-privid="'. $child['privilege_id'] .'"
									data-modid="'. $child['module_id'] .'"' . 
									chk_approval($groupId,$child)['checked'] .'>
									<label class="onoffswitch-label" for="has_approval_'.$child['module_id'].'">
										<span class="onoffswitch-inner"></span>
										<span class="onoffswitch-switch"></span>
									</label>
								</div>
								</td>';							
							$tbody .= '<td>
								<div class="onoffswitch">
									<input 
									type="checkbox" 
									name="has_view[]" 
									class="onoffswitch-checkbox has_view" 
									id="has_view_'.$child['module_id'].'"
									value="'.chk_view($groupId,$child)['val'].'" 
									data-privid="'. $child['privilege_id'] .'"
									data-modid="'. $child['module_id'] .'"' . 
									chk_view($groupId,$child)['checked'] .'>
									<label class="onoffswitch-label" for="has_view_'.$child['module_id'].'">
										<span class="onoffswitch-inner"></span>
										<span class="onoffswitch-switch"></span>
									</label>
								</div>
								</td>';
							$tbody .= '<td>
								<div class="onoffswitch">
									<input 
									type="checkbox" 
									name="has_insert[]" 
									class="onoffswitch-checkbox has_insert" 
									id="has_insert_'.$child['module_id'].'"
									value="'.chk_insert($groupId,$child)['val'].'" 
									data-privid="'. $child['privilege_id'] .'"
									data-modid="'. $child['module_id'] .'"' . 
									chk_insert($groupId,$child)['checked'] .'>
									<label class="onoffswitch-label" for="has_insert_'.$child['module_id'].'">
										<span class="onoffswitch-inner"></span>
										<span class="onoffswitch-switch"></span>
									</label>
								</div>
								</td>';
							$tbody .= '<td>
								<div class="onoffswitch">
									<input 
									type="checkbox" 
									name="has_update[]" 
									class="onoffswitch-checkbox has_update" 
									id="has_update_'.$child['module_id'].'"
									value="'.chk_update($groupId,$child)['val'].'" 
									data-privid="'. $child['privilege_id'] .'"
									data-modid="'. $child['module_id'] .'"' . 
									chk_update($groupId,$child)['checked'] .'>
									<label class="onoffswitch-label" for="has_update_'.$child['module_id'].'">
										<span class="onoffswitch-inner"></span>
										<span class="onoffswitch-switch"></span>
									</label>
								</div>
								</td>';
							$tbody .= '<td>
								<div class="onoffswitch">
									<input 
									type="checkbox" 
									name="has_delete[]" 
									class="onoffswitch-checkbox has_delete" 
									id="has_delete_'.$child['module_id'].'"
									value="'.chk_delete($groupId,$child)['val'].'" 
									data-privid="'. $child['privilege_id'] .'"
									data-modid="'. $child['module_id'] .'"' . 
									chk_delete($groupId,$child)['checked'] .'>
									<label class="onoffswitch-label" for="has_delete_'.$child['module_id'].'">
										<span class="onoffswitch-inner"></span>
										<span class="onoffswitch-switch"></span>
									</label>
								</div>
								</td>';
							$tbody .= '<td>
								<div class="onoffswitch">
									<input 
									type="checkbox" 
									name="has_printpdf[]" 
									class="onoffswitch-checkbox has_printpdf" 
									id="has_printpdf_'.$child['module_id'].'"
									value="'.chk_printpdf($groupId,$child)['val'].'" 
									data-privid="'. $child['privilege_id'] .'"
									data-modid="'. $child['module_id'] .'"' . 
									chk_printpdf($groupId,$child)['checked'] .'>
									<label class="onoffswitch-label" for="has_printpdf_'.$child['module_id'].'">
										<span class="onoffswitch-inner"></span>
										<span class="onoffswitch-switch"></span>
									</label>
								</div>
								</td>';	
							$tbody .= '<td>
								<div class="onoffswitch">
									<input 
									type="checkbox" 
									name="has_printxls[]" 
									class="onoffswitch-checkbox has_printxls" 
									id="has_printxls_'.$child['module_id'].'"
									value="'.chk_printxls($groupId,$child)['val'].'" 
									data-privid="'. $child['privilege_id'] .'"
									data-modid="'. $child['module_id'] .'"' . 
									chk_printxls($groupId,$child)['checked'] .'>
									<label class="onoffswitch-label" for="has_printxls_'.$child['module_id'].'">
										<span class="onoffswitch-inner"></span>
										<span class="onoffswitch-switch"></span>
									</label>
								</div>
								</td>';
							$tbody .= '</tr>'; 
						}
						}
							// $num++;
					}
				}
			}
			$no++;
		$data['privId'] = $parent['privilege_id'];
		}

		$data['data'] = isset($result['data'])?$result['data']:"";
		$data['ugroup'] = $groupId;
		$data['tbody'] = $tbody;

		return $data;		
	}

	/* Jika group belum meiliki data di tabel privilege
	   !!! Uncomment the function if you want to use it !!! 
	*/
	public function insert_group_privilege($groupId) 
	{
		// $response = $this->client->request('GET','modules/getAllData',[
		// 	'headers' => [
		// 		'Accept' => 'application/json',
		// 		'Authorization' => session()->get('login_token')
		// 	]
		// ]);
		
		// $dt_arr = [];
		// $result = json_decode($response->getBody()->getContents(),true);	
		// $module_arr = $result['data']['datas'];
		// if($module_arr) {
		// 	foreach($module_arr as $row) {
		// 		$this->client->request('POST','privilege/create',[
		// 			'headers' => [
		// 				'Accept' => 'application/json',
		// 				'Authorization' => session()->get('login_token')
		// 			],
		// 			'form_params' => [
		// 				'group_id' => $groupId,
		// 				'module_id' => $row['module_id'],
		// 				'has_view' => '0',
		// 				'has_insert' => '0',
		// 				'has_update' => '0',
		// 				'has_delete' => '0',
		// 				'has_approval' => '0',
		// 				'has_printpdf' => '0',
		// 				'has_printxls' => '0',
		// 			]
		// 		]);			
		// 	}
		// }		
	}

}

