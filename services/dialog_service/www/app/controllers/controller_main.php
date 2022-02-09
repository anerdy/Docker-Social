<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use App\Core\View;

class Controller_Main extends Controller
{
    function __construct()
    {
        $this->model = new User();
        $this->view = new View();
    }

    public function action_index()
    {
        die('Api service');
    }

}