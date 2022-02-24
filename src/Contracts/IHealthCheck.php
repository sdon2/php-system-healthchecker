<?php

namespace HealthChecker\Contracts;

interface IHealthCheck
{
    public function getHealthCheckName();

    public function getActualValue();

    public function getThresholdValue();

    public function getComparer();

    public function getReport();
}
