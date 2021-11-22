<?php
namespace Modules\ContainerType\Controllers;

class ContainerType extends \CodeIgniter\Controller
{
	private $client;
	public function __construct()
	{
		helper(['url','form','app']);
		$this->client = api_connect();
	}

	// api => containertype/list
	public function index()
	{
		$data = [];
		$response = $this->client->request('GET','containertype/list',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			// 'json'=>[
			// 	'start'=>0,
			// 	'rows'=>10
			// ]
		]);

		$result = json_decode($response->getBody()->getContents(),true);	
		$data['ctype'] = $result['data']['datas'];
		/*if ($this->request->isAJAX()) {
			// $request = Services::request();

			$data = [];
			$i=1;
			foreach($result['data'] as $row){
				$dt = [];

				$dt[] = $i;
				$dt[] = $row['ctcode'];
				$dt[] = $row['ctdesc'];
				$data[] = $dt;
				$i++;
			}
            $output = [
                'draw' => $this->request->getPost('draw'),
                'recordsTotal' => count($result['data']),
                'recordsFiltered' => count($result['data']),          	
                'data' => $data
            ];			
			echo json_encode($result['data']);die();

		}
		*/

		return view('Modules\ContainerType\Views\index',$data);
	}

	public function view($ctcode)
	{
		$response = $this->client->request('GET','containertype/listOne',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'form_params' => [
				'idContainerType' => $ctcode,
			]
		]);

		$result = json_decode($response->getBody()->getContents(), true);	
		$data['ctype'] = $result['data'];
		return view('Modules\ContainerType\Views\view',$data);
	}	

	public function add()
	{
		$data = [];

		if ($this->request->isAJAX()) 
		{
	    	$ctcode = $this->request->getPost('ctcode');
	    	$ctdesc = $this->request->getPost('ctdesc');

			$validate = $this->validate([
	            'ctcode' 	=> 'required',
	            'ctdesc'  => 'required'
	        ]);			
		
		    if ($this->request->getMethod() === 'post' && $validate)
		    {

				$response = $this->client->request('POST','containertype/create',[
					'headers' => [
						'Accept' => 'application/json',
						'Authorization' => session()->get('login_token')
					],
					'form_params' => [
						'ctCode' =>$ctcode,
						'ctDesc' =>$ctdesc,
					]
				]);

				$result = json_decode($response->getBody()->getContents(), true);	
				$dt_err="";

				if(isset($result['status']) && ($result['status']=="Failled"))
				{
					$data['message'] = $result['message'];
					echo json_encode($data);die();				
				}

				$msg = $result['data'];
				$dataFlash['sukses'] = [
					'ctcode'	=> $msg['ctCode'],
					'ctdesc'		=> $msg['ctDesc']
				];

				session()->setFlashdata('sukses','Success, Container Type Saved.');
				$data['message'] = "success";
				echo json_encode($data);die();

			}
			else 
			{
		    	$data['message'] = \Config\Services::validation()->listErrors();
		    	echo json_encode($data);die();			
			}			
		}

		return view('Modules\ContainerType\Views\add',$data);

	}	

	public function edit($ctcode)
	{
		$data = [];

		if ($this->request->isAJAX()) 
		{
	    	$ctcode = $this->request->getPost('ctcode');
	    	$ctdesc = $this->request->getPost('ctdesc');

			$validate = $this->validate([
	            'ctcode' 	=> 'required',
	            'ctdesc'  => 'required'
	        ]);			

		    if ($this->request->getMethod() === 'post' && $validate)
		    {
				$response = $this->client->request('POST','containertype/update',[
					'headers' => [
						'Accept' => 'application/json',
						'Authorization' => session()->get('login_token')
					],
					'json' => [
						'ctCode' => $ctcode,
						'ctDesc' => $ctdesc
					]
				]);

				$result = json_decode($response->getBody()->getContents(), true);	
				$dt_err="";

				if(isset($result['errors']))
				{
					foreach($result['errors'] as $err) {
						foreach($err as $er) {
							$dt_err .= $er ."<br>";	
						}
					}

					$data['message'] = $dt_err;
					echo json_encode($data);die();				
				}

				$msg = $result['message'];

				session()->setFlashdata('sukses', $msg);
				$data['message'] = "success";
				echo json_encode($data);die();
			}
			else
			{
		    	$data['message'] = \Config\Services::validation()->listErrors();
		    	echo json_encode($data);die();					
			}
		} 

		$response = $this->client->request('GET','containertype/listOne',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'form_params' => [
				'idContainerType' => $ctcode,
			]
		]);

		$result = json_decode($response->getBody()->getContents(), true);	
		$data['ctype'] = $result['data'];
		return view('Modules\ContainerType\Views\edit',$data);
	}	

	public function delete($ctcode)
	{
		$response = $this->client->request('DELETE','containertype/delete',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'json' => [
				'idContainerType' => $ctcode
			]
		]);

		$result = json_decode($response->getBody()->getContents(), true);	
		$success = $result['success'];
		if($success){
			session()->setFlashdata('sukses','Success, Container Type : <b>'.$ctcode.'<b> Deleted.');
			return redirect()->to('containertype');
		}
	}	

	public function ajax_ctype()
	{
		$response = $this->client->request('GET','containertype/list',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			]
		]);

		$result = json_decode($response->getBody()->getContents(), true);	
		$json = $response->getBody()->getContents();	
		echo $json['data']['datas'];
	}


	public function pager($offset,$limit) 
	{
		$response = $this->client->request('GET','containertype/list',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'json'=>[
				'start'=>$offset,
				'rows'=>$limit
			]
		]);

		return json_decode($response->getBody()->getContents(),true);		
	}

	// DATATABLE REQUEST
    // private function getDatatablesQuery()
    // {
	   //  $table = 'users';
	   //  $column_order = ['id', 'name', 'email'];
	   //  $column_search = ['name', 'email'];
	   //  $order = ['id' => 'DESC'];
	   //  $request;
	   //  $db;
	   //  $dt;    	
    //     $i = 0;
    //     foreach ($this->column_search as $item) {
    //         if ($this->request->getPost('search')['value']) {
    //             if ($i === 0) {
    //                 $this->dt->groupStart();
    //                 $this->dt->like($item, $this->request->getPost('search')['value']);
    //             } else {
    //                 $this->dt->orLike($item, $this->request->getPost('search')['value']);
    //             }
    //             if (count($this->column_search) - 1 == $i)
    //                 $this->dt->groupEnd();
    //         }
    //         $i++;
    //     }

    //     if ($this->request->getPost('order')) {
    //         $this->dt->orderBy($this->column_order[$this->request->getPost('order')['0']['column']], $this->request->getPost('order')['0']['dir']);
    //     } else if (isset($this->order)) {
    //         $order = $this->order;
    //         $this->dt->orderBy(key($order), $order[key($order)]);
    //     }
    // }

    // public function countFiltered()
    // {
    //     $this->getDatatablesQuery();
    //     return $this->dt->countAllResults();
    // }

/*
search in array (niru LIKE %string% nya mysql) 
-------------------------------------------------

$input = preg_quote('bl', '~'); // don't forget to quote input string!
$data = array('orange', 'blue', 'green', 'red', 'pink', 'brown', 'black');

$result = preg_grep('~' . $input . '~', $data);

*/
}