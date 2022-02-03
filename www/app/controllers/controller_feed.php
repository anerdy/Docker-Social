<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Post;
use App\Models\User;
use App\Core\View;
use \Exception;
use App\Services\RabbitMQService;
use App\Services\RedisService;

class Controller_Feed extends Controller
{
    private $rabbitMQService;

    function __construct()
    {
        $this->model = new Post();
        $this->user = new User();
        $this->view = new View();
        $this->rabbitMQService = new RabbitMQService();
        $this->redisService = new RedisService();
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

        $posts = $this->rabbitMQService->getPosts($currentUser['id']);
        if (!empty($posts)) {
            $redisPosts = $this->redisService->getPosts($currentUser['id']);
            $posts = array_merge($posts, $redisPosts);
            $this->redisService->setPosts($posts,$currentUser['id']);
        } else {
            $posts = $this->redisService->getPosts($currentUser['id']);
        }

        $this->view->generate('feed/feed.php', 'template_view.php', ['currentUser' => $currentUser, 'posts' => $posts] );
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
                $friends = $this->user->getUserFriends($currentUser['id']);
                $friendsIds = [];
                foreach($friends as $friend) {
                    $friendsIds[] = $friend['id'];
                }
                $text = $_POST['text'];
                $this->model->addPost($currentUser['id'], $text);

                $this->rabbitMQService->addPosts($friendsIds, $text);

                header("Location: /?message=Добавлено!");
                die();
            }
        }

        $this->view->generate('feed/add.php', 'template_view.php' );
    }
}