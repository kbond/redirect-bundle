<?php

namespace Zenstruck\RedirectBundle;

use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class ZenstruckRedirectBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(DoctrineOrmMappingsPass::createXmlMappingDriver(
            [__DIR__.'/../config/doctrine/mapping' => 'Zenstruck\RedirectBundle\Model'],
            enableXsdValidation: true,
        ));
    }

    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
