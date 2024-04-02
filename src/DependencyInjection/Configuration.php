<?php

namespace PowderBlue\SfContinentCountryTypeBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    private const ROOT_NAME = 'powder_blue_sf_continent_country_type';

    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder(self::ROOT_NAME);

        /** @var ArrayNodeDefinition */
        $rootNode = $treeBuilder->getRootNode();
        $childNodes = $rootNode->addDefaultsIfNotSet()->children();

        $childNodes
            ->scalarNode('file')
                ->defaultValue(sprintf('%s/../Resources/data/continent_country.csv', __DIR__))
                ->example('MyBundle\\Resources\\data\\continent_country.json')
            ->end()
        ;

        $childNodes
            ->booleanNode('group_by_continent')
                ->defaultValue(true)
            ->end()
        ;

        $childNodes
            ->scalarNode('provider')
                ->defaultValue(self::ROOT_NAME . '.provider.continent_country_csv_file')
                ->example('my_bundle.continent_country_json_file_provider')
            ->end()
        ;

        $childNodes->end();

        return $treeBuilder;
    }
}
