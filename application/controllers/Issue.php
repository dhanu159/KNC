<?php

class Issue extends Admin_Controller
{
  public function __construct()
  {
    parent::__construct();

    $this->not_logged_in();
    $this->data['page_title'] = 'Issue';
    $this->load->model('model_supplier');
    $this->load->model('model_item');
    $this->load->model('model_measureunit');
    $this->load->model('model_customer');
    $this->load->model('model_issue');
  }

  //-----------------------------------
  // Create Issue
  //-----------------------------------

  public function CreateIssue()
  {
    if (!$this->isAdmin) {
      if (!in_array('createIssue', $this->permission)) {
        redirect('dashboard', 'refresh');
      }
    }

    $customer_data = $this->model_customer->getCustomerData();
    $item_data = $this->model_item->getOnlyFinishItemData();
    $payment_data = $this->model_issue->getPaymentTypes();
    $this->data['payment_data'] = $payment_data;
    $this->data['customer_data'] = $customer_data;
    $this->data['item_data'] = $item_data;

    $this->render_template('Issue/createIssue', 'Create Issue',  $this->data);
  }

  public function getOnlyFinishItemData()
  {
      $data = $this->model_item->getOnlyFinishItemData();
      echo json_encode($data);
  }


  public function SaveIssue()
  {
    if (!$this->isAdmin) {
      if (!in_array('createIssue', $this->permission)) {
        redirect('dashboard', 'refresh');
      }
    }

    $response = $this->model_issue->saveIssue();
    if ($response['success'] == true) {
      $response['issueNote'] = $this->PrintIssueDiv($response['intIssueHeaderID']);
    }
      // $response['issueNote'] = "ABC";


    echo json_encode($response);
  }

  public function getIssuedHeaderData()
  {
    if (!$this->isAdmin) {
      if (!in_array('viewIssue', $this->permission)) {
        redirect('dashboard', 'refresh');
      }
    }
    $intIssueHeaderID = $this->input->post('intIssueHeaderID');
    $issue_Header_Date =  $this->model_issue->GetIssueHeaderData($intIssueHeaderID);
    echo json_encode($issue_Header_Date);
  }

  public function PrintIssueDiv($intIssueHeaderID)
  {
    if ($intIssueHeaderID) {

      $issue_Header_Date =  $this->model_issue->GetIssueHeaderData($intIssueHeaderID);
      $issue_Detail_Date =  $this->model_issue->GetIssueDetailsData($intIssueHeaderID);

      $html = '

            <div id="myDiv" class="wrapper">
            <section class="invoice">
              <!-- title row -->
              <div class="row">
                <div class="col-xs-12">
                  <h2 class="page-header">
                    "KNC Cake Boards"
                  </h2>
                </div>
                <!-- /.col -->
              </div>
              <!-- info row -->
              <div class="row invoice-info">
                
                <div class="col-sm-4 invoice-col">

                  <b>Date:</b> ' . $issue_Header_Date['dtCreatedDate'] . '<br>
                  <b>Issue No:</b> ' . $issue_Header_Date['vcIssueNo'] . '<br>
                  <b>Customer Name:</b> ' . $issue_Header_Date['vcCustomerName'] . '<br>
             
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->

              <!-- Table row -->
              <div class="row">
                <div class="col-xs-12 table-responsive">
                  <table class="table table-striped">
                    <thead>
                    <tr>
                      <th>Item name</th>
                      <th>Unit Price</th>
                      <th>Qty</th>
                      <th>Amount</th>
                    </tr>
                    </thead>
                    <tbody>';

      foreach ($issue_Detail_Date as $k => $v) {

        $html .= '<tr>
                          <td>' . $v['vcItemName'] . '</td>
                          <td>' . $v['decUnitPrice'] . '</td>
                          <td>' . $v['decIssueQty'] . '</td>
                          <td>' . $v['decTotalPrice'] . '</td>
                        </tr>';
      }

      $html .= ' <tr align="right">
			              <th>Payment Mode:</th>
			              <td>' . $issue_Header_Date['vcPaymentType'] . '</td>
			            </tr>
			            <tr align="right">
			              <th>Sub Total:</th>
			              <td>' . $issue_Header_Date['decSubTotal'] . '</td>
			            </tr>
			            <tr align="right">
			              <th>Discount:</th>
			              <td>' . $issue_Header_Date['decDiscount'] . '</td>
                  </tr>
                  <tr align="right">
                  <th>Grand Total:</th>
                  <td>' . $issue_Header_Date['decGrandTotal'] . '</td>
                </tr>';

      $html .= '  </table>
          </div>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
    </div>';

      return $html;
    }
  }

