<?php

namespace Zenstruck\RedirectBundle\Tests\Fixture\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zenstruck\RedirectBundle\Model\NotFound;

#[ORM\Entity]
class DummyNotFound extends NotFound
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    public ?int $id = null;
}
