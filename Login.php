<?php

if (!defined('BASEPATH'))
    exit("Pas d'accès direct aux scripts");

/** @property EleveModel $eleveModel
 *  @property EnseignantModel $enseignantModel
 *  @property Aauth $aauth
 *  @property CI_session $session
 */
class Login extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('aauth');
        $this->load->model('eleveModel');
        $this->load->model('enseignantModel');
        $this->load->model('LoginModel');
        $this->create();
    }

    public function index() {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('login','Login','trim|required|xss_clean|max_length[100]');
        $this->form_validation->set_rules('password','Password','trim|required|xss_clean|callback_check_password');

        if ($this->form_validation->run()) {
            $userAuth=$this->aauth->get_user();
            $groupe=$this->getGroupe();
            $user=$this->getUser($groupe);

            $this->session->login=$userAuth->email;
            $this->session->groupe=$groupe;
            $this->session->iduser=$user['id'];
            $this->session->username=$user['prenom'].' '.$user['nom'];
            redirect('home');
        }
        else {
            $data['title']='Connectez vous';
            $this->load->view('AppHeader',$data);
            $this->load->view('Login');
            $this->load->view('AppFooter');
        }
    }

    private function create() {
        $this->aauth->create_user('admin@sio.jjr.fr','siojjr','Admin');
        $this->aauth->add_member($this->aauth->get_user_id('admin@sio.jjr.fr'),'Admin');
        $this->aauth->create_group('Elève');
        $this->aauth->create_group('Enseignant');
        //$this->aauth->add_member($this->aauth->get_user_id('olivier.debbache@gmail.com'),'Enseignant');
        // foreach ($this->aauth->list_groups() as $g) {
        //    echo $g->name;
        //    echo $g->definition."<br>";
        // }
        // $this->aauth->create_user('olivier.debbache@gmail.com','siojjr','oDebbache');
        // $this->EnseignantModel->add_enseignant(array('nom'=>'debbache','prenom'=>'olivier',''=>'olivier.debbache@gmail.com'));
    }

    function check_password($password) {
        $ok=FALSE;
        //on récupére le user
        $login=$this->input->post('login');
        if ($this->aauth->login($login,$password,true)) {
            $ok=TRUE;
        }
        else {
            $this->form_validation->set_message('check_password','login ou mot de passe invalides');
            $ok=FALSE;
        }
        return $ok;
    }

    private function getGroupe() {
        // pour notre application un user ne peut appartenir qu'à un seul groupe : {'admin','élève','enseignant','visiteur'}
        if ($this->aauth->is_member('Elève')) {
            $groupe='Elève';
        }
        else {
            if ($this->aauth->is_member('Enseignant')) {
                $groupe='Enseignant';
            }
            else {
                if ($this->aauth->is_member('Admin')) {
                    $groupe='Admin';
                }
                else {
                    $groupe='Visiteur';
                }
            }
        }
        return $groupe;
    }

    private function getUser($groupe) {
        $idAuth=$this->aauth->get_user()->id;
        switch ($groupe) {
            case 'Enseignant':
                $row=$this->enseignantModel->get_Where('idAuth',$idAuth);
                $user=array(
                    'id'=>$row[0]['id'],
                    'nom'=>$row[0]['nom'],
                    'prenom'=>$row[0]['prenom']
                );
                break;
            case 'Elève':
                $row=$this->eleveModel->get_Where('idAuth',$idAuth);
                $user=array(
                    'id'=>$row[0]['id'],
                    'nom'=>$row[0]['nom'],
                    'prenom'=>$row[0]['prenom']
                );
                break;
            default:
                $user=NULL;
                break;
        }
        return $user;
    }
    
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