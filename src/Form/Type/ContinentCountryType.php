<?php

declare(strict_types=1);

namespace PowderBlue\SfContinentCountryTypeBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use PowderBlue\SfContinentCountryTypeBundle\Provider\ContinentCountryProviderInterface;

use function array_flip;

use const true;

class ContinentCountryType extends AbstractType
{
    public function __construct(
        protected ContinentCountryProviderInterface $provider,
        protected bool $groupByContinent
    ) {
    }

    /**
     * @return void
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
