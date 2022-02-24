<?php

namespace HealthChecker\HealthChecks;

use HealthChecker\Contracts\IHealthCheck;

class SwapHealthCheck extends HealthCheck implements IHealthCheck
{

    public function getHealthCheckName()
    {
        return "Swap Health Check";
    }

    public function getActualValue()
    {
        //
    }

    public function getThresholdValue()
    {
        //
    }

    public function getComparer()
    {
        //
    }

    public function getReport()
    {
        //
    }
}
