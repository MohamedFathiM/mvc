<?php

namespace App\Controllers;

use System\Controller;

class HomeController extends Controller
{
    public function index()
    {
        $this->response->setHeader('name', 'Mohamed');
        $data['name'] = "Mohamed";

        return  $this->view->render('main/home', $data);
    }
}
