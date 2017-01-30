<?php

namespace PowderBlue\SfContinentCountryTypeBundle\Provider;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Intl\Data\Bundle\Reader\BundleEntryReader;
use Symfony\Component\Intl\Data\Bundle\Reader\JsonBundleReader;
use Symfony\Component\Intl\Intl;
use Symfony\Component\Intl\ResourceBundle\RegionBundleInterface;
use Symfony\Component\Intl\Locale\Locale;

class ContinentCountryCSVFileProvider implements ContinentCountryProviderInterface
{
    /** @var RequestStack */
    protected $requestStack;

    /** @var string */
    protected $file;

    /** @var RegionBundleInterface */
    protected $regionBundle;

    /**
     * @param RequestStack $requestStack
     * @param string       $file
     */
    public function __construct(RequestStack $requestStack, $file)
    {
        $this->requestStack = $requestStack;
        $this->file = $file;
        $this->regionBundle = Intl::getRegionBundle();
    }

    /**
     * @return array
     */
    public function getCountries()
    {
        $countries = [];

        foreach ($this->getRows() as $row) {
            $countries[$row[0]] = $this->regionBundle->getCountryName($row[0]);
        }

        return $countries;
    }

    /**
     * @return array
     */
    public function getContinents()
    {
        $reader = new BundleEntryReader(new JsonBundleReader());
        $locale = $this->requestStack->getCurrentRequest()->getLocale();
        $continents = [];

        foreach ($this->getRows() as $row) {
            $continent = $reader->readEntry(
                sprintf('%s/../Resources/translations/continents', __DIR__),
                $locale,
                ['Names', $row[1]]
            );
            if (!array_key_exists($continent, $continents)) {
                $continents[$continent] = [];
            }

            $continents[$continent][$row[0]] = $this->regionBundle->getCountryName($row[0]);
        }

        return $continents;
    }

    /**
     * @return array
     */
    private function getRows()
    {
        $rows = [];

        if (($handle = fopen($this->file, 'r')) !== false) {
            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                $rows[] = $row;
            }
            fclose($handle);
        }

        return $rows;
    }
}
