<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use App\Core\View;
use \Exception;

class Controller_User extends Controller
{
    function __construct()
    {
        $this->model = new User();
        $this->view = new View();
    }

    public function action_profile()
    {
        if (!isset($_COOKIE['auth'])) {
            header("Location: /?message=Вы не авторизованы!");
            die();
        }
        $currentUser = [];
        $isCurrentUser = false;
        $isFriend = false;
        if (isset($_COOKIE['auth'])) {
            $currentUser = $this->model->getCurrentUser();
        }
        if (isset($GLOBALS['GET_PARAMS']['id']) && !empty($GLOBALS['GET_PARAMS']['id'])) {
            $id = (int)$GLOBALS['GET_PARAMS']['id'];
            $user = $this->model->getUserById($id);
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
        unset($user['password']);

        if (!$isCurrentUser) {
            $isFriend = $this->model->isUserFriend($currentUser['id'], $user['id']);
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!$isCurrentUser) {
                if (isset($_POST['delete']) && $isFriend) {
                    $this->model->deleteFriend($currentUser['id'], $user['id']);
                    header("Location: /user/friends?message=Пользователь успешно удален из друзей!");
                    die();
                }

                if ($isFriend) {
                    header("Location: /?message=Пользователь уже ваш друг!");
                    die();
                } else {
                    $this->model->addFriend($currentUser['id'], $user['id']);
                    header("Location: /?message=Пользователь успешно добавлен в друзья!");
                    die();
                }
            }
        }

        $this->view->generate('user/profile.php', 'template_view.php', ['user' => $user, 'isCurrentUser' => $isCurrentUser, 'isFriend' => $isFriend] );
    }

    public function action_friends()
    {
        if (!isset($_COOKIE['auth'])) {
            header("Location: /?message=Вы не авторизованы!");
            die();
        }
        $currentUser = $this->model->getCurrentUser();
        $friends = $this->model->getUserFriends($currentUser['id']);

        $this->view->generate('user/friends.php', 'template_view.php', ['friends' => $friends] );
    }

    public function action_find()
    {
        $users = [];
        $page = 1;
        $name = '';
        $surname = '';
        $urlParts = explode('/', $_SERVER['REQUEST_URI']);
        if (isset($urlParts[3]) && isset($urlParts[4])) {
            $name = urldecode($urlParts[3]);
            $surname = urldecode($urlParts[4]);
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST' ) { // && isset($_COOKIE['auth'])
            if (isset($_POST['name']) && isset($_POST['surname'])) {
                $name = $_POST['name'];
                $surname = $_POST['surname'];
            }
        }
        if (!empty($name) && !empty($surname)) {
            $users = $this->model->findUsers($name, $surname);
        }

        $this->view->generate('user/find.php', 'template_view.php', ['users' => $users, 'page' => $page, 'name' => $name, 'surname' => $surname] );
    }


}