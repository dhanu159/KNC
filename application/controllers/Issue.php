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

    $this->data['customer_data'] = $customer_data;
    $this->data['item_data'] = $item_data;

    $this->render_template('Issue/createIssue', 'Create Issue',  $this->data);
  }


  public function SaveIssue()
  {
    if (!$this->isAdmin) {
      if (!in_array('createIssue', $this->permission)) {
        redirect('dashboard', 'refresh');
      }
    }

    $response = $this->model_issue->saveIssue();

    echo json_encode($response);
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

        // $product_data = $this->model_products->getProductData($v['product_id']); 

        $html .= '<tr>
                          <td>' . $v['vcItemName'] . '</td>
                          <td>' . $v['decUnitPrice'] . '</td>
                          <td>' . $v['decIssueQty'] . '</td>
                          <td>' . $v['decTotalPrice'] . '</td>
                        </tr>';
      }

      $html .= '</tbody>
                  </table>
                </div>
                <!-- /.col -->
              </div>';

      echo $html;
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


    $issue_data = $this->model_issue->GetIssueHeaderData(null,$PaymentType, $CustomerID, $FromDate, $ToDate);

    // $this->data['grn_data'] = $grn_data;

    foreach ($issue_data as $key => $value) {

      $buttons = '';

      if (in_array('viewIssue', $this->permission) || $this->isAdmin) {
        $buttons .= '<a class="button btn btn-default" href="' . base_url("GRN/ViewGRNDetails/" . $value['intIssueHeaderID']) . '" style="margin:0 !important;"><i class="fas fa-eye"></i></a>';
      }


      $result['data'][$key] = array(
        $value['vcIssueNo'],
        $value['vcCustomerName'],
        $value['dtIssueDate'],
        $value['dtCreatedDate'],
        $value['vcFullName'],
        $value['vcPayment'],
        number_format((float)$value['decSubTotal'], 2, '.', ''),
        number_format((float)$value['decDiscount'], 2, '.', ''),
        number_format((float)$value['decGrandTotal'], 2, '.', ''),
        number_format((float)$value['decPaidAmount'], 2, '.', ''),
        number_format((float)$value['decBalance'], 2, '.', ''),
        $buttons
      );
    }

    echo json_encode($result);
  }
}
