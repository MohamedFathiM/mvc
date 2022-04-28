<?php

namespace App\Controllers;

use System\Controller;

class HomeController extends Controller
{
    public function index()
    {
        //    $this->url->link('/home');
        //    $this->url->redirectTo('/home/posts/1');
        echo assets('images/image.png');
    }
}
