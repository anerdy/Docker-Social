<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Test;
use App\Core\View;
use \Exception;

class Controller_Test extends Controller
{
    function __construct()
    {
        $this->model = new Test();
        $this->view = new View();
    }

    public function action_testadd()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (isset($_POST['name'])) {
                    $name = $_POST['name'];
                    $this->model->addTestName($name);
                    header("Location: /?message=Добавлено!");
                    die();
                }
        }

        $this->view->generate('test/testadd.php', 'template_view.php' );
    }

}