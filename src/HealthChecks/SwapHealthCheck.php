<?php

namespace HealthChecker\HealthChecks;

use HealthChecker\Contracts\IHealthCheck;
use LucidFrame\Console\ConsoleTable;

class SwapHealthCheck extends HealthCheck implements IHealthCheck
{
    private $memory = [];

    public function getHealthCheckName()
    {
        return "Swap Memory";
    }

    // Actual value
    public function getActualValue()
    {
        $this->populateResult();
        return $this->memory['utilisation'];
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
        $data = $this->memory;
        $data['title'] = $this->getHealthCheckName() . " threshold failed!!";

        if ($this->isHtmlReport()) {
            return $this->view('memory-health', $data);
        } else {
            $output = "\n" . $data['title'] . "\n";
            $table = new ConsoleTable();
            $output .= $table->setHeaders(['Total', 'Used', 'Used %', 'Free', 'Free %', 'Expected', 'Utilisation'])
            ->addRow([$data['total'] . "MB", $data['used'] . "MB", $data['used_percent'] . "%", $data['free'] . "MB", $data['free_percent'] . "%", $data['expected'] . "%", $data['utilisation'] . "%"])
                ->getTable();
            return $output;
        }
    }

    protected function populateResult()
    {
        $total_memory = $this->getTotalMemory();
        $used_memory = $this->getUsedMemory();
        $free_memory = $total_memory - $used_memory;

        $percent = function ($value) use ($total_memory) {
            return intval(($value * 100) / $total_memory);
        };

        $used_percent = $percent($used_memory);
        $free_percent = $percent($free_memory);

        $expected = $this->getThresholdValue();
        $utilisation = $used_percent;

        $this->memory = [
            'total' => $total_memory, 'used' => $used_memory, 'free' => $free_memory,
            'used_percent' => $used_percent, 'free_percent' => $free_percent,
            'expected' => $expected, 'utilisation' => $utilisation,
        ];
    }

    // Total Memory
    protected function getTotalMemory()
    {
        return $this->getCommandOutput("free -m | tail -1 | awk '{print $2}'");
    }

    // Used memory
    protected function getUsedMemory()
    {
        return $this->getCommandOutput("free -m | tail -1 | awk '{print $3}'");
    }

    // Free memory
    protected function getFreeMemory()
    {
        //return $this->getCommandOutput("free -m | tail -1 | awk '{print $4}'");
    }
}
