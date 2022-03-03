<?php
namespace App\Services;

use App\Core\Controller;
use App\Models\Post;
use App\Models\User;
use App\Core\View;
use \Exception;
use Predis\Client as RedisClient;


class RedisService
{
    private $client;

    function __construct()
    {
        $this->client = new RedisClient([
            'host' => redis,
            "password" => "testpass"
        ]);

    }

    public function setNotifications($countNotifications, $userId)
    {
        try {
            $this->client->set('notifications'.$userId, $countNotifications);
        } catch (Exception $e) {
            die('Ошибка Redis: '.$e->getMessage());
        }
    }

    public function getNotifications(int $userId): int
    {
        try {
            $notifications = $this->client->get('notifications'.$userId);
        } catch (Exception $e) {
            die('Ошибка Redis: '.$e->getMessage());
        }
        if (is_null($notifications)) {
            $notifications = 0;
        }

        return $notifications;
    }

    public function deleteNotifications(int $userId)
    {
        try {
            $this->client->del('notifications'.$userId);
        } catch (Exception $e) {
            die('Ошибка Redis: '.$e->getMessage());
        }
    }

}
