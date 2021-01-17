<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Receipt extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->not_logged_in();
        $this->data['page_title'] = 'Receipt';
        $this->load->model('model_receipt');
        $this->load->model('model_groups');
        $this->load->model('model_customer');
        $this->load->model('model_issue');

        // $user_group_data = $this->model_groups->getUserGroupData();
        // $this->data['user_groups_data'] = $user_group_data;
    }

  //-----------------------------------
  // Create Receipt
  //-----------------------------------

  public function CreateReceipt()
  {
    if (!$this->isAdmin) {
      if (!in_array('createReceipt', $this->permission)) {
        redirect('dashboard', 'refresh');
      }
    }

    $customer_data = $this->model_customer->getCustomerData();
    $paymode_data = $this->model_issue->getPaymentTypes();
    $this->data['paymode_data'] = $paymode_data;
    $this->data['customer_data'] = $customer_data;

    $this->render_template('Receipt/CreateReceipt', 'Create Receipt',  $this->data);
  }


}