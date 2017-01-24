# sf-continent-country-type


## Introduction

This is a Symfony2 bundle which provides a new form type called *ContinentCountryType*. This type extends Symfony's *CountryType* and allows developers to group countries by continents. It also give developers the possibility to define the countries and the continents that need to be used.


## Screenshots

Example of a dropdown with countries grouped by continent:
![dropdown with countries grouped by continent](Resources/doc/img/grouped.png)

Example of a simple country dropdown:
![simple country dropdown](Resources/doc/img/not-grouped.png)

*Note that Select2 library is applied on the select list in the examples above.*

## Installation

* Run `composer require powderblue/sf-continent-country-type`
* Update you project `app/AppKernel.php` file and add the bundle to the `$bundles` array:
```php
$bundles = [
    // ...
    new PowderBlue\SfContinentCountryTypeBundle\PowderBlueSfContinentCountryTypeBundle(),
];
```


## How to use

In the `buildForm` method of a form type class, specify `ContinentCountryType::class` as the type. Don't forget to include the form type class:
```php
use PowderBlue\SfContinentCountryTypeBundle\Form\Type\ContinentCountryType;
```

Here is an example:
```php
$builder
    // ...
    ->add('country', ContinentCountryType::class, [
        'label' => 'Country',
        'attr' => [
            'placeholder' => 'Country',
        ],
    ])
;
```


## Configuration

Below you can find a reference of all configuration options with their default values:
```yml
# config.yml
powder_blue_sf_continent_country_type:
    file: %bundle_root_dir%/Resources/data/continent_country.csv
    group_by_continent: true
    provider: powder_blue_sf_continent_country_type.provider.continent_country_csv_file
```


### Options

- `file` - specifies the path of the file that contains countries (and continents) which should appear in the dropdown
- `group_by_continent` - specifies whether the countries should be grouped by continent in the dropdown
- `provider` - represents the id of the service that is used to parse the countries file; it should implement `PowderBlue\SfContinentCountryTypeBundle\Provider\ContinentCountryProviderInterface` interface
