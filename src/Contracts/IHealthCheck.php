<?php

namespace HealthChecker\Contracts;

interface IHealthCheck
{
    public function getServerName();

    public function getHealthCheckName();

    public function getActualValue();

    public function getThresholdValue();

    public function isCheckPassed();

    public function getReport();

    public function isHtmlReport();
}
