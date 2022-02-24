<?php

namespace HealthChecker\HealthChecks;

use HealthChecker\Contracts\IHealthCheck;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class PhysicalMemoryHealthCheck implements IHealthCheck
{
    public function getHealthCheckName()
    {
        return "Physical Memory";
    }

    public function getActualValue()
    {
        $process = Process::fromShellCommandline("free -m | head -2 | tail -1| awk '{print $2}'");
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        return intval($process->getOutput());
    }

    public function getThresholdValue()
    {
        return intval($_ENV['PHYSICAL_MEMORY_THRESHOLD']);
    }

    public function getReport() { }
}
