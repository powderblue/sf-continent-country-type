<?php

declare(strict_types=1);

namespace PowderBlue\SfContinentCountryTypeBundle\Tests\Provider;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\Stub;
use PowderBlue\SfContinentCountryTypeBundle\Provider\ContinentCountryCSVFileProvider;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @phpstan-import-type Countries from \PowderBlue\SfContinentCountryTypeBundle\Provider\ContinentCountryProviderInterface
 * @phpstan-import-type Continents from \PowderBlue\SfContinentCountryTypeBundle\Provider\ContinentCountryProviderInterface
 */
class ContinentCountryCSVFileProviderTest extends KernelTestCase
{
    private static function createFixtureFilename(string $basename): string
    {
        return __DIR__ . "/ContinentCountryCSVFileProviderTest/{$basename}";
    }

    private function createMockRequestStack(string $locale): Stub
    {
        $mockRequest = $this->createStub(Request::class);

        $mockRequest
            ->method('getLocale')
            ->willReturn($locale)
        ;

        $mockRequestStack = $this->createStub(RequestStack::class);

        $mockRequestStack
            ->method('getCurrentRequest')
            ->willReturn($mockRequest)
        ;

        return $mockRequestStack;
    }

    /** @return array<mixed[]> */
    public static function providesCountries(): array
    {
        return [
            [
                [
                    'FR' => 'France',
                    'RO' => 'Romania',
                    'ES' => 'Spain',
                    'GB' => 'United Kingdom',
                    'US' => 'United States',
                ],
                'en',
                self::createFixtureFilename('continent_countries_1.csv'),
            ],
        ];
    }

    /**
     * @phpstan-param Countries $expectedCountries
     */
    #[DataProvider('providesCountries')]
    public function testGetcountriesReturnsAnOrderedListOfCountries(
        array $expectedCountries,
        string $locale,
        string $filename
    ): void {
        /** @var RequestStack */
        $mockRequestStack = $this->createMockRequestStack($locale);

        $actualCountries = (new ContinentCountryCSVFileProvider($mockRequestStack, $filename))
            ->getCountries()
        ;

        $this->assertSame($expectedCountries, $actualCountries);
    }

    /** @return array<mixed[]> */
    public static function providesContinentCountries(): array
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
     * @phpstan-param Continents $expectedContinents
     */
    #[DataProvider('providesContinentCountries')]
    public function testGetcontinentsReturnsAnOrderedListOfContinentCountries(
        array $expectedContinents,
        string $locale,
        string $filename
    ): void {
        /** @var RequestStack */
        $mockRequestStack = $this->createMockRequestStack($locale);

        $actualContinents = (new ContinentCountryCSVFileProvider($mockRequestStack, $filename))
            ->getContinents()
        ;

        $this->assertSame($expectedContinents, $actualContinents);
    }
}
