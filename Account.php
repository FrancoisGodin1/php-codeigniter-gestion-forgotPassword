<?php

class Account extends CI_Controller {

    // méthode ajoutée au controller Account 
    
    public function changePassword() {
        $this->load->view("AccountRememberPassword");
        $this->form_validation->set_rules('password', 'Password','required|max_length[100]');
        $this->form_validation->set_rules('passwordConfirmation', 'Confirmez le password','required|max_length[100]|callback_password_check');
        
        if ($this->form_validation->run()) {
            $email=$this->input->post('login');
            $pass=$this->input->post('password');
            $id = $this->aauth->get_user_id($email);
            $this->aauth->update_user($id,FALSE,$pass,FALSE);
            
            redirect('login/index');
        }
        
    }

}
