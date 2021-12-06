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

	function list_data(){		
		$search = ($this->request->getPost('search[value]') != "")?$this->request->getPost('search[value]'):"";
        $offset = ($this->request->getPost('start')!= 0)?$this->request->getPost('start'):0;
        $limit = ($this->request->getPost('rows') !="")? $this->request->getPost('rows'):10;
        $sort_column = $this->request->getPost('order[0][column]');	
        $sort_type = $this->request->getPost('order[0][dir]');	
        $orderColumn = '';
        if ($sort_column == '0') {
        	$orderColumn = '';
        } elseif ($sort_column == '1') {
        	$orderColumn = 'ctcode';
        } elseif ($sort_column == '2') {
        	$orderColumn = 'ctdesc';
        } 
		// PULL data from API
		$response = $this->client->request('GET','containertype/list',[
				'headers' => [
					'Accept' => 'application/json',
					'Authorization' => session()->get('login_token')
				],
				'json' => [
					'start' => (int)$offset,
					'rows'	=> (int)$limit,
					'search'=> (string)$search,
					'orderColumn' => (string)$orderColumn,
					'orderType' => (string)$sort_type
				]
			]);

		$result = json_decode($response->getBody()->getContents(), true);
        $output = array(
            "draw" => $this->request->getPost('draw'),
            "recordsTotal" => @$result['data']['count'],
            "recordsFiltered" => @$result['data']['count'],
            "data" => array()
        );
		$no = ($offset !=0)?$offset+1 :1;
		foreach ($result['data']['datas'] as $k=>$v) {
			$btn_list="";
            $record = array(); 
            $record[] = $no;
            $record[] = $v['ctcode'];
            $record[] = $v['ctdesc'];
			
			$btn_list .= '<a href="'.site_url().'/containertype/view/'.$v['ctcode'].'" class="btn btn-xs btn-primary btn-tbl">View</a>';	
			$btn_list .= '<a href="'.site_url().'/containertype/edit/'.$v['ctcode'].'" class="btn btn-xs btn-success btn-tbl">Edit</a>';
			$btn_list .= '<a href="'.site_url().'/containertype/delete/'.$v['ctcode'].'" class="btn btn-xs btn-danger btn-tbl" id="delete" data-kode="'.$v['ctcode'].'>Delete</a>';
            $record[] = '<div>'.$btn_list.'</div>';
            $no++;

            $output['data'][] = $record;
        } 
        
        echo json_encode($output);
		
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
					'ctcode'	=> $msg['ctcode'],
					'ctdesc'		=> $msg['ctdesc']
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
		if(isset($result['status']) && ($result['status']=="Failled"))
		{
			$data['message'] = $result['message'];
			echo json_encode($data);die();				
		}

		session()->setFlashdata('sukses','Hapus data sukses.');
		$data['message'] = "success";
		echo json_encode($data);die();
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