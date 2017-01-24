<?php

namespace PowderBlue\SfContinentCountryTypeBundle\Provider;

interface ContinentCountryProviderInterface
{
    /**
     * @return array
     */
    public function getCountries();

    /**
     * @return array
     */
    public function getContinents();
}
