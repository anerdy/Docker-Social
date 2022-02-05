<?php
namespace App\Models;

use App\Core\Model;

class User extends Model
{
    protected $table = 'users';

    protected $allowedFields = [
        'id',
        'login',
        'password'
    ];


    public function getUsers($count = 50, $offset = 0)
    {
        $result = $this->slave->prepare('SELECT * FROM ' . $this->table . ' ORDER BY id ASC LIMIT ' . $count . ' OFFSET ' . $offset . ' ;');
        $result->execute();

        if ($result->rowCount() > 0) {
            return $result->fetchAll(\PDO::FETCH_ASSOC);
        } else {
            return false;
        }
    }

    public function findUsers($name, $surname)
    {
        $name = $name.'%';
        $surname = $surname.'%';
        $result = $this->slave->prepare('SELECT * FROM ' . $this->table . ' WHERE `name` LIKE :name AND `surname` LIKE :surname ORDER BY id ASC;');
        $result->bindParam(':name', $name);
        $result->bindParam(':surname', $surname);
        $result->execute();

        if ($result->rowCount() > 0) {
            return $result->fetchAll(\PDO::FETCH_ASSOC);
        } else {
            return false;
        }
    }

    public function getUsersCount()
    {
        $result = $this->connection->prepare('SELECT * FROM ' . $this->table . ';');
        $result->execute();

        if ($result->rowCount() > 0) {
            return $result->rowCount();
        } else {
            return 0;
        }
    }

    public function isUserExist($login, $password)
    {
        $result = $this->connection->prepare('SELECT * FROM ' . $this->table . ' WHERE `login` = :login AND `password` = :password;');
        $result->bindParam(':login', $login);
        $result->bindParam(':password', $password);
        $result->execute();

        if ($result->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function getUserByLogin($login)
    {
        $result = $this->connection->prepare('SELECT * FROM ' . $this->table . ' WHERE `login` = :login ;');
        $result->bindParam(':login', $login);
        $result->execute();

        if ($result->rowCount() > 0) {
            return $result->fetch(\PDO::FETCH_ASSOC);
        } else {
            return false;
        }
    }

    public function getUserById($id)
    {
        $result = $this->connection->prepare('SELECT * FROM ' . $this->table . ' WHERE `id` = :id ;');
        $result->bindParam(':id', $id, \PDO::PARAM_INT);
        $result->execute();

        if ($result->rowCount() > 0) {
            return $result->fetch(\PDO::FETCH_ASSOC);
        } else {
            return false;
        }
    }

    public function getCurrentUser()
    {
        if (!isset($_COOKIE['auth'])) {
            header("Location: /?message=Вы не авторизованы!");
            die();
        }
        $password = $_COOKIE['auth'];
        $result = $this->connection->prepare('SELECT * FROM ' . $this->table . ' WHERE `password` = :password ;');
        $result->bindParam(':password', $password);
        $result->execute();

        if ($result->rowCount() > 0) {
            return $result->fetch(\PDO::FETCH_ASSOC);
        } else {
            return false;
        }
    }

    public function isUserFriend($userFrom, $userTo)
    {
        $result = $this->connection->prepare('SELECT * FROM user_user WHERE (`user_from` = :user_from AND `user_to` = :user_to) OR (`user_from` = :user_to AND `user_to` = :user_from) ;');
        $result->bindParam(':user_from', $userFrom, \PDO::PARAM_INT);
        $result->bindParam(':user_to', $userTo, \PDO::PARAM_INT);
        $result->execute();

        if ($result->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function addFriend($userFrom, $userTo)
    {
        try {
            $result = $this->connection->prepare('INSERT INTO user_user (user_from, user_to) value (:user_from, :user_to);');
            $result->bindParam(':user_from', $userFrom, \PDO::PARAM_INT);
            $result->bindParam(':user_to', $userTo, \PDO::PARAM_INT);
            $result->execute();

        } catch (\Exception $exception) {
            header("Location: /?message=Ошибка добавления!");
            die();
        }
    }

    public function deleteFriend($currentUser, $delUser)
    {
        try {
            $result = $this->connection->prepare('DELETE FROM user_user WHERE (`user_from` = :user_from AND `user_to` = :user_to) OR (`user_from` = :user_to AND `user_to` = :user_from) ;');
            $result->bindParam(':user_from', $currentUser, \PDO::PARAM_INT);
            $result->bindParam(':user_to', $delUser, \PDO::PARAM_INT);
            $result->execute();

        } catch (\Exception $exception) {
            header("Location: /?message=Ошибка удаления!");
            die();
        }
    }

    public function getUserFriends($userId)
    {
        $userFriends = [];
        $result = $this->connection->prepare('SELECT * FROM user_user WHERE `user_from` = :user_id OR `user_to` = :user_id ;');
        $result->bindParam(':user_id', $userId, \PDO::PARAM_INT);
        $result->execute();

        if ($result->rowCount() > 0) {
            $connections = $result->fetchAll(\PDO::FETCH_ASSOC);
            $friendsIds = [];
            foreach ($connections as $connection) {
                if ($connection['user_from'] != $userId) {
                    $friendsIds[] = $connection['user_from'];
                }
                if ($connection['user_to'] != $userId) {
                    $friendsIds[] = $connection['user_to'];
                }
            }
            if (!empty($friendsIds)) {
                $where = '';
                foreach ($friendsIds as $key => $friendsId) {
                    if ( array_key_first($friendsIds) != $key ) {
                        $where .= ' , ';
                    }
                    $where .= ':user_'.$key;
                }
                $friends = $this->connection->prepare('SELECT * FROM ' . $this->table . ' WHERE `id` IN ('.$where.');');
                foreach ($friendsIds as $key => &$friendsId) {
                    $friends->bindParam(':user_'.$key, $friendsId, \PDO::PARAM_INT);
                }
                $friends->execute();
                if ($friends->rowCount() > 0) {
                    $userFriends = $friends->fetchAll(\PDO::FETCH_ASSOC);
                }
            }
        }

        return $userFriends;
    }

    public function createUser($login, $password, $name, $surname, $age, $gender, $interests, $city)
    {
        try {
            $result = $this->connection->prepare('INSERT INTO ' . $this->table . ' (login, password, name, surname, age, gender, interests, city) 
        value (:login, :password, :name, :surname, :age, :gender, :interests, :city);');
            $result->bindParam(':login', $login);
            $result->bindParam(':password', password_hash($password, PASSWORD_DEFAULT));
            $result->bindParam(':name', $name);
            $result->bindParam(':surname', $surname);
            $result->bindParam(':age', $age, \PDO::PARAM_INT);
            $result->bindParam(':gender', $gender, \PDO::PARAM_INT);
            $result->bindParam(':interests', $interests);
            $result->bindParam(':city', $city);
            $result->execute();
        } catch (\Exception $exception) {
            header("Location: /auth/register?message=Ошибка сохранения!");
            die();
        }
    }

}
