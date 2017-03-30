<?php

namespace PowderBlue\SfContinentCountryTypeBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
    public function __construct(ContinentCountryProviderInterface $provider, $groupByContinent)
    {
        $this->provider = $provider;
        $this->groupByContinent = $groupByContinent;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $choices = [];

        if ($this->groupByContinent) {
            foreach ($this->provider->getContinents() as $name => $countries) {
                $choices[$name] = array_flip($countries);
            }
        } else {
            $choices = array_flip($this->provider->getCountries());
        }

        $resolver->setDefaults([
            'choices' => $choices,
            'choices_as_values' => true,
        ]);
    }

    /**
     * @return string
     */
    public function getParent()
    {
        return ChoiceType::class;
    }
}
