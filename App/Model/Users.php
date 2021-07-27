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
