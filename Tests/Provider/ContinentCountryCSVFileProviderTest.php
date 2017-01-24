<?php

namespace PowderBlue\SfContinentCountryTypeBundle\Tests\Provider;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Intl\ResourceBundle\RegionBundle;
use Symfony\Component\Intl\Data\Bundle\Reader\BundleEntryReader;
use PowderBlue\SfContinentCountryTypeBundle\Provider\ContinentCountryCSVFileProvider;

class ContinentCountryCSVFileProviderTest extends KernelTestCase
{
    /** @var ContinentCountryCSVFileProvider */
    protected $provider;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $requestStack;

    /** @var string */
    protected $file;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $regionBundle;

    public function setUp()
    {
        $this->requestStack = $this
            ->getMockBuilder(RequestStack::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $this->file = sprintf('%s/../Resources/data/continent_country.csv', __DIR__);

        $this->regionBundle = $this
            ->getMockBuilder(RegionBundle::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $this->provider = new ContinentCountryCSVFileProvider(
            $this->requestStack,
            $this->file
        );
    }

    /**
     * @dataProvider getDataForTestGetCountries
     *
     * @param array $rows
     * @param array $countries
     */
    public function testGetCountries(array $rows, array $countries)
    {
        for ($i = 0; $i < count($rows); $i++) {
            $this
                ->regionBundle
                ->method('getCountryName')
                ->with($rows[$i])
                ->willReturn($countries[$i])
            ;
        }

        $result = $this->provider->getCountries();

        $this->assertInternalType('array', $result);
        $this->assertSame(array_values($countries), array_values($result));
    }

    /**
     * @return array
     */
    public function getDataForTestGetCountries()
    {
        return [
            [
                ["RO", "GB", "US",],
                ["Romania", "United Kingdom", "United States",],
            ],
        ];
    }

    /**
     * @dataProvider getDataForTestGetContinents
     *
     * @param array  $rows
     * @param array  $continents
     * @param array  $countries
     * @param string $locale
     */
    public function testGetContinents(
        array $rows,
        array $continents,
        array $countries,
        $locale
    ) {
        $reader = $this
            ->getMockBuilder(BundleEntryReader::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $request = $this
            ->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $this
            ->requestStack
            ->expects($this->once())
            ->method('getCurrentRequest')
            ->willReturn($request)
        ;
        $request
            ->expects($this->once())
            ->method('getLocale')
            ->willReturn($locale)
        ;

        for ($i = 0; $i < count($rows); $i++) {
            $reader
                ->method('readEntry')
                ->withAnyParameters()
                ->willReturn($continents[$i])
            ;
            $this
                ->regionBundle
                ->method('getCountryName')
                ->willReturn($countries[$i])
            ;
        }

        $result = $this->provider->getContinents();

        $this->assertInternalType('array', $result);
        $this->assertSame(array_values(array_unique($continents)), array_keys($result));
    }

    public function getDataForTestGetContinents()
    {
        return [
            [
                [["RO", "EU",], ["GB", "EU",], ["US", "NA",],],
                ["Europe", "Europe", "North America",],
                ["Romania", "United Kingdom", "United States",],
                'en',
            ],
        ];
    }
}
