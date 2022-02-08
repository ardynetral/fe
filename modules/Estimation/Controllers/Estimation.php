<?php
namespace Modules\Estimation\Controllers;

use App\Libraries\MyPaging;

class Estimation extends \CodeIgniter\Controller
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
		$response = $this->client->request('GET','dataListReports/listAllEstimations',[
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
            $record[] = $v['RPCRNO'];
            $record[] = $v['PRCODE'];
            $record[] = $v['CPITGL'];
            $record[] = $v['SVSURDAT'];
            $record[] = $v['SVCOND'];
            $record[] = $v['RPNOEST'];
            $record[] = $v['RPVER'];
			
			$btn_list .= '<a href="#" id="" class="btn btn-xs btn-primary btn-tbl" data-praid="">view</a>';	
			if(has_privilege_check($module, '_update')==true):
				$btn_list .= '<a href="#" id="editPraIn" class="btn btn-xs btn-success btn-tbl">edit</a>';
			endif;
			
			if(has_privilege_check($module, '_printpdf')==true):
				$btn_list .= '<a href="#" class="btn btn-xs btn-info btn-tbl" data-praid="">print</a>';
			 endif;

			if(has_privilege_check($module, '_delete')==true):
				$btn_list .= '<a href="#" id="deletePraIn" class="btn btn-xs btn-danger btn-tbl">delete</a>';
			endif;
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
		

		$data['page_title'] = "Estimation";
		$data['page_subtitle'] = "Estimation Page";
		return view('Modules\Estimation\Views\index',$data);
	}

	public function add()
	{
		check_exp_time();
		$data = [];
		$data['data'] = "";
		$data['act'] = "Add";
		$data['page_title'] = "Estimation";
		$data['page_subtitle'] = "Estimation Page";
		$data['lccode_dropdown'] = $this->lccode_dropdown();
		$data['cmcode_dropdown'] = $this->cmcode_dropdown();
		$data['dycode_dropdown'] = $this->dycode_dropdown();
		$data['rmcode_dropdown'] = $this->rmcode_dropdown();
		return view('Modules\Estimation\Views\add',$data);
	}

	public function add_detail()
	{
		$data = [];
		$data['data'] = "";
		$data['act'] = "Add Detail";
		$data['page_title'] = "Estimation";
		$data['page_subtitle'] = "Estimation Page";
		return view('Modules\Estimation\Views\add_detail',$data);
	}	

	public function getDataEstimasi() 
	{
		$CRNO = $this->request->getPost('crno');
		if($this->request->isAjax()) {
			$response = $this->client->request('GET', 'estimasi/listOneCrno', [
				'headers' => [
					'Accept' => 'application/json',
					'Authorization' => session()->get('login_token')
				],
				'query' => [
					'crno' => $CRNO
				]
			]);	
			$result = json_decode($response->getBody()->getContents(), true);
			if($result['data']['dataOne']==null) {
				$data['status'] = "Failled";
				$data['message'] = "Data tidak ditemukan";
				echo json_encode($data);
				die();
			}
			$data['status'] = "success";
			$data['header'] = $result['data']['dataOne'][0];
			$data['detail'] = $this->fill_table_detail($result['data']['dataTwo']);
			// echo var_dump($result);die();		
			echo json_encode($data);
			die();
		}
	}

	public function fill_table_detail($data) {
		$html = "";
		if($data=="") {
			$html="";
		} else {
			$no=1;
			foreach($data as $row) {
				$html .= '<tr>';
				$html .= '<td>'.$no.'</td>';
				$html .= '<td>'.$row['cmcode'].'</td>';
				$html .= '<td>'.$row['dycode'].'</td>';
				$html .= '<td>'.$row['rmcode'].'</td>';
				$html .= '<td>'.$row['cmcode'].'</td>';
				$html .= '<td>'.$row['rdmhr'].'</td>';
				$html .= '<td>'.$row['rdsize'].'</td>';
				$html .= '<td>'.$row['muname'].'</td>';
				$html .= '<td>'.$row['rdqty'].'</td>';
				$html .= '<td>'.$row['rdmhr'].'</td>';
				$html .= '<td>'.$row['curr_symbol'].'</td>';
				$html .= '<td>'.$row['rddesc'].'</td>';
				$html .= '<td>'.number_format($row['rdlab'],2).'</td>';
				$html .= '<td>'.number_format($row['rdmat'],2).'</td>';
				$html .= '<td><a href="#" class="btn btn-primary btn-xs view">view</a></td>';
				$html .= '</tr>';
				$no++;
			}
		}

		return $html;
	}
	// DROPDOwN
	public function lccode_dropdown($selected = "")
	{

		$data = [];
		$response = $this->client->request('GET', 'locations/list', [
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'json' => [
				'start' => 0,
				'rows' => 2000,
				'search' => "",
				'orderColumn' => "lccode",
				'orderType' => "ASC"

			]
		]);

		$result = json_decode($response->getBody()->getContents(), true);

		$res = $result['data']['datas'];
		$option = "";
		$option .= '<select name="lccode" id="lccode" class="select-lccode">';
		$option .= '<option value="">-select-</option>';
		foreach ($res as $r) {
			$option .= "<option value='" . $r['lccode'] . "'" . ((isset($selected) && $selected == $r['lccode']) ? ' selected' : '') . ">" . $r['lccode'] . "</option>";
		}
		$option .= "</select>";
		return $option;
		die();
	}

	public function cmcode_dropdown($selected = "")
	{
		$data = [];
		$response = $this->client->request('GET', 'components/list', [
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'json' => [
				'start' => 0,
				'rows' => 2000

			]
		]);

		$result = json_decode($response->getBody()->getContents(), true);

		$option = "";
		// return $option;
		// die();
		$res = $result['data']['datas'];
		$option .= '<select name="cmcode" id="cmcode" class="select-cmcode">';
		$option .= '<option value="">-select-</option>';
		foreach ($res as $r) {
			$option .= "<option value='" . $r['cmcode'] . "'" . ((isset($selected) && $selected == $r['cmcode']) ? ' selected' : '') . ">" . $r['cmcode'] . "</option>";
		}
		$option .= "</select>";

		return $option;
		die();
	}

	public function dycode_dropdown($selected = "")
	{

		$data = [];
		$response = $this->client->request('GET', 'damagetype/list', [
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'json' => [
				'start' => 0,
				'rows' => 2000,
				'search' => "",
				'orderColumn' => "dycode",
				'orderType' => "ASC"

			]
		]);

		$result = json_decode($response->getBody()->getContents(), true);
		$res = $result['data']['datas'];
		$option = "";
		$option .= '<select name="dycode" id="dycode" class="select-dycode">';
		$option .= '<option value="">-select-</option>';
		foreach ($res as $r) {
			$option .= "<option value='" . $r['dycode'] . "'" . ((isset($selected) && $selected == $r['dycode']) ? ' selected' : '') . ">" . $r['dycode'] . "</option>";
		}
		$option .= "</select>";
		return $option;
		die();
	}

	public function rmcode_dropdown($selected = "")
	{

		$data = [];
		$response = $this->client->request('GET', 'repair_methods/getAllData', [
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'query' => [
				'offset' => 0,
				'limit' => 2000

			]
		]);

		$result = json_decode($response->getBody()->getContents(), true);
		$res = $result['data']['datas'];
		$option = "";
		$option .= '<select name="rmcode" id="rmcode" class="select-rmcode">';
		$option .= '<option value="">-select-</option>';
		foreach ($res as $r) {
			$option .= "<option value='" . $r['rmcode'] . "'" . ((isset($selected) && $selected == $r['rmcode']) ? ' selected' : '') . ">" . $r['rmcode'] . "</option>";
		}
		$option .= "</select>";
		return $option;
		die();
	}		
}

