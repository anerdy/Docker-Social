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

}
