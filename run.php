#!/usr/bin/env php

<?php

require __DIR__ . "/vendor/autoload.php";

use Symfony\Component\Dotenv\Dotenv;
use HealthChecker\HealthChecks\PhysicalMemoryHealthCheck;
use HealthChecker\Contracts\IHealthCheck;
use HealthChecker\HealthChecks\SwapHealthCheck;
use HealthChecker\Mailer;

try
{
    $dotenv = new Dotenv();
    $dotenv->load(__DIR__.'/.env');

    $checks = [
        PhysicalMemoryHealthCheck::class,
        SwapHealthCheck::class,
    ];

    $test_passed = true;
    $report = "";

    foreach ($checks as $check) {
        /**
        * @var IHealthCheck $class
        */
        $class = new $check();

        if ($class->isThresholdFailed()) {
            $report .= $class->getHealthCheckName() . " exceeded ThresHold\n";
            $report .= $class->getReport();

            $test_passed = false;
        }
    }

    if ($test_passed) {
        echo "\nAll tests passed!!!\n";
        exit(0);
    } else {
        if ($_ENV['ENABLE_EMAIL'] != 'false') {
            echo "\nSome of the Tests failed and Email Triggered!!!\n";
            Mailer::sendReport($report);
        } else {
            echo "\nSome of the Tests Failed!!!\n";
            echo $report;
        }
        exit(-1);
    }
}
catch (\Exception $ex) {
    echo "\n" . $ex->getMessage() . "\n";
    exit(-1);
}

