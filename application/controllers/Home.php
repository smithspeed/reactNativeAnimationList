<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	
	public function index()
	{	
		$data = new stdClass();

		$this->load->view('template/header.php',$data);
		$this->load->view('home/home_index.php',$data);
		$this->load->view('template/footer.php',$data);
	}
}
