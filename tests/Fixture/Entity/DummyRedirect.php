<?php

namespace Zenstruck\RedirectBundle\Tests\Fixture\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zenstruck\RedirectBundle\Model\Redirect;

#[ORM\Entity]
class DummyRedirect extends Redirect
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    public ?int $id = null;
}
