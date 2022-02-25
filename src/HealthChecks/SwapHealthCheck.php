<?php

namespace HealthChecker\HealthChecks;

use HealthChecker\Contracts\IHealthCheck;
use LucidFrame\Console\ConsoleTable;

class SwapHealthCheck extends HealthCheck implements IHealthCheck
{
    public function getHealthCheckName()
    {
        return "Swap Memory";
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

    public function isCheckPassed()
    {
        $threshold = $this->getThresholdValue();
        $used = $this->getActualValue();

        // If used < threshold return true
        return  $used < $threshold;
    }

    public function getReport()
    {
        $data = $this->getResult();
        $data['title'] = $this->getHealthCheckName() . " threshold failed!!";
        $data['expected'] = $this->getThresholdValue();
        $data['utilisation'] = $this->getActualValue();

        if ($this->isHtmlReport()) {
            return $this->view('memory-health', $data);
        } else {
            $output = "\n" . $data['title'] . "\n";
            $table = new ConsoleTable();
            $output .= $table->setHeaders(['Total', 'Used', 'Free', 'Expected', 'Utilisation'])
                ->addRow([$data['total'] . "MB", $data['used'] . "MB", $data['free'] . "MB", $data['expected'] . "%", $data['utilisation'] . "%"])
                ->getTable();
            return $output;
        }
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
        return $this->getCommandOutput("free -m | tail -1 | awk '{print $2}'");
    }

    // Used memory
    public function getUsedMemory()
    {
        return $this->getCommandOutput("free -m | tail -1 | awk '{print $3}'");
    }

    // Free memory
    protected function getFreeMemory()
    {
        //return $this->getCommandOutput("free -m | tail -1 | awk '{print $4}'");
        return $this->getTotalMemory() - $this->getUsedMemory();
    }
}
