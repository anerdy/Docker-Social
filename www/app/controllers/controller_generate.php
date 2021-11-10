<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use App\Core\View;
use \Exception;


class Controller_Generate extends Controller
{
    function __construct()
    {
        $this->model = new User();
        $this->view = new View();
    }

    public function action_users()
    {
        $countUsers = $this->model->getUsersCount();
        var_dump($countUsers);die;
        if ($countUsers < 1000000) {
            for ($i = $countUsers+1; $i <= 1000000; $i++) {
                $person = new \Faker\Generator();
                $person->addProvider(new \Faker\Provider\ru_RU\Person($person));
                $randGender = rand(1,2);
                $male = $randGender === 1 ? 'male' : 'female';

//                $company = new \Faker\Generator();
  //              $company->addProvider(new \Faker\Provider\ru_RU\Company($company));

                $address = new \Faker\Generator();
                $address->addProvider(new \Faker\Provider\ru_RU\Address($address));

                $this->model->createUser('login'.$i, 'test', $person->firstName($male), $person->lastName($male), rand(18,100),
                    $randGender, 'interest'.$i, $address->city );
            }
        }


    }



}