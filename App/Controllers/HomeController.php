<?php

namespace App\Controllers;

use System\Controller;

class HomeController extends Controller
{
    public function index()
    {
        $user =  $this->db->query('SELECT * from users WHERE id = ? ', 1)->fetch();

        $this->db->data('email', 'mohamed@gmail.com')
            ->where('id=?', 1)
            ->update('users');

        pre($user);
        // echo $this->db->data([
        //     'first_name' => 'Mohamed',
        //     "last_name" => '25'
        // ])->insert('users')->lastId();

        $this->response->setHeader('name', 'Mohamed');
        $data['name'] = "Mohamed";

        return  $this->view->render('main/home', $data);
    }
}
