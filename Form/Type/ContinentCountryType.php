<?php

namespace PowderBlue\SfContinentCountryTypeBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use PowderBlue\SfContinentCountryTypeBundle\Provider\ContinentCountryProviderInterface;

class ContinentCountryType extends AbstractType
{
    /** @var ContinentCountryProviderInterface */
    protected $provider;

    /** @var bool */
    protected $groupByContinent;
    
    /**
     * @param ContinentCountryProviderInterface $provider
     * @param bool                              $groupByContinent
     */
    public function __construct(
        ContinentCountryProviderInterface $provider,
        $groupByContinent
    ) {
        $this->provider = $provider;
        $this->groupByContinent = $groupByContinent;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'choices' => $this->groupByContinent
                ? $this->provider->getContinents()
                : $this->provider->getCountries(),
            'choices_as_values' => false,
        ]);
    }
    
    /**
     * @return string
     */
    public function getParent()
    {
        return CountryType::class;
    }
}
