<?php

require __DIR__ . '/../vendor/autoload.php';

use App\RsrErrorHandler;
use App\SimpleArrayCache;
use App\HttpClient;
use App\Locator\Ip;
use App\Locator\IpGeoLocationLocator;
use App\Locator\IpInfoLocator;
use App\Locator\MuteLocator;
use App\Logger;


$apiKey = "12345";
$ip = new Ip("8.8.8.8");
$handler = new RsrErrorHandler(new Logger());
$client = new HttpClient();
$cache = new SimpleArrayCache();


# ------------ Example 1 ------------ #
$locator = new \App\Locator\ChainLocator(
    new MuteLocator(
        new IpGeoLocationLocator($client, $apiKey),
        $handler
    ),
    new MuteLocator(
        new IpInfoLocator($client, $apiKey),
        $handler
    )
);
/** @psalm-suppress ForbiddenCode */
var_dump($locator->locate($ip));


# ------------ Example 2 ------------ #
$locator = new \App\Locator\CacheLocator(
    new \App\Locator\ChainLocator(
        new MuteLocator(
            new IpGeoLocationLocator($client, $apiKey),
            $handler
        ),
        new MuteLocator(
            new IpInfoLocator($client, $apiKey),
            $handler
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
            new IpGeoLocationLocator($client, $apiKey),
            $handler
        ),
        $cache,
        "prefix_1",
        3600
    ),
    new \App\Locator\CacheLocator(
        new MuteLocator(
            new IpInfoLocator($client, $apiKey),
            $handler
        ),
        $cache,
        "prefix_2",
        3600
    )
);
/** @psalm-suppress ForbiddenCode */
var_dump($locator->locate($ip));
