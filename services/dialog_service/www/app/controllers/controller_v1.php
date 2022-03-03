<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Post;
use App\Models\User;
use App\Core\View;
use \Exception;
use App\Services\RabbitMQService;
use App\Services\RedisService;
use \GuzzleHttp\Client as GuzzleClient;

class Controller_V1 extends Controller
{

    function __construct()
    {
        $this->model = new Post();
        $this->user = new User();
        $this->view = new View();
        $this->rabbitMQService = new RabbitMQService();
        $this->redisService = new RedisService();
    }

    public function action_dialog()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $currentUser = [];
            if (!isset($GLOBALS['GET_PARAMS']['user_id'])) {
                print json_encode(['error' => 'User not found.'], JSON_PRETTY_PRINT);
                die;
            } else {
                $userId = (int)$GLOBALS['GET_PARAMS']['user_id']; // get
            }
    
            $posts = $this->rabbitMQService->getPosts($userId);
            if (!empty($posts)) {
                $redisPosts = $this->redisService->getPosts($userId);
                $posts = array_merge($posts, $redisPosts);
                $this->redisService->setPosts($posts, $userId);
            } else {
                $posts = $this->redisService->getPosts($userId);
            }
            
            print json_encode(['posts' => $posts], JSON_PRETTY_PRINT);
            die;
        } else if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (isset($_POST['user_id']) && isset($_POST['text'])) {
                $userId = (int)$_POST['user_id'];
                $friends = $this->user->getUserFriends($userId);
                $friendsIds = [];
                foreach($friends as $friend) {
                    $friendsIds[] = $friend['id'];
                }
                $text = $_POST['text'];
                $this->model->addPost($userId, $text);

                $this->rabbitMQService->addPosts($friendsIds, $text);

                try {
                    $client = new GuzzleClient();
                    $response = $client->request('POST', nginx.':82/v1/notification', [
                        'form_params' => [
                            'user_id' => $userId
                        ]
                    ]);
                    $content = json_decode($response->getBody()->getContents(), true);
                    if ( !isset($content['success']) || $content['success'] != true ) {
                        print json_encode(['error' => 'Ошибка отправки уведомления.'.$userId.serialize($content)], JSON_PRETTY_PRINT);
                        die;
                    }
                } catch (Exception $e) {
                    print json_encode(['error' => 'Ошибка получения данных: '.$e->getMessage()], JSON_PRETTY_PRINT);
                    die;
                }

                print json_encode(['success' => true], JSON_PRETTY_PRINT);
                die;
            }

        } 
        print json_encode(['error' => 'Not supported method.'], JSON_PRETTY_PRINT);
        die;
    }

}