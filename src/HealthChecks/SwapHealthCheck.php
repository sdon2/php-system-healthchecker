<?php

namespace HealthChecker\HealthChecks;

use HealthChecker\Contracts\IHealthCheck;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class SwapHealthCheck implements IHealthCheck
{
    public function getTotalValue()
    {
        //
    }

    public function getHealthCheckName()
    {
        //
    }

    public function getActualValue()
    {
        //
    }

    public function getThresholdValue()
    {
        //
    }

    public function getReport()
    {
        //
    }
}
