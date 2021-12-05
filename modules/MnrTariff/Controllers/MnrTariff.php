<?php
namespace Modules\MnrTariff\Controllers;

class MnrTariff extends \CodeIgniter\Controller
{
	private $client;
	public function __construct()
	{
		helper(['url','form','app']);
		$this->client = api_connect();
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

		$data['page_title'] = "MNR Tariff";
		$data['page_subtitle'] = "MNR Tariff page";
		return view('Modules\MnrTariff\Views\index',$data);
	}

	public function list_data() {

		$search = ($this->request->getPost('search') && $this->request->getPost('search') != "")?$this->request->getPost('search'):"";
        $offset = ($this->request->getPost('start')!= 0)?$this->request->getPost('start'):0;
        $limit = ($this->request->getPost('rows') !="")? $this->request->getPost('rows'):10;

		$response = $this->client->request('GET','damage_tariffs/getAllData',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'query' => [
				'offset' => $offset,
				'limit' => $limit
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
            $record[] = $v['prcode'];
            // $record[] = $v['dmno'];
			$record[] = $v['dmdate'];
			$record[] = $v['dmexpdate'];
			$record[] = $v['dmremarks'];

			// $btn_list .='<a href="'.site_url('mnrtariff/view/').$v["prcode"].'" id="" class="btn btn-xs btn-primary btn-table" data-praid="">view</a>&nbsp;';						
			// $btn_list .='<a href="'.site_url('mnrtariff/edit/').$v["prcode"].'" class="btn btn-xs btn-success btn-table">edit</a>&nbsp;';
			// $btn_list .='<a href="#" id="deleteRow_'.$no.'" class="btn btn-xs btn-danger btn-table delete" data-kode="'.$v['prcode'].'">delete</a>';			
            $record[] = '<div>'.$btn_list.'</div>';
            $no++;

            $output['data'][] = $record;
        } 
        echo json_encode($output);		
	}
}