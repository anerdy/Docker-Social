<?php
namespace App\Models;

use App\Core\Model;

class Message extends Model
{
    protected $table = 'social_db.messages';

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

    public function addMessage($from, $to, $message)
    {
        $shardId = $this->getShardId($from, $to);
        try {
            $result = $this->proxy->prepare('/* shard = '.$shardId.' */ INSERT INTO messages (author_id, receiver_id, message) values (:author_id, :receiver_id, :message);');
            $result->bindParam(':author_id', $from, \PDO::PARAM_INT);
            $result->bindParam(':receiver_id', $to, \PDO::PARAM_INT);
            $result->bindParam(':message', $message);
            $result->execute();
        } catch (\Exception $exception) {
            header("Location: /");
            die();
        }
    }

    private function getShardId($from, $to)
    {
        $sum = (int)$from + (int)$to;
        $rest = $sum % self::SHARD_COUNT;

        return $rest;
    }

}
