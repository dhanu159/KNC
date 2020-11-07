<?php
class Dashboard extends Admin_Controller 
{
	public function __construct()
	{
		parent::__construct();

		$this->not_logged_in();
		// $this->data['page_title'] = 'Dashboard - KNC';
		// $this->load->model('model_dashboard');
	}

	public function index()
	{
		$this->data['page_title'] = 'Dashboard';

		$this->load->view('partials/header', $this->data);
		$this->load->view('Dashboard');
		$this->load->view('dashboard/widgetSet_01');
		$this->load->view('partials/footer');
		// $this->render_template('Dashboard');
	}

	public function getDashboardData(){

	}
}
