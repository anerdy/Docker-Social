<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Post;
use App\Models\User;
use App\Core\View;
use \Exception;
//use Acme\AmqpWrapper\SimpleSender;
use App\Services\RabbitMQService;


class Controller_Feed extends Controller
{
    private $rabbitMQService;

    function __construct()
    {
        $this->model = new Post();
        $this->user = new User();
        $this->view = new View();
        $this->rabbitMQService = new RabbitMQService();
    }

    public function action_index()
    {
        if (!isset($_COOKIE['auth'])) {
            header("Location: /?message=Вы не авторизованы!");
            die();
        }
        $currentUser = [];
        if (isset($_COOKIE['auth'])) {
            $currentUser = $this->user->getCurrentUser();
        }

        $this->rabbitMQService->listen($currentUser['id']);

        $this->view->generate('feed/feed.php', 'template_view.php', ['currentUser' => $currentUser] );
    }

    public function action_add()
    {
        if (!isset($_COOKIE['auth'])) {
            header("Location: /?message=Вы не авторизованы!");
            die();
        }
        $currentUser = [];
        if (isset($_COOKIE['auth'])) {
            $currentUser = $this->user->getCurrentUser();
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['text'])) {
                $text = $_POST['text'];
                $this->model->addPost($currentUser['id'], $text);

                $this->rabbitMQService->execute($currentUser['id'], $text);

                header("Location: /?message=Добавлено!");
                die();
            }
        }

        $this->view->generate('feed/add.php', 'template_view.php' );
    }


}