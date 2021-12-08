<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Message;
use App\Models\User;
use App\Core\View;
use \Exception;

class Controller_Chat extends Controller
{
    function __construct()
    {
        $this->model = new Message();
        $this->user = new User();
        $this->view = new View();
    }

    public function action_dialog()
    {
        if (!isset($_COOKIE['auth'])) {
            header("Location: /?message=Вы не авторизованы!");
            die();
        }
        $currentUser = [];
        $isCurrentUser = false;
        if (isset($_COOKIE['auth'])) {
            $currentUser = $this->user->getCurrentUser();
        }
        if (isset($GLOBALS['GET_PARAMS']['id']) && !empty($GLOBALS['GET_PARAMS']['id'])) {
            $id = (int)$GLOBALS['GET_PARAMS']['id'];
            $user = $this->user->getUserById($id);
            if (!empty($currentUser) && $user !== false && $currentUser['id'] == $user['id']) {
                $isCurrentUser = true;
            }
        } else {
            $user = $currentUser;
            $isCurrentUser = true;
        }
        if ($user === false || empty($user) ) {
            header("Location: /?message=Пользователь не найден!");
            die();
        }

        if ($isCurrentUser) {
            header("Location: /?message=Нельзя писать себе!");
            die();
        }
        $messages = $this->model->getMessages($currentUser['id'], $user['id']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (isset($_POST['message'])) {
                    $message = $_POST['message'];
                    $this->model->addMessage($currentUser['id'], $user['id'], $message);
                    header("Location: /?message=Добавлено!");
                    die();
                }
        }

        $this->view->generate('chat/dialog.php', 'template_view.php', ['currentUser' => $currentUser, 'messages' => $messages] );
    }



}