<?php


namespace App\Model;

use PDO;
use PDOException;

class Entities extends AbstractModel
{
    public function fetch(int $limit, int $offset): array
    {
        try {
            $db = $this->getPdo();
            $prep = $db->prepare("SELECT * FROM problems LIMIT :limit OFFSET :offset");
            $prep->bindParam("limit", $limit, PDO::PARAM_INT);
            $prep->bindParam("offset", $offset, PDO::PARAM_INT);
            $prep->execute();
            return $prep->fetchAll();
        } catch (PDOException $e) {
            return array('error' => $e->getMessage());
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

    public function setMark(int $entity_id, int $evaluator_id, int $mark): bool
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

}
