<?php
namespace App\Models;

use App\Core\Model;

class Post extends Model
{
    protected $table = 'social_db.posts';

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