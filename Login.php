<?php


class Login extends CI_Controller {
    
    // methode concernée par la modif
    function forgotPassword()
    {
        $this->load->view('LoginForgotPassword');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('login','Login','trim|required|xss_clean|max_length[100]');
        if($this->form_validation->run())
        {
            $email = $this->input->post('login');
            if($this->LoginModel->verifEmail($email) != NULL){
                echo "Le mail de confirmation a été envoyé";
                $this->email->from('babaorum.slam.sio.jjr@gmail.com','Admin');
                $this->email->to($email);
                $this->email->subject('Mot de passe oublié');
                $this->email->message('http://localhost/rallyeLecture/index.php/account/changePassword');
                $this->email->send();
            }
            else
            {
                echo "login incorrect";
            }
        }
    }
}
