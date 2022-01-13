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

    public function setPosts($posts, $userId)
    {
        try {
            $this->client->set('feed'.$userId, serialize($posts));
        } catch (Exception $e) {
            die('Ошибка Redis: '.$e->getMessage());
        }
    }

    public function getPosts($userId): array
    {
        try {
            $posts = $this->client->get('feed'.$userId);
        } catch (Exception $e) {
            die('Ошибка Redis: '.$e->getMessage());
        }
        if (is_null($posts)) {
            $posts = [];
        } else {
            $posts = unserialize($posts);
        }

        return $posts;
    }


}
