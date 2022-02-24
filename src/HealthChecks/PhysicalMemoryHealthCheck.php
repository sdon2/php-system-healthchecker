<?php

namespace HealthChecker\HealthChecks;

use HealthChecker\Contracts\IHealthCheck;

class PhysicalMemoryHealthCheck extends HealthCheck implements IHealthCheck
{
    public function getHealthCheckName()
    {
        return "Physical Memory";
    }

    // Total Memory
    public function getTotalValue()
    {
        return $this->getCommandOutput("free -m | head -2 | tail -1 | awk '{print $2}'");
    }

    // Used memory
    public function getUsedValue()
    {
        return $this->getCommandOutput("free -m | head -2 | tail -1 | awk '{print $3}'");
    }

    // Free memory
    public function getActualValue()
    {
        return $this->getCommandOutput("free -m | head -2 | tail -1| awk '{print $4}'");
    }

    // Expected value
    public function getThresholdValue()
    {
        return intval($_ENV['PHYSICAL_MEMORY_THRESHOLD']);
    }

    public function getReport()
    {
        $result = $this->getResult();
        print_r($result);
    }

    public function getGB($memory)
    {
        return $this->getCommandOutput("echo \"scale=2;if($memory<1024 && $memory > 0) print 0;$memory/1024\" | bc -l");
    }

    protected function getResult()
    {
        $total_memory = $this->getGB($this->getTotalValue());
        $used_memory = $this->getGB($this->getUsedValue());
        $free_memory = $this->getGB($this->getActualValue());

        return ['total' => $total_memory, 'used' => $used_memory, 'free' => $free_memory];
    }
}
