<?php


namespace App\Model;

use PDO;
use PDOException;

class Problems extends AbstractModel
{
    public function fetch(int $limit, int $offset): array
    {
        try {
            $db = $this->getPdo();
            $prep = $db->prepare("SELECT * FROM problems ORDER BY id DESC LIMIT :limit OFFSET :offset");
            $prep->bindParam("limit", $limit, PDO::PARAM_INT);
            $prep->bindParam("offset", $offset, PDO::PARAM_INT);
            $prep->execute();
            return $prep->fetchAll();
        } catch (PDOException $e) {
            return array('error' => $e->getMessage());
        }
    }

    public function rowsCount(): int
    {
        try {
            $db = $this->getPdo();
            $prep = $db->prepare("SELECT COUNT(*) FROM problems");
            $prep->execute();
            return $prep->fetchColumn();
        } catch (PDOException $e) {
            return 0;
        }
    }

    public function ratedCount(): int
    {
        try {
            $db = $this->getPdo();
            $prep = $db->prepare("SELECT COUNT(*) FROM problems WHERE mark IS NOT NULL");
            $prep->execute();
            return $prep->fetchColumn();
        } catch (PDOException $e) {
            return 0;
        }
    }

    public function getActiveWriters(): array
    {
        try {
            $db = $this->getPdo();
            $prep = $db->prepare("SELECT COUNT(*) as `count`, users.email FROM `problems` INNER JOIN users
    ON (users.id = problems.writer_id) GROUP BY writer_id ORDER BY `count` DESC LIMIT 10");
            $prep->execute();
            return $prep->fetchAll();
        } catch (PDOException $e) {
            return array('0' => ['email'=>'error']);
        }
    }

    public function getActiveEvaluators(): array
    {
        try {
            $db = $this->getPdo();
            $prep = $db->prepare("SELECT COUNT(*) as `count`, users.email FROM `problems` INNER JOIN users 
    ON (users.id = problems.evaluator_id) GROUP BY evaluator_id ORDER BY `count` DESC LIMIT 10");
            $prep->execute();
            return $prep->fetchAll();
        } catch (PDOException $e) {
            return array('0' => ['email'=>'error']);
        }
    }

    public function addEntity(int $writer_id, string $problem, string $solution): bool
    {
        try {
            $db = $this->getPdo();
            $prep = $db->prepare('INSERT INTO problems VALUES (NULL, ?, NULL, NOW(), ?, ?, NULL)');
            $prep->execute([$writer_id, $problem, $solution]);
        } catch (PDOException $e) {
            return false;
        }
        return true;
    }

    public function deleteEntity(int $entity_id): bool
    {
        try {
            $db = $this->getPdo();
            $prep = $db->prepare('DELETE FROM problems WHERE id = ?');
            $prep->execute([$entity_id]);
        } catch (PDOException $e) {
            return false;
        }
        return true;
    }

    public function setMark(int $entity_id, $evaluator_id, $mark): bool
    {
        try {
            $db = $this->getPdo();
            $prep = $db->prepare('UPDATE problems SET mark = ?, evaluator_id = ?, date=NOW() WHERE id = ?');
            $prep->execute([$mark, $evaluator_id, $entity_id]);
        } catch (PDOException $e) {
            return false;
        }
        return true;
    }

    public function deleteMark(int $entity_id): bool
    {
        return $this->setMark($entity_id, null, null);
    }
}
