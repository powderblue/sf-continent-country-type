<?php

declare(strict_types=1);

namespace PowderBlue\SfContinentCountryTypeBundle\Provider;

use RuntimeException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Intl\Countries;
use Symfony\Component\Intl\Data\Bundle\Reader\BundleEntryReader;
use Symfony\Component\Intl\Data\Bundle\Reader\JsonBundleReader;

use function array_key_exists;
use function array_merge;
use function asort;
use function fclose;
use function fgetcsv;
use function fopen;
use function ksort;

use const false;

/**
 * @phpstan-import-type Countries from \PowderBlue\SfContinentCountryTypeBundle\Provider\ContinentCountryProviderInterface as CountriesArray
 * @phpstan-import-type Continents from \PowderBlue\SfContinentCountryTypeBundle\Provider\ContinentCountryProviderInterface as ContinentsArray
 */
class ContinentCountryCSVFileProvider implements ContinentCountryProviderInterface
{
    protected string $locale;

    /**
     * Lazy-loaded
     *
     * @phpstan-var ContinentsArray
     */
    protected array $continentCountries;

    public function __construct(
        protected RequestStack $requestStack,
        protected string $filename
    ) {
        /** @var Request */
        $currentRequest = $this->requestStack->getCurrentRequest();
        $this->locale = $currentRequest->getLocale();
    }

    /**
     * @phpstan-param CountriesArray $countries
     * @phpstan-return CountriesArray
     */
    private function sortCountries(array $countries): array
    {
        asort($countries);

        return $countries;
    }

    /**
     * @phpstan-return ContinentsArray
     * @throws RuntimeException If it failed to open the continent-countries file.
     */
    private function getContinentCountries()
    {
        if (!isset($this->continentCountries)) {
            $handle = fopen($this->filename, 'r');

            if (false === $handle) {
                throw new RuntimeException("Failed to open '{$this->filename}'.");
            }

            $this->continentCountries = [];

            while (false !== ($record = fgetcsv($handle, 1000))) {
                /** @var string */
                $continentCode = $record[1];

                if (!array_key_exists($continentCode, $this->continentCountries)) {
                    $this->continentCountries[$continentCode] = [];
                }

                /** @var string */
                $countryCode = $record[0];

                $this->continentCountries[$continentCode][$countryCode] = Countries::getName($countryCode);
            }

            fclose($handle);
        }

        return $this->continentCountries;
    }

    /**
     * @phpstan-return CountriesArray
     */
    public function getCountries(): array
    {
        $countries = [];

        foreach ($this->getContinentCountries() as $continentCountries) {
            $countries = array_merge($countries, $continentCountries);
        }

        return $this->sortCountries($countries);
    }

    /**
     * @phpstan-return ContinentsArray
     */
    public function getContinents(): array
    {
        $bundleEntryReader = new BundleEntryReader(new JsonBundleReader());
        $continents = [];

        foreach ($this->getContinentCountries() as $continentCode => $continentCountries) {
            /** @var string */
            $continentName = $bundleEntryReader->readEntry(
                __DIR__ . '/../Resources/translations/continents',
                $this->locale,
                ['Names', $continentCode]
            );

            $continents[$continentName] = $this->sortCountries($continentCountries);
        }

        ksort($continents);

        return $continents;
    }
}
