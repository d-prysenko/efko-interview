<?php

namespace App\Model;

use http\Exception\InvalidArgumentException;
use PDOException;

class User extends AbstractModel
{
    public ?int $id;
    public ?string $role;
    public ?bool $verified;

    public function __construct()
    {
        $this->id = 0;
        $this->role = null;
        $this->verified = false;

        if (isset($_COOKIE['id'], $_COOKIE['value'])) {
            $db = $this->getPdo();
            $stmt = $db->prepare('SELECT role, users.id, verified FROM users INNER JOIN cookies ON (users.id=cookies.user_id) WHERE cookies.id = ? AND cookies.value = ?');
            $stmt->execute([$_COOKIE['id'], $_COOKIE['value']]);

            $user = $stmt->fetch();

            if ($user) {
                $this->id = $user['id'];
                $this->role = $user['role'];
                $this->verified = $user['verified'];
            }
        }
    }

    public function isAuth(): bool
    {
        return $this->id != 0;
    }

    public function passwordVerify($email, $password): bool
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
            $this->verified = $user['verified'];
            return true;
        }

        return false;
    }

    public function register($email, $password, $role, $name, $surname): bool
    {
        try {
            $salt = uniqid();
            $hash = password_hash($password.$salt, PASSWORD_DEFAULT);

            $db = $this->getPdo();

            $user_email = $db->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
            $user_email->execute([$email]);

            if ($user_email->fetchColumn()) {
                throw new \InvalidArgumentException("Пользователь с такой почтой уже существует!");
            }

            $stmt = $db->prepare("INSERT INTO users VALUES (NULL, :role, false, :name, :surname, :email, :hash, :salt)");
            $stmt->execute([
                ':role' => $role,
                ':name' => $name,
                ':surname' => $surname,
                ':email' => $email,
                ':hash' => $hash,
                ':salt' => $salt
            ]);

            $this->id = $db->lastInsertId();
        } catch (PDOException $e) {
            throw $e;
        }

        return true;
    }

    public function setCookies()
    {
        $value = uniqid('', true);

        try {
            $db = $this->getPdo();
            $stmt = $db->prepare("INSERT INTO cookies VALUES (NULL, ?, ?)");
            $stmt->execute([$this->id, $value]);

            setcookie("id", $db->lastInsertId(), time()+60*60*24*365);
            setcookie("value", $value, time()+60*60*24*365);
        } catch (PDOException $e) {
            echo "Неудалось выполнить запрос к базе данных: " . $e->getMessage();
            exit(-1);
        }
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
