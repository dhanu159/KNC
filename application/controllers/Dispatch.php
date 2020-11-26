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
}
