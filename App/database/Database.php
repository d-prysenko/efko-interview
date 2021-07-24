<?php

namespace App\Database;

use PDO;

class Database
{
    private static ?PDO $pdo = null;

    public static function getPdo(): PDO
    {
        if (!static::$pdo) {
            $db['host'] = 'localhost';
            $db['user'] = 'root';
            $db['password'] = '';
            $db['dbname'] = 'efco';
            static::$pdo = new PDO('mysql:host='.$db['host'].';dbname='.$db['dbname'], $db['user'], $db['password']);
            static::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            static::$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        }

        return static::$pdo;
    }
}
