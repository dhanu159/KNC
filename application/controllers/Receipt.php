<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Receipt extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->not_logged_in();
        $this->load->model('model_receipt');
        $this->load->model('model_customer');
    $this->load->model('model_utility'); 

        // $this->load->model('model_issue');

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
    $paymode_data = $this->model_utility->getPayModes();
    $bank_data = $this->model_utility->getBanks();

    $this->data['customer_data'] = $customer_data;
    $this->data['paymode_data'] = $paymode_data;
    $this->data['bank_data'] = $bank_data;

    $this->render_template('Receipt/CreateReceipt', 'Create Receipt',  $this->data);
  }


  public function getCustomerToBeSettleIssueNos(){
    $CustomerID = $this->input->post('intCustomerID');
    $data = $this->model_receipt->getCustomerToBeSettleIssueNos($CustomerID);
    echo json_encode($data);
  } 

  public function getIssueNotePaymentDetails(){
    $IssueHeaderID = $this->input->post('intIssueHeaderID');
    $data = $this->model_receipt->getIssueNotePaymentDetails($IssueHeaderID);
    echo json_encode($data);
  }


}