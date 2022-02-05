<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use App\Core\View;
use \Exception;

class Controller_Auth extends Controller
{

    function __construct()
    {
        $this->model = new User();
        $this->view = new View();
    }

    public function action_register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $isUserExist = $this->model->isUserExist($_POST['login'], $_POST['password']);
            if ($isUserExist) {
                header("Location: /?message=Пользователь уже зарегистрирован!");
                die();
            } else {
                if (empty($_POST['login']) || empty($_POST['password']) || empty($_POST['name']) || empty($_POST['surname']) || empty($_POST['age']) ||
                    empty($_POST['gender']) || empty($_POST['interests']) || empty($_POST['city']) ) {
                    header("Location: /auth/register?message=Не все поля заполнены!");
                    die();
                }

                $this->model->createUser($_POST['login'], $_POST['password'], $_POST['name'], $_POST['surname'], $_POST['age'],
                    $_POST['gender'], $_POST['interests'], $_POST['city']
                );
                header("Location: /?message=Пользователь успешно создан, авторизуйтесь!");
                die();
            }
        }
        $this->view->generate('auth/register.php', 'template_view.php' );
    }

    public function action_login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $user = $this->model->getUserByLogin($_POST['login']);
            if ($user === false) {
                header("Location: /?message=Пользователь не зарегистрирован!");
                die();
            } else {
                if(password_verify($_POST['password'], $user['password'])) {
                    if( ! isset($_COOKIE['auth'])) {
                        setcookie('auth', $user['password'], time() + (86400 * 30), "/");
                        header("Location: /?message=Успешная авторизация!");
                        die();
                    } else {
                        header("Location: /?message=Вы уже авторизованы!");
                        die();
                    }
                } else {
                    header("Location: /?message=Неверный пароль!");
                    die();
                }
            }

        }
        $this->view->generate('auth/login.php', 'template_view.php' );
    }

    public function action_logout()
    {
        setcookie("auth", "", time() - 3600, "/");
        header("Location: /");
        die();
    }

}