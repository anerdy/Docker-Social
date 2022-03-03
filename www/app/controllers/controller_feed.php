<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use App\Core\View;
use \Exception;
use App\Services\RabbitMQService;
use App\Services\RedisService;
use \GuzzleHttp\Client as GuzzleClient;

class Controller_Feed extends Controller
{
    private $rabbitMQService;

    function __construct()
    {
        $this->user = new User();
        $this->view = new View();
        $this->rabbitMQService = new RabbitMQService();
        $this->redisService = new RedisService();
    }

    public function action_index()
    {
        $currentUser = [];
        if (!isset($_COOKIE['auth'])) {
            header("Location: /?message=Вы не авторизованы!");
            die();
        } else {
            $currentUser = $this->user->getCurrentUser();
        }

        try {
            $client = new GuzzleClient();
            $response = $client->request('GET', nginx.':81/v1/dialog?user_id='.$currentUser['id']);
            
            $content = json_decode($response->getBody()->getContents(), true);
            $posts = isset($content['posts']) ? $content['posts'] : [];

            $client2 = new GuzzleClient();
            $response = $client2->request('PUT', nginx.':82/v1/notification?user_id='.$currentUser['id']);
            $content = json_decode($response->getBody()->getContents(), true);

        } catch (Exception $e) {
            die('Ошибка получения данных: '.$e->getMessage());
        }

        $this->view->generate('feed/feed.php', 'template_view.php', ['currentUser' => $currentUser, 'posts' => $posts] );
    }

    public function action_add()
    {
        $currentUser = [];
        if (!isset($_COOKIE['auth'])) {
            header("Location: /?message=Вы не авторизованы!");
            die();
        } else {
            $currentUser = $this->user->getCurrentUser();
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['text'])) {
                $text = $_POST['text'];
                $client = new GuzzleClient();
                $response = $client->request('POST', nginx.':81/v1/dialog', [
                    'form_params' => [
                        'user_id' => $currentUser['id'],
                        'text' => $text
                    ]
                ]);            
                $content = json_decode($response->getBody()->getContents(), true);
                if ( isset($content['success']) && $content['success'] == true ) {
                    header("Location: /?message=Добавлено!");
                    die();
                }
            }
        
            header("Location: /?message=Новость не добавлена!");
            die();
        }

        $this->view->generate('feed/add.php', 'template_view.php' );
    }
}