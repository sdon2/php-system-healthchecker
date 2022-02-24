#!/usr/bin/env php

<?php

require __DIR__ . "/vendor/autoload.php";

use Symfony\Component\Dotenv\Dotenv;
use HealthChecker\HealthChecks\PhysicalMemoryHealthCheck;
use HealthChecker\Contracts\IHealthCheck;

$dotenv = new Dotenv();
$dotenv->load(__DIR__.'/.env');

$checks = [
    PhysicalMemoryHealthCheck::class
];

foreach ($checks as $check) {
    /**
     * @var IHealthCheck $class
     */
    $class = new $check();
    $class->getReport();
}
