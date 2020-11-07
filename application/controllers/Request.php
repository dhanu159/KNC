<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Request extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->not_logged_in();
        $this->load->model('model_branch');
        $this->load->model('model_groups');
        $this->load->model('model_item');
        $this->load->model('model_measureunit');
        $this->load->model('model_request');

        $user_group_data = $this->model_groups->getUserGroupData();
        $this->data['user_groups_data'] = $user_group_data;
    }

    public function RequestItem($BranchID = null)
    {
        if (!$this->isAdmin) {
            if (!in_array('viewRequestItem', $this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }
        $item_data = $this->model_item->getOnlyFinishItemData();
        $this->data['item_data'] = $item_data;

        // $available_item_data = $this->model_item->getBranchStockItems($BranchID);
        // $this->data['available_item_data'] = $available_item_data;

        $this->render_template('request/requestItem','Request Item', $this->data);
    }

    public function ViewRequest()
    {
        if (!$this->isAdmin) {
            if (!in_array('viewRequestItem', $this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }

        $this->render_template('request/viewRequestItem','View Request Item');
    }

    public function ApprovalRequestItem($intRequestHeaderID = null)
    {
        if (!$this->isAdmin) {
            if (!in_array('approveRequestItem', $this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }

        $request_header_data = $this->model_request->getRequestHeaderData($intRequestHeaderID);
        $request_detail_data = $this->model_request->getRequestDetailData($intRequestHeaderID);

        $this->data['request_header_data'] = $request_header_data;
        $this->data['request_detail_data'] = $request_detail_data;

        $this->render_template('request/ApprovalRequestItem','Approval Request Item', $this->data);
    }

    public function EditRequestItem($intRequestHeaderID = null)
    {

        if (!$this->isAdmin) {
            if (!in_array('editRequestItem', $this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }

        if (!$intRequestHeaderID) {
            redirect(base_url() . 'request/ViewRequest', 'refresh');
        }

        $request_header_data = $this->model_request->getRequestHeaderData($intRequestHeaderID);
        $request_detail_data = $this->model_request->getRequestDetailData($intRequestHeaderID);
        $item_data = $this->model_item->getOnlyFinishItemData();

        $this->data['item_data'] = $item_data;


        $this->data['request_header_data'] = $request_header_data;
        $this->data['request_detail_data'] = $request_detail_data;


        $this->render_template('request/editRequestItem','Edit Request Item', $this->data);
    }

    public function FilterRequestHeaderData($StatusType, $FromDate, $ToDate)
    {
        if (!$this->isAdmin) {
            if (!in_array('viewRequestItem', $this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }

        $result = array('data' => array());

        $data = $this->model_request->getRequestHeaderData(null, $StatusType, $FromDate, $ToDate);
        foreach ($data as $key => $value) {

            $buttons = '';
            $Pending = '';
            $Rejected  = '';


            if ($this->isAdmin) {
                $buttons .= '<a class="button btn btn-default" href="' . base_url("Request/EditRequestItem/" . $value['intRequestHeaderID']) . '" style="margin:0 !important;"><i class="fas fa-edit"></i></a>';
                $buttons .= ' <button type="button" class="btn btn-default" id="btnRemoveRequestItem" onclick="RemoveRequest(' . $value['intRequestHeaderID'] . ')"><i class="fa fa-trash"></i></button>';
                $buttons .= '<a class="button btn btn-default" href="' . base_url("request/ApprovalRequestItem/" . $value['intRequestHeaderID']) . '" style="margin:0 !important;"><i class="far fa-thumbs-up"></i></button>';

            } else {
                if ($this->session->userdata('Is_main_branch') == false) {
                    if (in_array('editRequestItem', $this->permission)) {
                        $buttons .= '<a class="button btn btn-default" href="' . base_url("request/EditRequestItem/" . $value['intRequestHeaderID']) . '" style="margin:0 !important;"><i class="fas fa-edit"></i></a>';
                    }
                    if (in_array('deleteRequestItem', $this->permission)) {
                        $buttons .= '<button type="button" class="btn btn-default" onclick="removeRequestItem(' . $value['intRequestHeaderID'] . ')"><i class="fa fa-trash"></i></button>';
                    }
                } else {
                    if (in_array('approveRequestItem', $this->permission)) {
                        $buttons .= '<a class="button btn btn-default" href="' . base_url("request/ApprovalRequestItem/" . $value['intRequestHeaderID']) . '" style="margin:0 !important;"><i class="far fa-thumbs-up"></i></button>';
                    }
                }
            }

            $Pending .= ($value['Pending'] == 0) ? "N/A"  : $value['Pending'];
            $Rejected .= ($value['Rejected'] == 0) ? "N/A"  : $value['Rejected'];

            $result['data'][$key] = array(
                $value['vcRequestNo'],
                $value['vcBranchName'],
                $value['dtCreatedDate'],
                $value['Created_User'],
                $value['Total_Items'],
                $Pending,
                $Rejected,
                $buttons
            );
        }

        echo json_encode($result);
    }

    public function getRequestFinishedByItemID($ItemID)
    {
        $data = $this->model_request->getRequestFinishedByItemID($ItemID);
        echo json_encode($data);
    }

    public function RemoveRequest()
    {
        if (!$this->isAdmin) {
            if (!in_array('deleteRequest', $this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }
        $intRequestHeaderID = $this->input->post('intRequestHeaderID');
        $response = array();
        if ($intRequestHeaderID) {

            $canRemove = $this->model_request->canRemoveRequest($intRequestHeaderID);

            if ($canRemove) {

                $delete = $this->model_request->removeRequest($intRequestHeaderID);

                if ($delete == true) {
                    $response['success'] = true;
                    $response['messages'] = "Deleted !";
                } else {
                    $response['success'] = false;
                    $response['messages'] = "Error in the database while removing the Request information !";
                }
            } else {
                $response['success'] = false;
                $response['messages'] = "You can't remove this Request, Please check and try again !";
            }
        } else {
            $response['success'] = false;
            $response['messages'] = "Please refersh the page again !!";
        }
        echo json_encode($response);
    }

    public function SaveRequestItem()
    {
        if (!$this->isAdmin) {
            if (!in_array('createRequestItem', $this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }
        $response = array();

        $result = $this->model_request->SaveRequestItem();
        if ($result == true) {
            $response['success'] = true;
        } else {
            $response['success'] = false;
            $response['messages'] = 'Error in the database while creating the GRN idetails. Please contact service provider.';
        }
        echo json_encode($response);
    }

    
    public function EditRequestDetails($intRequestHeaderID)
    {
        if (!$this->isAdmin) {
            if (!in_array('editRequestItem', $this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }
        $response = array();

        $result = $this->model_request->EditRequest($intRequestHeaderID);
        if ($result == true) {
            $response['success'] = true;
        } else {
            $response['success'] = false;
            $response['messages'] = 'Error in the database while creating the GRN idetails. Please contact service provider.';
        }
        echo json_encode($response);
    }

}
