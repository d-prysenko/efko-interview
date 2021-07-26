<?php

namespace App\Model;

use PDOException;

class User extends AbstractModel
{
    public ?int $id;
    public ?string $role;

    public function __construct()
    {
        $this->id = 0;
        $this->role = null;

        if (isset($_COOKIE['id'], $_COOKIE['value'])) {
            $db = $this->getPdo();
            $stmt = $db->prepare('SELECT users.id, role FROM users, cookies WHERE cookies.id = ? AND value = ?');
            $stmt->execute([$_COOKIE['id'], $_COOKIE['value']]);

            $user = $stmt->fetch();

            if ($user) {
                $this->id = $user['id'];
                $this->role = $user['role'];
            }
        }
    }

    public function isAuth(): bool
    {
        return $this->id != 0;
    }

    public function verify($email, $password): bool
    {
        if ($email == '' || strlen($password) < 6) {
            $this->id = 0;
            return false;
        }

        $db = $this->getPdo();

        $stmt = $db->prepare("SELECT * FROM users WHERE `email`= ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password . $user['salt'], $user['hash'])) {
            $this->id = $user['id'];
            return true;
        }

        return false;
    }

    public function setCookies()
    {
        $value = uniqid('', true);

        $db = $this->getPdo();
        $stmt = $db->prepare("INSERT INTO cookies VALUES (NULL, ?, ?)");
        $stmt->execute([$this->id, $value]);

        setcookie("id", $db->lastInsertId(), time()+60*60*24*365);
        setcookie("value", $value, time()+60*60*24*365);
    }

    public function deleteCookies()
    {
        if (isset($_COOKIE['id'], $_COOKIE['value'])) {
            $db = $this->getPdo();
            try {
                $stmt = $db->prepare("DELETE FROM cookies WHERE `id` = ? AND `value` = ?");
                $stmt->execute([$_COOKIE['id'], $_COOKIE['value']]);
                setcookie('id', '', 0);
                setcookie('value', '', 0);
            } catch (PDOException $e) {
                echo "Неудалось выполнить запрос к базе данных: " . $e->getMessage();
                exit(-1);
            }
        }
    }

    public function isAdmin(): bool
    {
        return $this->role == 'admin';
    }

    public function isWriter(): bool
    {
        return $this->role == 'writer';
    }

    public function isEvaluator(): bool
    {
        return $this->role == 'evaluator';
    }
}
