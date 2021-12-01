<?php
namespace Modules\RepoOut\Controllers;

use App\Libraries\MyPaging;

class RepoOut extends \CodeIgniter\Controller
{
	private $client;
	public function __construct()
	{
		helper(['url','form','app']);
		$this->client = api_connect();
	}

	function list_data(){		
		$module = service('uri')->getSegment(1);
		$search = ($this->request->getPost('search') && $this->request->getPost('search') != "")?$this->request->getPost('search'):"";
        $offset = ($this->request->getPost('start')!= 0)?$this->request->getPost('start'):0;
        $limit = ($this->request->getPost('rows') !="")? $this->request->getPost('rows'):10;
        // $sort_dir = $this->get_sort_dir();		
		// PULL data from API
		$response = $this->client->request('GET','dataListReports/listAllRepoOuts',[
				'headers' => [
					'Accept' => 'application/json',
					'Authorization' => session()->get('login_token')
				],
				'query' => [
					'offset' => (int)$offset,
					'limit'	=> (int)$limit
				]
			]);

		$result = json_decode($response->getBody()->getContents(), true);
		
        $output = array(
            "draw" => $this->request->getPost('draw'),
            "recordsTotal" => @$result['data']['Total'],
            "recordsFiltered" => @$result['data']['Total'],
            "data" => array()
        );
		$no = ($offset !=0)?$offset+1 :1;
		foreach ($result['data']['datas'] as $k=>$v) {
			$btn_list="";
            $record = array(); 
            $record[] = $no;
            $record[] = $v['CRNO'];
            $record[] = $v['CPID'];
            $record[] = $v['CPIPRANO'];
            $record[] = $v['CPOPR'];
            $record[] = $v['CPIPRATGL'];
            $record[] = $v['VESID'];
            $record[] = $v['CPIVOY'];
			
			$btn_list .= '<a href="'.site_url().'/repoout/view/'.$v['CRNO'].'" class="btn btn-xs btn-primary btn-tbl">View</a>';	
			if (has_privilege_check($module, '_update') == true) {				
				$btn_list .= '<a href="#" id="editPraIn" class="btn btn-xs btn-success btn-tbl">Edit</a>';
			}

			if (has_privilege_check($module, '_printpdf') == true) {
				$btn_list .= '<a href="#" class="btn btn-xs btn-info btn-tbl">Print</a>';
			}

			if (has_privilege_check($module, '_delete')== true) {
				$btn_list .= '<a href="#" id="deletePraIn" class="btn btn-xs btn-danger btn-tbl" data-kode="'.$v['CRNO'].'">Delete</a>';		
			}
            $record[] = '<div>'.$btn_list.'</div>';
            $no++;

            $output['data'][] = $record;
        } 
        
        echo json_encode($output);
		
	}

	public function index()
	{
		check_exp_time();
		$module = service('uri')->getSegment(1);
		has_privilege($module, "_view");
		define("has_insert", has_privilege_check($module, '_insert'));
		define("has_approval", has_privilege_check($module, '_approval'));
		define("has_edit", has_privilege_check($module, '_update'));
		define("has_delete", has_privilege_check($module, '_delete'));
		define("has_print", has_privilege_check($module, '_printpdf'));

		$token = get_token_item();
		$user_id = $token['id'];
		$group_id = $token['groupId'];
		// $prcode = $token['prcode'];

		$data = [];
		$paging = new MyPaging();
		$limit = 10;
		$endpoint = 'dataListReports/listAllRepoOuts';
		$data['data'] = $paging->paginate($endpoint,$limit,'repout');
		$data['pager'] = $paging->pager;
		$data['nomor'] = $paging->nomor($this->request->getVar('page_repout'), $limit);
		$data['page_title'] = "Repo Out";
		$data['page_subtitle'] = "Repo Out Page";
		return view('Modules\RepoOut\Views\index',$data);
	}
}