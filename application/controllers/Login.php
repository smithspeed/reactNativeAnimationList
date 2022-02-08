<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	public function index()
	{	
		$data = new stdClass();

		$this->load->view('login/login_index',$data);
	}

    public function signIn(){

        $username = $_POST['username'];

        if($username==''){

            response('ERR','Username is required');
        }

        $password = $_POST['password'];

        if($password==''){

            response('ERR','Password is required');
        }

        $checkUser = $this->data->get([
            'db' => 'write',
            'table' => 'user_info',
            'where' => [
                'username' => $username,
                'password' => md5($password),
                'is_active' => '1'
            ]
        ]);

        if(!$checkUser){
            response('ERR','Invalid User Details');
        }

        $this->session->set_userdata('username',$checkUser->username);
        $this->session->set_userdata('displayName',$checkUser->name);
        //redirect('home');

        response('TXN','');
    }

    public function logout(){

        $this->session->sess_destroy();

        redirect('login');
    }
}
