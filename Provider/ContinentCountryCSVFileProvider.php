<?php

namespace PowderBlue\SfContinentCountryTypeBundle\Provider;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Intl\Data\Bundle\Reader\BundleEntryReader;
use Symfony\Component\Intl\Data\Bundle\Reader\JsonBundleReader;
use Symfony\Component\Intl\Intl;

class ContinentCountryCSVFileProvider implements ContinentCountryProviderInterface
{
    /** @var RequestStack */
    protected $requestStack;

    /** @var string */
    protected $filename;

    /** @var string */
    protected $locale;

    /** @var array */
    protected $continentCountries;

    /**
     * @param RequestStack $requestStack
     * @param string       $filename
     */
    public function __construct(RequestStack $requestStack, $filename)
    {
        $this->requestStack = $requestStack;
        $this->filename = $filename;

        $this->locale = $this->requestStack->getCurrentRequest()->getLocale();
        $this->continentCountries = null;
    }

    /**
     * @param array $countries
     * @return array
     */
    private function sortCountries($countries)
    {
        asort($countries);
        return $countries;
    }

    /**
     * @return array
     * @throws \RuntimeException If it failed to open the continent-countries file.
     */
    private function getContinentCountries()
    {
        if (null === $this->continentCountries) {
            $handle = fopen($this->filename, 'r');

            if (false === $handle) {
                throw new \RuntimeException("Failed to open '{$this->filename}'.");
            }

            $this->continentCountries = [];

            while (false !== ($record = fgetcsv($handle, 1000))) {
                $continentCode = $record[1];

                if (!array_key_exists($continentCode, $this->continentCountries)) {
                    $this->continentCountries[$continentCode] = [];
                }

                $countryCode = $record[0];

                $this->continentCountries[$continentCode][$countryCode] = Intl::getRegionBundle()
                    ->getCountryName($countryCode)
                ;
            }

            fclose($handle);
        }

        return $this->continentCountries;
    }

    /**
     * @return array
     */
    public function getCountries()
    {
        $countries = [];

        foreach ($this->getContinentCountries() as $continentCountries) {
            $countries = array_merge($countries, $continentCountries);
        }

        return $this->sortCountries($countries);
    }

    /**
     * @return array
     */
    public function getContinents()
    {
        $bundleEntryReader = new BundleEntryReader(new JsonBundleReader());
        $continents = [];

        foreach ($this->getContinentCountries() as $continentCode => $continentCountries) {

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
