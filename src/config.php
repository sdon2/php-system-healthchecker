<?php

use HealthChecker\HealthChecks\PhysicalMemoryHealthCheck;
use HealthChecker\HealthChecks\SwapHealthCheck;

return [
    'checks' => [
        PhysicalMemoryHealthCheck::class,
        SwapHealthCheck::class,
    ],
];
