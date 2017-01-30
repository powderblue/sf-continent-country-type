<?php

namespace PowderBlue\SfContinentCountryTypeBundle\DependencyInjection;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('powder_blue_sf_continent_country_type');

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
