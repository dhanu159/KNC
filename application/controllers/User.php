<?php
class User extends CI_Controller
{
	public function forgotPassword()
	{
		$this->load->view('user/forgotPassword');
	}
}
