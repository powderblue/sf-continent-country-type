<?php

namespace PowderBlue\SfContinentCountryTypeBundle\DependencyInjection;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\HttpKernel\Kernel;

class Configuration implements ConfigurationInterface
{
    private const ROOT_NAME = 'powder_blue_sf_continent_country_type';

    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder(self::ROOT_NAME);

        $rootNode = Kernel::VERSION > 4.2
            ? $treeBuilder->getRootNode()
            : $treeBuilder->root(self::ROOT_NAME)
        ;

        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('file')
                    ->defaultValue(sprintf('%s/../Resources/data/continent_country.csv', __DIR__))
                    ->example('MyBundle\\Resources\\data\\continent_country.json')
                ->end()
                ->booleanNode('group_by_continent')
                    ->defaultValue(true)
                ->end()
                ->scalarNode('provider')
                    ->defaultValue('powder_blue_sf_continent_country_type.provider.continent_country_csv_file')
                    ->example('my_bundle.continent_country_json_file_provider')
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
