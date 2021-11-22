<?php
namespace Modules\Dashboard\Controllers;

class Dashboard extends \CodeIgniter\Controller
{
	protected $_token;
	public function __construct()
	{
		helper(['app']);
		$this->_token = session()->get('login_token');
	}

	public function index()
	{
		if($this->_token=='') {
			return redirect()->to('/login');
		}		
		return view('Modules\Dashboard\Views\index');
	}
}