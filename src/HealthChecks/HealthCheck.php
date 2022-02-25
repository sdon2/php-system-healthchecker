<?php

namespace HealthChecker\HealthChecks;

use HealthChecker\Tools\Template;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

abstract class HealthCheck
{
    public function getServerName()
    {
        return $_ENV['SERVER_NAME'];
    }

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

    protected function view($file, $data = [])
    {
        $view_path = __DIR__ . "/../.." . $_ENV['VIEW_PATH'];
        $cache_path = __DIR__ . "/../.." . $_ENV['VIEW_CACHE_PATH'];
        $cache_enabled = $_ENV['VIEW_CACHE_ENABLED'] == 'true';
        $template = new Template($view_path, $cache_path, $cache_enabled);
        return $template->view("$view_path/$file", $data);
    }

    public function isHtmlReport()
    {
        return $_ENV['ENABLE_EMAIL'] == 'true';
    }
}
