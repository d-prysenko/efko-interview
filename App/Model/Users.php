<?php


namespace App\Model;

class Users extends AbstractModel
{
    public function getNewUsersList(): array
    {
        $db = $this->getPdo();
        $stmt = $db->prepare("SELECT * FROM users WHERE verified = false ORDER BY id DESC LIMIT 10");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function register($email, $password, $role, $name, $surname): User
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

            $stmt = $db->prepare("INSERT INTO users VALUES
                         (NULL, :role, false, :name, :surname, :email, :hash, :salt)");
            $stmt->execute([
                ':role' => $role,
                ':name' => $name,
                ':surname' => $surname,
                ':email' => $email,
                ':hash' => $hash,
                ':salt' => $salt
            ]);

            return new User($db->lastInsertId());
        } catch (\PDOException $e) {
            throw $e;
        }
    }

    public function accept($user_id): bool
    {
        try {
            $db = $this->getPdo();
            $user = $db->prepare("UPDATE users SET verified = true WHERE id = ?");
            $user->execute([$user_id]);
        } catch (\PDOException $e) {
            return false;
        }

        return true;
    }
}
