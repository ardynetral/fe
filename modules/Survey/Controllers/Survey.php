<?php
namespace Modules\Survey\Controllers;

use App\Libraries\MyPaging;

class Survey extends \CodeIgniter\Controller
{
	private $client;
	public function __construct()
	{
		helper(['url','form','app']);
		$this->client = api_connect();
	}

	function list_data(){		
		$module = service('uri')->getSegment(1);		
		$search = ($this->request->getPost('search[value]') != "")?$this->request->getPost('search[value]'):"";
        $offset = ($this->request->getPost('start')!= 0)?$this->request->getPost('start'):0;
        $limit = ($this->request->getPost('rows') !="")? $this->request->getPost('rows'):10;
        // $sort_dir = $this->get_sort_dir();		
		// PULL data from API
		$response = $this->client->request('GET','dataListReports/listAllSurveis',[
				'headers' => [
					'Accept' => 'application/json',
					'Authorization' => session()->get('login_token')
				],
				'query' => [
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
		$no = ($offset !=0)?$offset+1 :1;
		foreach ($result['data']['datas'] as $k=>$v) {
			$btn_list="";
            $record = array(); 
            $record[] = $no;
            $record[] = $v['CRNO'];
            $record[] = $v['PRCODE'];
            $record[] = $v['CPITGL'];
            $record[] = $v['SVSURDAT'];
            $record[] = $v['SVCOND'];

			$btn_list .= '<a href="'.site_url().'/survey/view/'.$v['CRNO'].'" class="btn btn-xs btn-info btn-tbl">View</a>';
			if(has_privilege_check($module, '_update')==true): 
				$btn_list .= '<a href="'.site_url().'/survey/add/'.$v['CRNO'].'" class="btn btn-xs btn-success btn-tbl">Edit</a>';
			endif;

			if(has_privilege_check($module, '_printpdf')==true):
				$btn_list .= '<a href="#" class="btn btn-xs btn-info btn-tbl" data-praid="">print</a>';
			endif;

			if(has_privilege_check($module, '_delete')==true):
				// $btn_list .= '<button type="button" id="delete" onclick="delete_data(`'.$v['CRNO'].'`,`'.@$v['CPIORDERNO'].'`)" class="btn btn-xs btn-danger btn-tbl">delete</button>';
				$btn_list .= '<button type="button" crno="'.$v['CRNO'].'" cpiorderno="'.@$v['CPIORDERNO'].'" svid="'.@$v['SVID'].'" bid="'.@$v['bid'].'" rpid="'.@$v['rpid'].'" class="btn btn-xs btn-danger btn-tbl delete_btn">delete</button>';
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
		// $paging = new MyPaging();
		// $limit = 10;
		// $endpoint = 'dataListReports/listAllSurveis';
		// $data['data'] = $paging->paginate($endpoint,$limit,'survey');
		// $data['pager'] = $paging->pager;
		// $data['nomor'] = $paging->nomor($this->request->getVar('page_survey'), $limit);		
		$data['page_title'] = "Survey";
		$data['page_subtitle'] = "Survey Page";
		return view('Modules\Survey\Views\index',$data);
	}

	public function checkValid()
	{
		$crno = $_POST['CRNO'];
		$checkValid = $this->client->request('GET','survey/checkValid',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'query' => [
				'CRNO' => $crno,
				'CRLASTACT' => 'WS'
			]
		]);

		$res= json_decode($checkValid->getBody()->getContents(),true);	
		if ($res['data']['valid'] == 'valid') {
			$getDetails = $this->client->request('GET','survey/getDetail',[
				'headers' => [
					'Accept' => 'application/json',
					'Authorization' => session()->get('login_token')
				],
				'query' => [
					'CRNO' => $crno
				]
			]);
			$detail = json_decode($getDetails->getBody()->getContents(),true);	
		
			$result = array('status'=>'valid', 'data'=>$detail['data']['datas'], 'err'=> false );
		} else {
			$result = array('status'=>'invalid', 'err'=> true );			
		}
		// print_r($result);
		echo json_encode ($result);
	}


	public function add($crno ='')
	{
		$request = \Config\Services::request();
		$token = get_token_item();
		$data = [];
		$data['act'] = ($crno =='')?"Add":"Update";
		$data['page_title'] = "Survey";
		$data['page_subtitle'] = "Survey Page";		
		$data['uname'] = $token['username'];

		// $data['crno'] = $this->request->uri->getSegment(3);
		if ($crno!='') {
			$response = $this->client->request('GET','survey/getDetail',[
				'headers' => [
					'Accept' => 'application/json',
					'Authorization' => session()->get('login_token')
				],
				'query' => [
					'CRNO' => $crno,
				]
			]);

			$result = json_decode($response->getBody()->getContents(), true);	
			$data['details'] = $result['data'];
			$data['crno'] = $crno;
		}
		return view('Modules\Survey\Views\add',$data);
	}

	public function view($crno)
	{
		check_exp_time();
		$data['act'] = "View";
		$data['page_title'] = "Survey";
		$data['page_subtitle'] = "Survey Page";	
		$response = $this->client->request('GET','survey/getDetail',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'query' => [
				'CRNO' => $crno,
			]
		]);

		$result = json_decode($response->getBody()->getContents(), true);	
		$data['details'] = isset($result['data']) ? $result['data'] : '';
		$data['crno'] = $crno;

		return view('Modules\Survey\Views\view',$data);
	}	

	public function save()
	{
		// $input = $this->request->getPost('PRCODE');
		$token = get_token_item();
		$date = date('Y-m-d');
		$getsvid = $this->client->request('GET','survey/getSVID',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'query' => []
		]);
		$result = json_decode($getsvid->getBody()->getContents(), true);	
		$SVID = $result['data'];
		$form_params = array(
			"CRNO" => $this->request->getPost('CRNO'),
		    "CPIORDERNO" => $this->request->getPost('CPIPRANO'),
		    "CPIRECEPTNO" => $this->request->getPost('CPIRECEPTNO'),
		    "CPIPRANO" => $this->request->getPost('CPIPRANO'),
		    "SVID" => $SVID,
		    "SYID" => $this->request->getPost('SYID'),
		    "SVCRNO" => $this->request->getPost('CRNO'),
		    "SVSURDAT" => ($this->request->getPost('SVSURDAT')!=''?$this->request->getPost('SVSURDAT'):$date),
		    "SVCOND" => $this->request->getPost('CRLASTCOND'),
		    // "SVCRTON" => $this->request->getPost('SVCRTON'),
		    "SVCRTON" => $date,
		    // "SVCRTBY" => $this->request->getPost('SVCRTBY'),
		    "SVCRTBY" => $token['username'],
		    "SVNOTES" => $this->request->getPost('SVNOTES'),
		    "CRCMANDAT" => $this->request->getPost('CRCMANDAT'),
		    "CPICHRGBB" => $this->request->getPost('CPICHRGBB'),
		    "CPIPAIDBB" => $this->request->getPost('CPIPAIDBB'),
		    "CRCDP" => $this->request->getPost('CRCDP'),
		    "CRACEP" => $this->request->getPost('CRACEP'),
		    "CRCSC" => $this->request->getPost('CRCSC'),
		    "CPIFE" => $this->request->getPost('CPIFE'),
		    "CPIEIR" => $this->request->getPost('CPIEIR'),
		    "CTCODE" => $this->request->getPost('CTCODE'),
		    "CPITERM" => $this->request->getPost('CPITERM'),
		    "MTCODE1" => $this->request->getPost('MTCODE1'),
		    "CPICARGO" => $this->request->getPost('CPICARGO'),
		    "CRWEIGHTK" => ($this->request->getPost('CRWEIGHTK')!=''?$this->request->getPost('CRWEIGHTK'):'0.00'),
		    "CRWEIGHTL" => ($this->request->getPost('CRWEIGHTL')!=''?$this->request->getPost('CRWEIGHTL'):'0.00'),
		    "CRTARAK" => ($this->request->getPost('CRTARAK')!=''?$this->request->getPost('CRTARAK'):'0.00'),
		    "CRTARAL" => ($this->request->getPost('CRTARAL')!=''?$this->request->getPost('CRTARAL'):'0.00'),
		    "CRNETK" => ($this->request->getPost('CRNETK')!=''?$this->request->getPost('CRNETK'):'0.00'),
		    "CRNETL" => ($this->request->getPost('CRNETL')!=''?$this->request->getPost('CRNETL'):'0.00'),
		    "CRVOL" => ($this->request->getPost('CRVOL')!=''?$this->request->getPost('CRVOL'):'0.00'),
		    "CPIDISH" => $this->request->getPost('CPIDISH'),
		    "MANUFDATE" => $this->request->getPost('MANUFDATE'),
		    "CPIDISDAT" => $this->request->getPost('CPIDISDAT'),
		    "CPISEAL" => $this->request->getPost('CPISEAL'),
		    "CRLASTCOND" => $this->request->getPost('CRLASTCOND'),
		    "CRLASTCONDE" => $this->request->getPost('CRLASTCONDE'),
		    "CRLASTACT" => $this->request->getPost('CRLASTACT'),
		    "LECONTRACTNO" => $this->request->getPost('LECONTRACTNO'),
		    "LECLEARNO" => $this->request->getPost('LECLEARNO'),
		    "CUTYPE" => $this->request->getPost('CUTYPE'),
		    "CPIVOYID" => $this->request->getPost('CPIVOYID'),
		    "CPIVES" => $this->request->getPost('CPIVES'),
		    "CPIDRIVER" => $this->request->getPost('CPIDRIVER'),
		    "CPINOPOL" => $this->request->getPost('CPINOPOL'),
		    "CRBAY" => ($this->request->getPost('CRBAY')!=''?$this->request->getPost('CRBAY'):'0'),
		    "CPIDELIVER" => $this->request->getPost('CPIDELIVER'),
		    "CRPOS" => $this->request->getPost('CRPOS'),
		    "CRROW" => ($this->request->getPost('CRROW')!=''?$this->request->getPost('CRROW'):'0'),
		    "CPIDPP" => $this->request->getPost('CPIDPP'),
		    "CRTIER" => ($this->request->getPost('CRTIER')!=''?$this->request->getPost('CRTIER'):'0'),
		    "CPIREMARK" => $this->request->getPost('CPIREMARK'),
		    "CPINOTES" => $this->request->getPost('CPINOTES'),
		    "CRMANUF" => $this->request->getPost('CRMANUF'));

		if ($this->request->getPost('UPDATE_ID') == '') {
			$response = $this->client->request('POST','survey/createNew',[
				'headers' => [
					'Accept' => 'application/json',
					'Authorization' => session()->get('login_token')
				],
				'json' => $form_params,
			]);
			$type = 'insert';
		} else {
			$response = $this->client->request('PUT','survey/updateData',[
				'headers' => [
					'Accept' => 'application/json',
					'Authorization' => session()->get('login_token')
				],
				'json' => $form_params,
			]);
			$type = 'update';
		}
		$result = json_decode($response->getBody()->getContents(), true);
		
		$err = ($result['message']== "Success Insert Data" || $result['message'] == "Success Update Data")?false:true;
		// $err = false;
		$resp = array('method'=>$type, 'err'=>$err,'message'=>'Success '.$type.' Data', 'api'=>@$result);
		echo json_encode($resp);
	}

	public function delete(){
		$response = $this->client->request('DELETE','survey/deleteData',[
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => session()->get('login_token')
			],
			'query' => [
					'CRNO' => $this->request->getPost('CRNO'),
					'CPIPRANO' => $this->request->getPost('CPIORDERNO'),
					'RPID'=>$this->request->getPost('RPID'),
					'SVID'=>$this->request->getPost('SVID'),
					'BID'=>$this->request->getPost('BID')
				],
		]);
		$result = json_decode($response->getBody()->getContents(), true);
		if ($result['message'] == 'Success Delete Data') {
			$msg='success delete'; 
			$err=false;
		} else {
			$msg='failed delete'; 
			$err=true;
		}
		echo json_encode(array('message'=>$msg, 'err'=> $err));
	}
}