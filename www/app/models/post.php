<?php
namespace App\Models;

use App\Core\Model;

class Post extends Model
{
    protected $table = 'social_db.posts';

    const SHARD_COUNT = 3;


    public function getMessages($from, $to)
    {
        $shardId = $this->getShardId($from, $to);
        $result = $this->proxy->prepare('/* shard = '.$shardId.' */ SELECT * FROM ' . $this->table . ' WHERE (`author_id` = :author_id AND `receiver_id` = :receiver_id) OR (`author_id` = :receiver_id AND `receiver_id` = :author_id) ;');
        $result->bindParam(':author_id', $from);
        $result->bindParam(':receiver_id', $to);
        $result->execute();

        if ($result->rowCount() > 0) {
            return $result->fetchAll(\PDO::FETCH_ASSOC);
        } else {
            return false;
        }
    }

    public function addPost($authorId, $message)
    {
        try {
            $result = $this->proxy->prepare('INSERT INTO posts (author_id, text, created_at) values (:author_id, :message, NOW());');
            $result->bindParam(':author_id', $authorId, \PDO::PARAM_INT);
            $result->bindParam(':message', $message);
            $result->execute();
        } catch (\Exception $exception) {
            header("Location: /");
            die();
        }
    }


}
