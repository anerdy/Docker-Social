<?php
namespace App\Core;

use \PDO;
use \PDOException;

class Database {

    private static $mysql = null;
    private static $slave = null;
    private static $proxy = null;

    protected function __construct() { }

    protected function __clone() { }

    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize singleton");
    }

    public static function getInstance()
    {
        if (static::$mysql === null) {
            try {
                static::$mysql = new PDO("mysql:host=".DBHOST.";dbname=".DBNAME, DBUSER, DBPASS);
                static::$mysql->query("SET NAMES 'utf8'");
            } catch (PDOException $e) {
                die('Подключение не удалось: ' . $e->getMessage());
            }
        }

        return static::$mysql;
    }

    public static function getSlaveInstance()
    {
        if (static::$slave === null) {
            try {
                static::$slave = new PDO("mysql:host=".DBHOST2.";dbname=".DBNAME2, DBUSER2, DBPASS2);
                static::$slave->query("SET NAMES 'utf8'");
            } catch (PDOException $e) {
                die('Подключение не удалось: ' . $e->getMessage());
            }
        }

        return static::$slave;
    }

    public static function getProxyInstance()
    {
        if (static::$proxy === null) {
            try {
                //var_dump(PROXYSQL_DBHOST);die;
                static::$proxy = new PDO("mysql:host=".PROXYSQL_DBHOST.";port=6033;dbname=".PROXYSQL_DBNAME, PROXYSQL_DBUSER, PROXYSQL_DBPASS);
                static::$proxy->query("SET NAMES 'utf8'");
            } catch (PDOException $e) {
                die('Подключение не удалось: ' . $e->getMessage());
            }
        }

        return static::$proxy;
    }

}