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
    // $item_data = $this->model_item->getOnlyFinishItemData();
    $payment_data = $this->model_issue->getPaymentTypes();
    $this->data['payment_data'] = $payment_data;
    $this->data['customer_data'] = $customer_data;
    // $this->data['item_data'] = $item_data;

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
                      <th style="text-align:center">Item name</th>
                      <th style="text-align:center">Unit Price</th>
                      <th style="text-align:center">Qty</th>
                      <th style="text-align:center">Amount</th>
                    </tr>
                    </thead>
                    <tbody>';

      foreach ($issue_Detail_Date as $k => $v) {

        $html .= '<tr>
                          <td>' . $v['vcItemName'] . '</td>
                          <td style="text-align:right">' . $v['decUnitPrice'] . '</td>
                          <td style="text-align:center">' . $v['decIssueQty'] . '</td>
                          <td style="text-align:right">' . $v['decTotalPrice'] . '</td>
                        </tr>';
      }

      $html .= ' <tr align="right" style="width:100%">
                  <td></td>
                  <td></td>
			              <th>Payment Mode:</th>
			              <td>' . $issue_Header_Date['vcPaymentType'] . '</td>
			            </tr>
			            <tr align="right">
                  <td></td>
                  <td></td>
			              <th>Sub Total:</th>
			              <td>' . $issue_Header_Date['decSubTotal'] . '</td>
			            </tr>
			            <tr align="right">
                  <td></td>
                  <td></td>
			              <th>Discount:</th>
			              <td>' . $issue_Header_Date['decDiscount'] . '</td>
                  </tr>
                  <tr align="right">
                  <td></td>
                  <td></td>
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

      // $html = '



      //   <style>
      //   .f{
      //     color: #212e59;
      //     font-size:3em;
      //     position: absolute;
      //     font-family: "Oswald",sans-serif;
      //   }
      //   img{
      //     height: 150px;
      //     position:absolute;
      //     right: 30px;
      //     margin-top: 40px;
      //   }

      //   .address{
      //     margin-top: 120px;
      //     font-size: 13px;
      //     font-family: "oswald", sans-serif;
      //   }
      //   .shipping-info-head{
      //     column-count: 4;
      //   }

      //   .shipping-info-head h6{
      //     font-size: 20px;
      //     margin-top: 0px;
      //     font-family: "Teko", sans-serif; 
      //   }
      //   .shipping-info-head p{
      //     font-size: 15px;
      //     margin-top: 5px !important;
      //     font-weight: 100;
      //     font-family: "Teko", sans-serif; 
      //   }
      //   .shipping-info-1{
      //     column-count: 4;
      //     margin-top: -40px;
      //   }

      //   .shipping-info-1 h6{
      //     font-size: 20px;
      //     margin-top: 0px;
      //     font-family: "Teko", sans-serif; 
      //   }
      //   .shipping-info-1 p{
      //     font-size: 15px;
      //     margin-top: 5px !important;
      //     font-weight: 100;
      //     font-family: "Teko", sans-serif; 
      //   }
      //   .shipping-info-2{
      //     column-count: 4;
      //     margin-top: -27px;
      //   }

      //   .shipping-info-2 h6{
      //     font-size: 20px;
      //     margin-top: 0px;
      //     font-family: "Teko", sans-serif; 
      //   }
      //   .shipping-info-2 p{
      //     font-size: 15px;
      //     margin-top: 5px !important;
      //     font-weight: 100;
      //     font-family: "Teko", sans-serif; 
      //   }
      //   .shipping-info-3{
      //     column-count: 4;
      //     margin-top: -27px;
      //   }

      //   .shipping-info-3 h6{
      //     font-size: 20px;
      //     margin-top: 0px;
      //     font-family: "Teko", sans-serif; 
      //   }
      //   .shipping-info-3 p{
      //     font-size: 15px;
      //     margin-top: 5px !important;
      //     font-weight: 100;
      //     font-family: "Teko", sans-serif; 
      //   }

      //   .main-strip{
      //     column-count: 4;
      //     margin-top: 17px;
      //   }

      //   .main-strip h6{
      //     font-size: 25px;
      //     color: #212e59;
      //     margin-top: 0px;
      //     font-family: "Teko", sans-serif; 
      //     text-align: center;
      //   }
      //   .top{
      //     margin-bottom: -15px;
      //     border-top: 2px solid red;
      //   }
      //   .bottom{
      //     margin-top: -60px;
      //     border-top: 2px solid red;
      //   }

      //   .item-qty{
      //   text-align: center !important;
      //   }

      //   .text-right{
      //     text-align: right;
      //   }

      //   .shipping-1{
      //     column-count: 4;
      //     margin-top: 20px;
      //   }

      //   .shipping-1 p{
      //     font-size: 17px;
      //     margin-top: 5px !important;
      //     font-weight: 100;
      //     font-family: "Teko", sans-serif; 
      //   }
      //   .shipping-2{
      //     column-count: 4;
      //     margin-top: -10px;
      //   }


      //   .shipping-2 p{
      //     font-size: 17px;
      //     margin-top: 5px !important;
      //     font-weight: 100;
      //     font-family: "Teko", sans-serif; 
      //   }
      //   .shipping-3{
      //     column-count: 4;
      //     margin-top: -10px;
      //   }

      //   .shipping-3 p{
      //     font-size: 17px;
      //     margin-top: 5px !important;
      //     font-weight: 100;
      //     font-family: "Teko", sans-serif; 
      //   }

      //   total{
      //     right: 0;
      //     width: 300px;
      //     position: absolute;
      //     margin-top: 20px;
      //     margin-right: 10px;

      //   }

      //   .shipping-total{
      //     column-count: 2;
      //     margin-top: 10px;
      //   }


      //   .shipping-total p{
      //     font-size: 17px;
      //     margin-top: 5px !important;
      //     font-weight: 100;
      //     font-family: "Teko", sans-serif; 
      //   }
      //   .shipping-total-1{
      //     column-count: 2;
      //     margin-top: -10px;
      //   }


      //   .shipping-total-1 p{
      //     font-size: 17px;
      //     margin-top: 5px !important;
      //     font-weight: 100;
      //     font-family: "Teko", sans-serif; 
      //   }
      //   .shipping-total-2{
      //     column-count: 2;
      //     margin-top: -10px;
      //   }
      //   .shipping-total-2 h6{
      //     font-size: 23px;
      //     color: #212e59;
      //     margin-top: 0px;
      //     font-family: "Teko", sans-serif; 
      //   }


      //   .theTitle{
      //       display: flex;
      //       align-items: center;
      //       height: 120px;
      //       margin-top: 700px;
      //       position: absolute;
      //       left: 50%;
      //       top: 10%;
      //       transform: translate(-50%, -50%);
      //       width: 600px;
      //   }
      //   .right, .left{
      //       width: 50%;
      //       overflow: hidden;
      //   }
      //   .theTitle h1{
      //       margin: 0;
      //       height: 80px;
      //     margin-top: 50px;
      //       line-height: 80px;
      //       vertical-align: middle;
      //       font-family: "Dancing Script", cursive;
      //       font-weight: 600;
      //       margin-left: 50px;
      //       font-size: 3.5rem;
      //       color: #212e59;
      //       transform: translateX(100%);
      //       transition: transform 0.6s ease-out .1s;
      //   }

      //   .theTitle .separator{
      //       background-color: black;
      //       width: 4px;
      //       transform: rotate(90deg);
      //       height: 0;
      //       display: block;
      //       margin: 0 6px 0 0;
      //       transition: height 0.3s ease 0.9s, transform 0.3s ease 0.6s;
      //   }

      //   .active .separator{
      //       height: 100%;
      //       transform: rotate(0);
      //       transition: height 0.3s ease, transform 0.3s ease 0.4s;

      //   }
      //   .theTitle.active h1{
      //       transform: translateX(0);
      //       transition: transform 0.6s ease-out 0.8s;
      //   }
      //   .theTitle.active p{
      //       transform: translateX(0);
      //       transition: transform 0.5s ease-out 0.7s;
      //   }
      //   .theTitle .right{
      //       display: flex;
      //       align-items: center;
      //       width: 50%;
      //   }
      //   .theTitle p{
      //       margin: 0;
      //       padding: 0;
      //       font-family: "Teko";
      //       font-size: 1.3rem;
      //       font-weight: 400;
      //       line-height: 200px;
      //       margin-top: 0px;
      //       vertical-align: middle;
      //       display: flex;
      //       align-items: center;
      //       text-transform: uppercase;
      //       color: red;
      //       transform: translateX(-100%);
      //       transition: transform 0.5s ease-out;
      //   }

      //   @media(max-width: 750px){
      //     img{
      //       height: 70px;
      //       position:absolute;
      //       margin-left: 430px;
      //       margin-top: 40px;
      //   }

      //   }
      //   </style>


      //   <body>
      //   <!-- partial:index.partial.html -->
      //   <div class="page">
      //     <h1 class="f">INVOICE</h1>
      //     <img src="https://img.freepik.com/free-vector/illustration-circle-stamp-banner-vector_53876-27183.jpg?size=338&ext=jpg" ALIGN="right">
      //     <p class="address"><b>KNC Cake Boards & Boxes</b><br>No.124A<br>Galle Road, Pohoddaramulla<br>Wadduwa</p>
      //     <div class="shipping-info-head">
      //       <h6>INVOICED TO</h6>
      //       <h6>SHIP TO</h6>
      //       <h6>INVOICE#</h6>
      //       <p>INV21010001</p>
      //     </div>
      //     <div class="shipping-info-1">
      //       <p>Client Name</p>
      //       <p>John Smith</p>
      //       <h6>INVOICE DATE</h6>
      //       <p>11/02/2019</p>
      //     </div>
      //     <div class="shipping-info-2">
      //       <p>Address Line 1</p>
      //       <p>37 Drive</p>
      //       <h6>P.O.#</h6>
      //       <p>2023/2019</p>
      //     </div>
      //     <div class="shipping-info-3">
      //       <p>Address Line 2</p>
      //       <p>Cambridge, MA 16543</p>
      //       <h6>DUE DATE</h6>
      //       <p>26/2/2019</p>
      //     </div>
      //     <hr class="top">
      //     <div class="main-strip">
      //       <h6>DESCRIPTION</h6>
      //       <h6>QTY</h6>
      //       <h6>UNIT PRICE</h6>
      //       <h6>AMOUNT</h6>
      //     </div>
      //     <hr class="bottom">
      //     <div class="shipping-1">
      //       <p>Front and rear break cables</p>
      //       <p class="item-qty">1</p>
      //       <p class="text-right">$ 100.00</p>
      //       <p class="text-right">$ 100.00</p>
      //     </div>
      //     <div class="shipping-2">
      //       <p>New set of pedal arms</p>
      //       <p class="item-qty">2</p>
      //       <p class="text-right">$ 15.00</p>
      //       <p class="text-right">$ 30.00</p>
      //     </div>
      //     <div class="shipping-3">
      //       <p>Lollipops</p>
      //       <p class="item-qty">3</p>
      //       <p class="text-right">$ 5.00</p>
      //       <p class="text-right">$ 15.00</p>
      //     </div>
      //     <hr class="top">
      //     <total >
      //     <div class="shipping-total">
      //       <p>Subtotal</p>
      //       <p class="text-right">$ 145.00</p>
      //     </div>
      //     <!--<div class="shipping-total-1">
      //       <p>Sales Tax 6.25%</p>
      //       <p class="text-right">9.06</p>
      //     </div> -->
      //     <div class="shipping-total-2">
      //       <h6>TOTAL</h6>
      //       <h6 class="text-right">$ 154.06</h6>
      //     </div>
      //     </total>
      //     <!-- <div class="theTitle active">
      //           <div class="left"><h1>Thank You</h1></div>
      //           <span class="separator"></span>
      //           <div class="right">
      //             <p>Payment is due within 15 days.</p>
      //       </div>
      //       </div> -->
      //   </div>
      //   <!-- partial -->

      //   </body>';

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
      $badge = '';

      if (in_array('viewIssue', $this->permission) || $this->isAdmin) {
        $buttons .= '<a class="button btn btn-default" href="' . base_url("Issue/ViewIssueDetails/" . $value['intIssueHeaderID']) . '" style="margin:0 !important;"><i class="fas fa-eye"></i></a>';
      }

      if ($value['PaymentViewButton'] != 'N/A') {
        if (in_array('viewIssueCreditSettlement', $this->permission) || $this->isAdmin) {
          $buttons .= ' <button type="button" class="btn btn-default" onclick="viewReceiptWiseSettlementDetails(' . $value['intIssueHeaderID'] . ')" data-toggle="modal" data-target="#viewModal"><i class="fas fa-money-bill-alt"></i></button>';
        }
      }

      if ($value['PaymentViewButton'] != 'N/A') {
        $badge = '<span class="badge badge-secondary" style="padding: 4px 10px; float:right; margin-right:10px;">Partially Paid</span>';
      }
      else{
        $badge = '<span class="badge badge-warning" style="padding: 4px 10px; float:right; margin-right:10px;">Total Pending</span>';
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
        $badge,
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
    } else {
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

    $IssueHeaderData = $this->model_issue->GetIssueHeaderData($this->input->post('cmbIssueNo'), null, null, null, null);
    $CustomerID =  $IssueHeaderData['intCustomerID'];
    $IssueHeaderID = $this->input->post('cmbIssueNo');

    $result =  $this->model_issue->chkNullCustomerAdvancePayment($CustomerID);

    if ($result) {
      $ckhExist = $this->model_issue->chkExistsReceiptDetails($IssueHeaderID);
      if ($ckhExist <> '') {
        if ($ckhExist[0]['value'] == 1) {
          $response['success'] = false;
          $response['messages'] = "Already Payment added this Issue. Please Cancel Payment Details !";
        } else {
          $response = $this->model_issue->saveIssueReturn();
        }
      }
    } else {
      $response['success'] = false;
      $response['messages'] = 'Already Have a Advance Payment Please Delete this Customer Advance Amount !';
    }

    echo json_encode($response);
  }
}
