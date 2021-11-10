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
        $page = 1;
        if (isset($_COOKIE['auth'])) {
            $count = 10;
            if (isset($_GET['page'])) {
                $page = (int)$_GET['page'];
                $offset = $count * $page - $count;
                $users = $this->model->getUsers($count, $offset);
            } else {
                $users = $this->model->getUsers($count);
            }
            $currentUser = $this->model->getCurrentUser();
        } else {
            $users = [];
            $currentUser = [];
        }

        $this->view->generate('main.php', 'template_view.php', ['users' => $users, 'currentUser' => $currentUser, 'page' => $page] );
    }

}