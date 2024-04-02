<?php

namespace PowderBlue\SfContinentCountryTypeBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

class PowderBlueSfContinentCountryTypeExtension extends ConfigurableExtension
{
    /**
     * @param mixed[] $configs
     * @return void
     */
    public function loadInternal(
        array $configs,
        ContainerBuilder $container
    ) {
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(sprintf('%s/../Resources/config', __DIR__))
        );

        $loader->load('services.yml');

        $rootNode = 'powder_blue_sf_continent_country_type';

        $provider = $container->getDefinition("{$rootNode}.continent_country_csv_file_provider");

        $container
            ->getDefinition("{$rootNode}.form.type.continent_country")
            ->addArgument($provider)
            ->addArgument($configs['group_by_continent'])
        ;

        $provider->addArgument($configs['file']);
    }
}
