<?php

namespace App\Controller;
use App\Model\General_Model;
use System\Controller;
use App\Model\UserModel;
use System\Helpers\Session;

class User extends Controller
{
    private $userModel;
    public function __construct()
    {
        parent::__construct();
        $this->session = new Session();
        $this->userModel = new UserModel();
        $this->General_Model = new General_Model();
    }
    public function signin()
    {
        $route = 'signin';

        //Get Page Data
        $response = $this->General_Model->PageDetail($route);
        $this->load->view('pages/header', $response);
        //$this->load->view('user/signin');

        if (isset($_POST['btn'])) {
            $email = $this->post('email');
            $password = md5($this->post('password'));

            // Query the login table
            $user = $this->userModel->findLogin($email, $password);

            if ($user) {

                $this->session->set('user_id', $user['user_id']);
                $this->session->set('email', $user['email']);

                echo "<div style='color: green;'>Login successful! Welcome {$user['email']}.</div>";
            } else {
                echo "<div style='color: red;'>Invalid email or password.</div>";
            }
        }
    }

    public function signup()
    {
        $this->load->view('user/signup');

        if(isset($_POST['btn'])) {

            $login = array(
                'email' => $this->post('email'),
                'password' => md5($this->post('password'))
            );

            $userId = $this->userModel->insertLogin($login);

            $userDetail = array(
                'user_id' => $userId,
                'first_name' => $this->post('first_name'),
                'last_name' => $this->post('last_name'),
            );

            //Calling Model to Insert Data in Table
            $run = $this->userModel->insertUserDetails($userDetail);

            if (!$run) {
                echo "<div style='color: green;'>User registered successfully!</div>";
            } else {
                echo "<div style='color: red;'>Failed to save user details.</div>";
            }

        }
    }
}
