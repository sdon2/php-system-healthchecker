<?php

namespace HealthChecker\HealthChecks;

use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

abstract class HealthCheck
{
    protected function getCommandOutput($command, $parseToInt = true)
    {
        $process = Process::fromShellCommandline($command);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        $output = $process->getOutput();

        return $parseToInt ? intval($output) : $output;
    }
}
