<?php
namespace develnext\tool;

use php\lang\Process;

/**
 * Class Tool
 * @package develnext\tool
 */
abstract class Tool {

    /**
     * @return string
     */
    abstract public function getVersion();

    /**
     * @return string
     */
    abstract public function getBaseCommand();

    /**
     * @param $directory
     * @param array $args
     * @param bool $wait
     * @return Process
     */
    public function execute($directory, array $args = [], $wait = true) {
        $process = new Process([$this->getBaseCommand()] + $args, $directory);
        return $wait ? $process->startAndWait() : $process->start();
    }
}
