<?php

namespace App\Controller;
use System\Controller;
use App\Model\Queries;

class Admin extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->Queries = new Queries();
    }

    public function dashboard()
    {
        $this->load->view('admin/dashboard');
    }

    public function route()
    {
        $this->load->view('admin/header');
        $response['route'] = $this->Queries->fetchPages();
        $this->load->view('admin/route', $response);

        if(isset($_POST['submit'])) {

            $route = [
                'page_name'        => request('page_name'),
                'page_route'       => request('page_route'),
                'page_title'       => request('page_title'),
                'page_description' => request('page_description'),
                'page_keywords'    => request('page_keywords')
            ];

            //Calling Model to Insert
            $run = $this->Queries->insertPage($route);
        }
    }

    public function del_route($id)
    {
        //Calling Model
        $run = $this->Queries->del_route($id);
    }

    public function api_token()
    {
        $this->load->view('admin/header');
        $response['token'] = $this->Queries->fetchAPIToken();
        $this->load->view('admin/api_token', $response);

        if(isset($_POST['submit'])) {

            $data = [
                'token'          => hashToken(request('token')),
                'token_secret'   => hashToken(request('token_secret')),
                'user_title'     => request('user_title'),
                'user_description'=> request('user_description'),
                'expire_at'      => request('expire_at'),
            ];

            //Call the Model to Insert
            $run = $this->Queries->insertTokenAPI($data);

        }
    }

    public function schedulers()
    {
        $this->load->view('admin/header');
        $response['scheduler'] = $this->Queries->fetchSchedulers();
        $this->load->view('admin/scheduler', $response);
    }

    public function bug_report()
    {
        //We are working on that
    }

    public function users()
    {
        $this->load->view('admin/header');
        $response['user'] = $this->Queries->fetchUsers();
        $this->load->view('admin/users', $response);
    }
}
