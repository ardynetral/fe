<?php
namespace Modules\Dashboard\Controllers;

class Dashboard extends \CodeIgniter\Controller
{
	protected $_token;
	public function __construct()
	{
		helper(['app']);
		check_exp_time();
	}

	public function index()
	{
		return view('Modules\Dashboard\Views\index');
	}
}