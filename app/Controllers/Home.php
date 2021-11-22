<?php
namespace App\Controllers;

class Home extends BaseController
{

	public function __construct()
	{
	}

	public function index()
	{
		return redirect()->to('dashboard');

	}

}
