# LaPoste DataNOVA-bundle

[![Build Status](https://travis-ci.org/florianajir/datanova-bundle.svg?branch=master)](https://travis-ci.org/florianajir/datanova-bundle) [![Code Coverage](https://scrutinizer-ci.com/g/florianajir/datanova-bundle/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/florianajir/datanova-bundle/?branch=master) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/florianajir/datanova-bundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/florianajir/datanova-bundle/?branch=master)

[![Packagist](https://img.shields.io/packagist/v/florianajir/datanova-bundle.svg?maxAge=2592000)](https://packagist.org/packages/florianajir/datanova-bundle) [![Packagist](https://img.shields.io/packagist/dt/florianajir/datanova-bundle.svg?maxAge=2592000)](https://packagist.org/packages/florianajir/datanova-bundle) [![GitHub license](https://img.shields.io/badge/license-MIT-blue.svg)](https://raw.githubusercontent.com/florianajir/datanova-bundle/master/LICENSE)

LaPoste DataNOVA (open-data API) client and proxy [Symfony](http://symfony.com) bundle.

Fetch and/or search in datasets from LaPoste DataNOVA.

## Installation

### Step 1: Download the Bundle

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```bash
$ composer require florianajir/datanova-bundle "dev-master"
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

### Step 2: Enable the Bundle

Then, enable the bundle by adding it to the list of registered bundles
in the `app/AppKernel.php` file of your project:

```php
<?php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...
            new Fmaj\LaposteDatanovaBundle\FmajLaposteDatanovaBundle(),
        );

        // ...
    }

    // ...
}
```

### Step 3: Using API proxy

To use the bundle proxy API, import the bundle routing file 
in the `app/routing.yml` file of your project:

```yml
data_nova:
    resource: "@FmajLaposteDatanovaBundle/Resources/config/routing.yml"
    prefix:   /datanova
```

### Step 4: Enjoy!

#### Search records

```
/records/search/{dataset}/{query}/{sort}/{rows}/{start} 
```

` Example: http://{localhost}/datanova/records/search/laposte_hexasmal/code_postal:34000/nom_de_la_commune `
` Example: http://{localhost}/datanova/records/search/laposte_hexasmal/paris/code_postal/10/10 `

#### Download records

```
/records/download/{dataset}.{_format}/{query} 
```

` Example: http://{localhost}/datanova/records/download/laposte_hexasmal.json `
` Example: http://{localhost}/datanova/records/download/laposte_hexasmal.csv/974 `


#### Download dataset (server cache)

To improve search performances, use the `data_nova:download:dataset` command to cache dataset for better web search performances. More details in [dataset download command documentation](Resources/dataset/README.md).

```bash
# /bin/bash
php bin/console datanova:download:dataset -h
Usage:
  datanova:download:dataset [options] [--] <dataset> [<format>] [<q>]

Arguments:
  dataset               Which dataset to download?
  format                Data file format : CSV (default), JSON [default: "CSV"]
  q                     query filter, by default all results will be download

Options:
  -f, --force-replace   If set, the command will replace local storage
```

`Example: php app/console datanova:download:dataset laposte_hexasmal json -f`