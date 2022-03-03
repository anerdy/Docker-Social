<?php
namespace App\Core;

use App\Models\User;
use \GuzzleHttp\Client as GuzzleClient;

class View
{
    function __construct()
    {
        $this->user = new User();
    }

    function generate($content_view, $template_view, $data = null)
    {
        if (!isset($_COOKIE['auth'])) {
            header("Location: /?message=Вы не авторизованы!");
            die();
        } else {
            $currentUser = $this->user->getCurrentUser();
        }

        try {
            $client = new GuzzleClient();
            $response = $client->request('GET', nginx.':82/v1/notification?user_id='.$currentUser['id']);

            $content = json_decode($response->getBody()->getContents(), true);
            $notifications = isset($content['notifications']) ? $content['notifications'] : 0;
        } catch (Exception $e) {
            die('Ошибка получения данных: '.$e->getMessage());
        }

        $data['notifications'] = $notifications;
        include 'app/views/'.$template_view;
    }
}