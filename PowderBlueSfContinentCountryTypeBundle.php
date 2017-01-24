<?php

namespace PowderBlue\SfContinentCountryTypeBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use PowderBlue\SfContinentCountryTypeBundle\DependencyInjection\Compiler\CompilerPass;

class PowderBlueSfContinentCountryTypeBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new CompilerPass());
    }
}
