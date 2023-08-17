<?php

namespace Zenstruck\RedirectBundle\Tests\Functional;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Foundry\Test\ResetDatabase;
use Zenstruck\RedirectBundle\Model\NotFound;
use Zenstruck\RedirectBundle\Model\Redirect;
use Zenstruck\RedirectBundle\Tests\Fixture\Entity\DummyNotFound;
use Zenstruck\RedirectBundle\Tests\Fixture\Entity\DummyRedirect;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
abstract class FunctionalTest extends WebTestCase
{
    use ResetDatabase;

    protected function em(): EntityManagerInterface
    {
        return self::getContainer()->get(EntityManagerInterface::class);
    }

    protected function getRedirect(string $source): ?Redirect
    {
        if (null === $redirect = $this->em()->getRepository(DummyRedirect::class)->findOneBy(['source' => $source])) {
            return null;
        }

        $this->em()->refresh($redirect);

        return $redirect;
    }

    /**
     * @return NotFound[]
     */
    protected function getNotFounds(): array
    {
        return $this->em()->getRepository(DummyNotFound::class)->findAll();
    }

    protected function addTestData(): void
    {
        $this->em()->createQuery('DELETE '.DummyRedirect::class)
            ->execute()
        ;

        $this->em()->createQuery('DELETE '.DummyNotFound::class)
            ->execute()
        ;

        $this->em()->persist(new DummyRedirect('/301-redirect', 'http://symfony.com'));
        $this->em()->persist(new DummyRedirect('/302-redirect', 'http://example.com', false));

        $this->em()->flush();
    }
}
