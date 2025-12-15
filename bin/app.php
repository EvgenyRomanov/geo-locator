<?php

/** @psalm-suppress MissingFile */
require __DIR__ . '/../vendor/autoload.php';

use App\RsrErrorHandler;
use App\SimpleArrayCache;
use App\HttpClient;
use App\Locator\Ip;
use App\Locator\IpGeoLocationLocator;
use App\Locator\IpInfoLocator;
use App\Locator\MuteLocator;
use App\Logger;


$apiKey = "fbba5f1967f04cc2a44b0f24dd341d35";
$ip = new Ip("8.8.8.8");
$customLogger = new Logger();
$errorHandler = new RsrErrorHandler($customLogger);
$customHttpClient =  new \GuzzleHttp\Client();
$cache = new SimpleArrayCache();

# ------------ Example 1 ------------ #
$locator = new \App\Locator\ChainLocator(
    new MuteLocator(
        new IpGeoLocationLocator($customHttpClient, $apiKey),
        $errorHandler
    ),
    new MuteLocator(
        new IpInfoLocator($customHttpClient, $apiKey),
        $errorHandler
    )
);
/** @psalm-suppress ForbiddenCode */
var_dump($locator->locate($ip));


$guzzleHttpClient =  new HttpClient();
$monologLogger = new \Monolog\Logger('test_name');
$monologLogger->pushHandler(new \Monolog\Handler\StreamHandler("php://stdout", \Monolog\Level::Debug));
$errorHandler = new RsrErrorHandler($monologLogger);

# ------------ Example 2 ------------ #
$locator = new \App\Locator\CacheLocator(
    new \App\Locator\ChainLocator(
        new MuteLocator(
            new IpGeoLocationLocator($guzzleHttpClient, $apiKey),
            $errorHandler
        ),
        new MuteLocator(
            new IpInfoLocator($guzzleHttpClient, $apiKey),
            $errorHandler
        )
    ),
    $cache,
    "prefix",
    3600
);
/** @psalm-suppress ForbiddenCode */
var_dump($locator->locate($ip));


# ------------ Example 3 ------------ #
$locator = new \App\Locator\ChainLocator(
    new \App\Locator\CacheLocator(
        new MuteLocator(
            new IpGeoLocationLocator($guzzleHttpClient, $apiKey),
            $errorHandler
        ),
        $cache,
        "prefix_1",
        3600
    ),
    new \App\Locator\CacheLocator(
        new MuteLocator(
            new IpInfoLocator($guzzleHttpClient, $apiKey),
            $errorHandler
        ),
        $cache,
        "prefix_2",
        3600
    )
);
/** @psalm-suppress ForbiddenCode */
var_dump($locator->locate($ip));
