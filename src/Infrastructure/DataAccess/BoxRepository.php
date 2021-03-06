<?php

namespace Krauza\Infrastructure\DataAccess;

use Krauza\Core\Entity\Box;
use Krauza\Core\Entity\User;
use Krauza\Core\Repository\BoxRepository as IBoxRepository;
use Krauza\Core\ValueObject\BoxName;
use Krauza\Core\ValueObject\EntityId;

final class BoxRepository implements IBoxRepository
{
    private const TABLE_NAME = 'box';

    /**
     * @var \Doctrine\DBAL\Connection
     */
    private $engine;

    public function __construct($engine)
    {
        $this->engine = $engine;
    }

    public function add(Box $box, User $user): void
    {
        $this->engine->insert(self::TABLE_NAME, [
            'id' => $box->getId(),
            'name' => $box->getName(),
            'current_section' => $box->getCurrentSection(),
            'user_id' => $user->getId()
        ]);
    }

    public function updateBoxSection(Box $box): void
    {
        $this->engine->executeUpdate('UPDATE box SET current_section = ? WHERE id = ?', [$box->getCurrentSection(), $box->getId()]);
    }

    public function getById(string $id): Box
    {
        $result = $this->engine->fetchAssoc('SELECT * FROM box WHERE id = ?', [$id]);

        if (empty($result)) {
            throw new \Exception();
        }

        $boxName = new BoxName($result['name']);
        $id = new EntityId($result['id']);
        $box = new Box($boxName, $result['current_section']);
        $box->setId($id);

        return $box;
    }

    public function getAllForUser(User $user): array
    {
        $sql = 'SELECT * FROM box WHERE user_id = :user_id';
        $result = $this->engine->fetchAll($sql, [
            ':user_id' => $user->getId()
        ]);

        return array_map(function ($el) {
            $boxName = new BoxName($el['name']);
            $id = new EntityId($el['id']);
            $box = new Box($boxName, $el['current_section']);
            $box->setId($id);

            return $box;
        }, $result);
    }
}
