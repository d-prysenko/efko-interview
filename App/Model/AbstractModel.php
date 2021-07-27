<?php

namespace App\Model;

use App\Database\Database;
use PDO;

abstract class AbstractModel
{
    protected function getPdo(): PDO
    {
        return Database::getPdo();
    }
}
