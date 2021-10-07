<?php

namespace App\Controllers;

class Vault extends Security_Controller {

    function __construct() {
        parent::__construct();
        $this->access_only_team_members();
    }

    protected function validate_access_to_vault() {
        $access_invoice = $this->get_access_info("invoice");
        $access_estimate = $this->get_access_info("estimate");

        //don't show the items if invoice/estimate module is not enabled
        if(!(get_setting("module_invoice") == "1" || get_setting("module_estimate") == "1" )){
            redirect("forbidden");
        }
        
        if ($this->login_user->is_admin) {
            return true;
        } else if ($access_invoice->access_type === "all" || $access_estimate->access_type === "all") {
            return true;
        } else {
            redirect("forbidden");
        }
    }

    //load note list view
    function index() {
        error_log(print_r("IN VAULT Index", TRUE)); 
        $this->validate_access_to_vault();

        return $this->template->rander("vault/index");
    }

    /* load item modal */

    function modal_form() {

        $view_data['model_info'] = $this->Vault_model->get_one($this->request->getPost('id'));
        $view_data['client_id'] = $this->request->getPost('client_id') ? $this->request->getPost('client_id') : $view_data['model_info']->client_id;

        return $this->template->view('vault/modal_form', $view_data);
    }

    /* add or edit an item */

    function save() {
        $this->validate_access_to_vault();

        $this->validate_submitted_data(array(
            "id" => "numeric"
        ));

        $id = $this->request->getPost('id');

        $password = $this->request->getPost('password');
        $usrPin = $this->request->getPost('passPin');
        $encryptedPwd = openssl_encrypt($password, "AES-128-ECB", $usrPin);

//        error_log(print_r($password, TRUE)); 
//        error_log(print_r($usrPin, TRUE)); 
//        error_log(print_r($encryptedPwd, TRUE)); 

        $item_data = array(
            "title" => $this->request->getPost('title'),
            "description" => $this->request->getPost('description'),
            "client_id" => $this->request->getPost('client_id') ? $this->request->getPost('client_id') : 0,
            "username" => $this->request->getPost('username'),
            "password" => $encryptedPwd
        );

        $item_id = $this->Vault_model->ci_save($item_data, $id);
        if ($item_id) {
            $options = array("id" => $item_id);
            $item_info = $this->Vault_model->get_details($options)->getRow();
            echo json_encode(array("success" => true, "id" => $item_info->id, "data" => $this->_make_item_row($item_info), 'message' => app_lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => app_lang('error_occurred')));
        }

    }

    /* delete or undo an item */

    function delete() {
        $this->validate_access_to_vault();

        $this->validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->request->getPost('id');
        if ($this->request->getPost('undo')) {
            if ($this->Vault_model->delete($id, true)) {
                $options = array("id" => $id);
                $item_info = $this->Vault_model->get_details($options)->getRow();
                echo json_encode(array("success" => true, "id" => $item_info->id, "data" => $this->_make_item_row($item_info), "message" => app_lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, app_lang('error_occurred')));
            }
        } else {
            if ($this->Vault_model->delete($id)) {
                $item_info = $this->Vault_model->get_one($id);
                echo json_encode(array("success" => true, "id" => $item_info->id, 'message' => app_lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => app_lang('record_cannot_be_deleted')));
            }
        }
    }

    /* list of items, prepared for datatable  */

    function list_data($id = 0) {
      //  $this->validate_access_to_vault();
        $options = array();
        $options["client_id"] = $id;

        $list_data = $this->Vault_model->get_details($options)->getResult();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_item_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    /* prepare a row of item list table */

    private function _make_item_row($data) {

        $title = modal_anchor(get_uri("vault/view/" . $data->id), $data->title, array("title" => app_lang('vault'), "data-post-id" => $data->id));

        return array(
            $data->id,
            $title,
            $data->description,
            $data->username,
//            modal_anchor(get_uri("vault/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_item'), "data-post-id" => $data->id))
            js_anchor("<i data-feather='x' class='icon-16'></i>", array('title' => app_lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("vault/delete"), "data-action" => "delete"))
        );
    }

    function view() {
        $this->validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $getID =  $this->request->getPost('id');

        $model_info = $this->Vault_model->get_details(array("id" => $this->request->getPost('id')))->getRow();
//        error_log(print_r($getID, TRUE));
        error_log(print_r($model_info, TRUE));
        $this->validate_access_to_vault($model_info);

        $view_data['model_info'] = $model_info;
        return $this->template->view('vault/view', $view_data);
    }

    function getPassword() {
        error_log(print_r("Begin Get Password Function", TRUE));
        $this->validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $usrPin = $this->request->getPost('pin');
        error_log(print_r("SHow Pin", TRUE));
        error_log(print_r($usrPin, TRUE));

        $getID =  $this->request->getPost('id');
        $model_info = $this->Vault_model->get_details(array("id" => $this->request->getPost('id')))->getRow();

        $Pwd = $model_info -> password;
        $decyrptPwd = openssl_decrypt($Pwd, "AES-128-ECB", $usrPin);
        if (!$decyrptPwd){
            $decyrptPwd = "Invalid PIN";
        }
        error_log(print_r($model_info, TRUE));

        echo json_encode(array("data" => $decyrptPwd));


        error_log(print_r("End Get Password Function", TRUE));
    }

}

/* End of file vault.php */
/* Location: ./application/controllers/vault.php */