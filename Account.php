<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Account
 * @property EnseignantModel $enseignantModel
 */
class Account extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('aauth');
        $this->load->library('form_validation');
        $this->load->model('enseignantModel');
    }

    function create() {
        LoadValidationRules($this->enseignantModel,$this->form_validation);
        $this->form_validation->set_rules('password','Password','required|max_length[100]');
        $this->form_validation->set_rules('passwordConfirmation','Confirmez le mot de passe',"required|max_length[100]|callback_password_check|min_length[5]");
        $this->form_validation->set_rules('g-recaptcha-response','Captcha','callback_recaptcha_check');

        if ($this->form_validation->run()) {
            $email=$this->input->post('login');
            $password=$this->input->post('password');
            $idAauth=$this->aauth->create_user($email,$password);
            $params=array(
                'nom'=>$this->input->post('nom'),
                'prenom'=>$this->input->post('prenom'),
                'login'=>$email,
                'idAuth'=>$idAauth
            );
            $idEnseignant=$this->enseignantModel->add_enseignant($params);
            $this->aauth->add_member($idAauth,'Enseignant');
            $this->attente_confirmation($email);
        }
        else {
            $data['title']="Inscription au rallye lecture";
            $this->load->view('AppHeader',$data);
            $this->load->view('AccountCreate',$data);
            $this->load->view('AppFooter');
        }
    }

    public function password_check() {
        $password=$this->input->post('password');
        $passwordConfirmation=$this->input->post('passwordConfirmation');
        if ($password!=$passwordConfirmation) {
            $this->form_validation->set_message('password_check','le mot de passe de confirmation est different du mot de passe initial');
            return false;
        }
        else {
            return true;
        }
    }

    public function recaptcha_check($resp) {
        if (empty($resp)) {
            $this->form_validation->set_message('recaptcha_check','quelque chose me dit que vous etes un robot, voulez-vous essayer a nouveau');
            return false;
        }
        else {
            return true;
        }
    }

    public function attente_confirmation($email) {
        $data['title']="Confirmation de votre inscription";
        $data['email']=$email;
        $this->load->view('AppHeader',$data);
        $this->load->view('AccountConfirmation',$data);
        $this->load->view('AppFooter');
    }

    public function verification($idAauth,$keyVerif) {
        $this->aauth->verify_user($idAauth,$keyVerif);
        $this->load->view('AccountInscrit');
    }

    public function changePassword() {
        $this->load->view("AccountRememberPassword");
        $this->form_validation->set_rules('password', 'Password','required|max_length[100]');
        $this->form_validation->set_rules('passwordConfirmation', 'Confirmez le password','required|max_length[100]|callback_password_check');
        
        if ($this->form_validation->run()) {
            $email=$this->input->post('login');
            $pass=$this->input->post('password');
            $id = $this->aauth->get_user_id($email);
            $this->aauth->update_user($id,FALSE,$pass,FALSE);
            
            redirect('logi');
        }
        
    }

}