<?php

namespace HealthChecker\Contracts;

interface IHealthCheck
{
    public function getHealthCheckName();

    public function getTotalValue();

    public function getActualValue();

    public function getThresholdValue();

    public function getReport();
}
