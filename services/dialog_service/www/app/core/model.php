<?php
namespace App\Core;

use \PDO;

class Model
{
    /** @var $connection PDO | null  */
    protected $connection = null;

    /** @var $slave PDO | null  */
    protected $slave = null;

    /** @var $proxy PDO | null  */
    protected $proxy = null;

    public function __construct() {
        $this->connection = Database::getInstance();
   //     $this->slave = Database::getSlaveInstance();
     //   $this->proxy = Database::getProxyInstance();
        $this->slave = $this->connection;
        $this->proxy = $this->connection;
    }


    /**
     * @param array $fields
     * @return \PDOStatement
     */
    public function getAll($fields = [])
    {
        $fieldsArray = $this->validateFields($fields);
        $fields = implode(',', $fieldsArray);

        $sql = "SELECT ".$fields." FROM " . $this->table;
        $result = $this->connection->query($sql);

        return $result;
    }

    /**
     * Валидация полей
     *
     * @param array $fields
     * @return array
     */
    private function validateFields($fields = [])
    {
        $correctFields = [];
        foreach ($fields as $field) {
            if (in_array($field, $this->allowedFields)) {
                $correctFields[] = $field;
            }
        }
        if (empty($correctFields)) {
            die('Empty fields array');
        }
        return $correctFields;
    }


}