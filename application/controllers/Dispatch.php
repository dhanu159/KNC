<?php
class Dispatch extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->not_logged_in();
        $this->load->model('model_item');
        $this->load->model('model_dispatch');
    }

    //-----------------------------------
    // Create Dispatch
    //-----------------------------------

    public function CreateDispatch()
    {
        if (!$this->isAdmin) {
            if (!in_array('createDispatch', $this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }
        $item_data = $this->model_item->getOnlyRawItemData();
        $this->data['item_data'] = $item_data;
        $this->render_template('Dispatch/createDispatch', 'Create Dispatch',  $this->data);
    }

    public function SaveDispatch()
    {
        if (!$this->isAdmin) {
            if (!in_array('createDispatch', $this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }

        $response = $this->model_dispatch->saveDispatch();

        echo json_encode($response);
    }

    //-----------------------------------
    // View Dispatch
    //-----------------------------------

    public function ViewDispatch()
    {
        if (!$this->isAdmin) {
            if (!in_array('viewDispatch', $this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }

        $this->render_template('Dispatch/viewDispatch', 'View Dispatch');
    }

    public function FilterDispatchHeaderData($StatusType, $FromDate, $ToDate)
    {
        if (!$this->isAdmin) {
            if (!in_array('viewDispatch', $this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }

        $result = array('data' => array());


        $dispatch_data = $this->model_dispatch->getDispatchHeaderData(null, $StatusType, $FromDate, $ToDate);

        // $this->data['grn_data'] = $grn_data;

        foreach ($dispatch_data as $key => $value) {

            $buttons = '';

            if (in_array('viewDispatch', $this->permission) || $this->isAdmin) {
                $buttons .= '<a class="button btn btn-default" href="' . base_url("Dispatch/ViewDispatchDetails/" . $value['intDispatchHeaderID']) . '" style="margin:0 !important;"><i class="fas fa-eye"></i></a>';
            }

            // if ($value['ApprovedUser'] == null && $value['RejectedUser'] == null) { // Pending 
            //     if (in_array('editGRN', $this->permission) || $this->isAdmin) {
            //         $buttons .= '<a class="button btn btn-default" href="' . base_url("GRN/EditGRN/" . $value['intGRNHeaderID']) . '" style="margin:0 !important;"><i class="fas fa-edit"></i></a>';
            //     }
            //     if (in_array('deleteGRN', $this->permission) || $this->isAdmin) {
            //         $buttons .= '<a class="button btn btn-default" onclick="removeGRN(' . $value['intGRNHeaderID'] . ')"><i class="fa fa-trash"></i></a>';
            //     }
            //     if (in_array('approveGRN', $this->permission) || $this->isAdmin) {
            //         $buttons .= '<a class="button btn btn-default" href="' . base_url("GRN/ApproveOrRejectGRN/" . $value['intGRNHeaderID']) . '"><i class="far fa-thumbs-up"></i></a>';
            //     }
            // }


            $result['data'][$key] = array(
                $value['vcDispatchNo'],
                $value['dtDispatchDate'],
                $value['dtCreatedDate'],
                $value['CreatedUser'],
                ($value['dtCancelledDate'] == NULL) ? "N/A" : $value['dtCancelledDate'],
                ($value['CancledUser'] == NULL) ? "N/A" : $value['CancledUser'],
                ($value['dtReceiveCompletedDate'] == NULL) ? "N/A" : $value['dtReceiveCompletedDate'],
                ($value['ReceiveCompletedUser'] == NULL) ? "N/A" : $value['ReceiveCompletedUser'],
                $buttons
            );
        }

        echo json_encode($result);
    }
}