  //-----------------------------------
  // View Issue
  //-----------------------------------

  public function ViewIssue()
  {
    if (!$this->isAdmin) {
      if (!in_array('viewIssue', $this->permission)) {
        redirect('dashboard', 'refresh');
      }
    }
    $payment_data = $this->model_issue->getPaymentTypes();
    $customer_data = $this->model_customer->getCustomerData();
    $this->data['payment_data'] = $payment_data;
    $this->data['customer_data'] = $customer_data;
    $this->render_template('Issue/ViewIssue', 'View Issue', $this->data);
  }

  public function FilterIssueHeaderData($PaymentType, $CustomerID, $FromDate, $ToDate)
  {
    if (!$this->isAdmin) {
      if (!in_array('viewIssue', $this->permission)) {
        redirect('dashboard', 'refresh');
      }
    }

    $result = array('data' => array());


    $issue_data = $this->model_issue->GetIssueHeaderData(null, $PaymentType, $CustomerID, $FromDate, $ToDate);

    // $this->data['grn_data'] = $grn_data;

    foreach ($issue_data as $key => $value) {

      $buttons = '';

      if (in_array('viewIssue', $this->permission) || $this->isAdmin) {
        $buttons .= '<a class="button btn btn-default" href="' . base_url("Issue/ViewIssueDetails/" . $value['intIssueHeaderID']) . '" style="margin:0 !important;"><i class="fas fa-eye"></i></a>';
      }


      $result['data'][$key] = array(
        $value['vcIssueNo'],
        $value['vcCustomerName'],
        $value['dtIssueDate'],
        $value['dtCreatedDate'],
        $value['vcFullName'],
        $value['vcPaymentType'],
        number_format((float)$value['decSubTotal'], 2, '.', ''),
        number_format((float)$value['decDiscount'], 2, '.', ''),
        number_format((float)$value['decGrandTotal'], 2, '.', ''),
        $value['vcRemark'],
        $buttons
      );
    }

    echo json_encode($result);
  }

  public function ViewIssueDetails($IssueHeaderID)
  {
      if (!$this->isAdmin) {
          if (!in_array('viewIssue', $this->permission)) {
              redirect('dashboard', 'refresh');
          }
      }

      if (!$IssueHeaderID) {
          redirect('dashboard', 'refresh');
      }

      $issue_header_data = $this->model_issue->GetIssueHeaderData($IssueHeaderID, null, null, null, null);

      if (isset($issue_header_data)) {

        $issue_detail_Date =  $this->model_issue->GetIssueDetailsData($IssueHeaderID);

          $this->data['issue_detail_Date'] = $issue_detail_Date;
          $this->data['issue_header_data'] = $issue_header_data;

          $this->render_template('Issue/viewIssueDetail', 'View Issue', $this->data);
      }else{
          redirect(base_url() . 'Issue/viewIssueDetail', 'refresh');
      }
  }

  
  //-----------------------------------
  // Issue Return
  //-----------------------------------

  public function IssueReturn()
  {
    if (!$this->isAdmin) {
      if (!in_array('issueReturn', $this->permission)) {
        redirect('dashboard', 'refresh');
      }
    }
    $issue_No = $this->model_issue->getReturnIssueNo();
    $this->data['issue_No'] = $issue_No;


    $this->render_template('Issue/IssueReturn', 'Issue Return', $this->data);
  }


  public function ViewIssueDetailsToTable()
  {
    if (!$this->isAdmin) {
      if (!in_array('viewIssue', $this->permission)) {
        redirect('dashboard', 'refresh');
      }
    }
    $intIssueHeaderID = $this->input->post('intIssueHeaderID');
    $issued_item_data = $this->model_issue->GetIssueDetailsData($intIssueHeaderID);
    echo json_encode($issued_item_data);
  }

  public function SaveIssueReturn()
  {
    if (!$this->isAdmin) {
      if (!in_array('issueReturn', $this->permission)) {
        redirect('dashboard', 'refresh');
      }
    }

    $IssueHeaderData = $this->model_issue->GetIssueHeaderData($this->input->post('cmbIssueNo'));
    $CustomerID =  $IssueHeaderData['intCustomerID'];

    $result =  $this->model_issue->chkNullCustomerAdvancePayment($CustomerID);

    if ($result) {
      $response = $this->model_issue->saveIssueReturn();
      // $response['success'] = true;
      // $response['messages'] = 'Succesfully Returned !';
    }
    else {
      $response['success'] = false;
      $response['messages'] = 'Already Have a Advance Payment Please Delete this Customer Advance Amount !';
    }
  
    echo json_encode($response);
  }

}
