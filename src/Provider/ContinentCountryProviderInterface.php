<?php

declare(strict_types=1);

namespace PowderBlue\SfContinentCountryTypeBundle\Provider;

/**
 * @phpstan-type Countries array<string,string>
 * @phpstan-type Continents array<string,array<string,string>>
 */
interface ContinentCountryProviderInterface
{
    /**
     * @phpstan-return Countries
     */
    public function getCountries(): array;

    /**
     * @phpstan-return Continents
     */
    public function getContinents(): array;
}
