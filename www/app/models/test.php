<?php
namespace App\Models;

use App\Core\Model;

class Test extends Model
{
    protected $table = 'testtable';

    protected $allowedFields = [
        'id',
        'name'
    ];


    public function addTestName($name)
    {
        try {
            $result = $this->connection->prepare('INSERT INTO ' . $this->table . ' (name) value (:name);');
            $result->bindParam(':name', $name);
            $result->execute();
        } catch (\Exception $exception) {
            header("Location: /test/testadd?massage=".$exception->getMessage());
            die();
        }
    }

}
