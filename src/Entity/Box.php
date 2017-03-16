<?php

namespace Krauza\Entity;

use Krauza\Repository\BoxRepository;
use Krauza\ValueObject\CardWord;

class Box
{
    private $boxRepository;
    private $user;

    public function __construct(BoxRepository $boxRepository, User $user)
    {
        $this->boxRepository = $boxRepository;
        $this->user = $user;
    }

    public function getNextCard(): Card
    {
        return new Card(new CardWord('aaa'), new CardWord('bbb'));
    }
}