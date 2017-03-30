<?php

namespace PowderBlue\SfContinentCountryTypeBundle\Tests\Provider;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Request;
use PowderBlue\SfContinentCountryTypeBundle\Provider\ContinentCountryCSVFileProvider;

class ContinentCountryCSVFileProviderTest extends KernelTestCase
{
    private static function createFixtureFilename($basename)
    {
        return __DIR__ . "/ContinentCountryCSVFileProviderTest/{$basename}";
    }

    private function createRequestStackStub($locale)
    {
        $request = $this
            ->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $request
            ->method('getLocale')
            ->willReturn($locale)
        ;

        $requestStack = $this
            ->getMockBuilder(RequestStack::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $requestStack
            ->method('getCurrentRequest')
            ->willReturn($request)
        ;

        return $requestStack;
    }

    public static function providesCountries()
    {
        return [[
            [
                'FR' => 'France',
                'RO' => 'Romania',
                'ES' => 'Spain',
                'GB' => 'United Kingdom',
                'US' => 'United States',
            ],
            'en',
            self::createFixtureFilename('continent_countries_1.csv'),
        ]];
    }

    /**
     * @dataProvider providesCountries
     */
    public function testGetcountriesReturnsAnOrderedListOfCountries($expectedCountries, $locale, $filename)
    {
        $actualCountries = (new ContinentCountryCSVFileProvider($this->createRequestStackStub($locale), $filename))
            ->getCountries()
        ;

        $this->assertSame($expectedCountries, $actualCountries);
    }

    public static function providesContinentCountries()
    {
        return [[
            [
                'Europe' => [
                    'FR' => 'France',
                    'RO' => 'Romania',
                    'ES' => 'Spain',
                    'GB' => 'United Kingdom',
                ],
                'North America' => [
                    'US' => 'United States',
                ],
            ],
            'en',
            self::createFixtureFilename('continent_countries_1.csv'),
        ]];
    }

    /**
     * @dataProvider providesContinentCountries
     */
    public function testGetcontinentsReturnsAnOrderedListOfContinentCountries($expectedContinents, $locale, $filename)
    {
        $actualContinents = (new ContinentCountryCSVFileProvider($this->createRequestStackStub($locale), $filename))
            ->getContinents()
        ;

        $this->assertSame($expectedContinents, $actualContinents);
    }
}
