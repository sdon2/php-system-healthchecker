<?php

namespace HealthChecker\HealthChecks;

use HealthChecker\Contracts\IHealthCheck;

class PhysicalMemoryHealthCheck extends HealthCheck implements IHealthCheck
{
    public function getHealthCheckName()
    {
        return "Physical Memory";
    }

    // Actual value
    public function getActualValue()
    {
        return ($this->getUsedMemory() * 100) / $this->getTotalMemory();
    }

    // Expected value
    public function getThresholdValue()
    {
        return intval($_ENV['PHYSICAL_MEMORY_THRESHOLD']);
    }

    public function isThresholdFailed()
    {
        $threshold = $this->getThresholdValue();
        $used = $this->getActualValue();

        // If used > threshold return true
        return  $used > $threshold;
    }

    public function getReport()
    {
        $result = $this->getResult();
        $output = "\nTotal \t Used \t Free \t Utilisation %\n";
        return $output . sprintf("%dMB \t %dMB \t %dMB \t %d %%\n", $result['total'], $result['used'], $result['free'], $this->getActualValue());
    }

    protected function getResult()
    {
        $total_memory = $this->getTotalMemory();
        $used_memory = $this->getUsedMemory();
        $free_memory = $this->getFreeMemory();

        return ['total' => $total_memory, 'used' => $used_memory, 'free' => $free_memory];
    }

    // Total Memory
    protected function getTotalMemory()
    {
        return $this->getCommandOutput("free -m | head -2 | tail -1 | awk '{print $2}'");
    }

    // Used memory
    public function getUsedMemory()
    {
        return $this->getCommandOutput("free -m | head -2 | tail -1 | awk '{print $3}'");
    }

    // Free memory
    protected function getFreeMemory()
    {
        return $this->getCommandOutput("free -m | head -2 | tail -1| awk '{print $4}'");
    }
}
