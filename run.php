#!/usr/bin/env php

<?php

require __DIR__ . "/vendor/autoload.php";

use Symfony\Component\Dotenv\Dotenv;
use HealthChecker\Contracts\IHealthCheck;
use HealthChecker\Tools\Mailer;

try {
    $dotenv = new Dotenv();
    $dotenv->load(__DIR__ . '/.env');

    $config = require(__DIR__ . "/src/config.php");

    $checks = $config['checks'];

    $test_passed = true;
    $reports = [];

    foreach ($checks as $check) {
        /**
         * @var IHealthCheck $class
         */
        $class = new $check();

        if (!$class->isCheckPassed()) {
            array_push($reports, ['html' => $class->isHtmlReport(), 'report' => $class->getReport()]);
            $test_passed = false;
        }
    }

    if ($test_passed) {
        echo "All tests passed!!!\n";
        exit(0);
    } else {
        if ($_ENV['ENABLE_EMAIL'] == 'true') {
            Mailer::sendReport($reports);
            throw new Exception('Some of the Tests failed and Email Triggered!!!');
        } else {
            $output = "";

            foreach ($reports as $report) {
                $output .= $report['report'];
            }
            throw new Exception("Some of the Tests Failed!!!\n" . $output);
        }
    }
} catch (\Exception $ex) {
    echo "\n" . $ex->getMessage() . "\n";
    exit(-1);
}
