<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use App\Core\View;
use \Exception;
use App\Services\RabbitMQService;
use App\Services\RedisService;

class Controller_V1 extends Controller
{

    function __construct()
    {
        $this->user = new User();
        $this->view = new View();
        $this->rabbitMQService = new RabbitMQService();
        $this->redisService = new RedisService();
    }

    public function action_notification()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $currentUser = [];
            if (!isset($GLOBALS['GET_PARAMS']['user_id'])) {
                print json_encode(['error' => 'User not found.'], JSON_PRETTY_PRINT);
                die;
            } else {
                $userId = (int)$GLOBALS['GET_PARAMS']['user_id']; // get
            }
    
            $countNotifications = $this->rabbitMQService->getNotifications($userId);
            if ($countNotifications != 0) {
                $redisNotifications = $this->redisService->getNotifications($userId);
                $countNotifications = $countNotifications + $redisNotifications;
                $this->redisService->setNotifications($countNotifications, $userId);
            } else {
                $countNotifications = $this->redisService->getNotifications($userId);
            }
            
            print json_encode(['notifications' => $countNotifications], JSON_PRETTY_PRINT);
            die;
        } else if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (isset($_POST['user_id'])) {
                $userId = (int)$_POST['user_id'];
                $friends = $this->user->getUserFriends($userId);
                $friendsIds = [];
                foreach($friends as $friend) {
                    $friendsIds[] = $friend['id'];
                }
                $this->rabbitMQService->addNotifications($friendsIds);

                print json_encode(['success' => true], JSON_PRETTY_PRINT);
                die;
            }

        } else if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            if (isset($GLOBALS['GET_PARAMS']['user_id'])) {
                $userId = (int)$GLOBALS['GET_PARAMS']['user_id'];
                $this->redisService->deleteNotifications($userId);
                print json_encode(['success' => true], JSON_PRETTY_PRINT);
                die;
            }
        }
        print json_encode(['error' => 'Not supported method.'], JSON_PRETTY_PRINT);
        die;
    }

}