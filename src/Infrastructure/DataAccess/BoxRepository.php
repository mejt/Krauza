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

    public function add(Box $box, User $user)
    {
        $this->engine->insert(self::TABLE_NAME, [
            'id' => $box->getId(),
            'name' => $box->getName(),
            'section' => $box->getCurrentSection(),
            'user_id' => $user->getId()
        ]);
    }

    public function updateBoxSection(Box $box)
    {
        $this->engine->update(self::TABLE_NAME, ['section' => $box->getCurrentSection()], ['id' => $box->getId()]);
    }

    public function getById(string $id): Box
    {
        $stmt = $this->engine->prepare("SELECT * FROM " . self::TABLE_NAME . " WHERE id = :id");
        $stmt->bindValue('id', $id);
        $stmt->execute();
        $result = $stmt->fetch();

        $boxName = new BoxName($result['name']);
        $id = new EntityId($result['id']);
        $box = new Box($boxName, $result['section']);
        $box->setId($id);

        return $box;
    }
}
