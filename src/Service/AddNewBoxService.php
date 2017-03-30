<?php

namespace Krauza\Service;

use Krauza\Entity\User;
use Krauza\Factory\BoxFactory;
use Krauza\Policy\IdPolicy;
use Krauza\Repository\BoxRepository;

class AddNewBoxService
{
    private $boxRepository;
    private $idPolicy;

    public function __construct(BoxRepository $boxRepository, IdPolicy $idPolicy)
    {
        $this->boxRepository = $boxRepository;
        $this->idPolicy = $idPolicy;
    }

    public function addNewBox(array $data, User $user)
    {
        $card = BoxFactory::createBox($data, $this->idPolicy);
        $this->boxRepository->add($card, $user);
    }
}
